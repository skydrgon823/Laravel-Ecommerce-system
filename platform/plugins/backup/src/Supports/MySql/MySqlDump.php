<?php

/**
 * PHP version of mysqldump cli that comes with MySQL.
 *
 * Tags: mysql mysqldump pdo php7 php5 database php sql hhvm mariadb mysql-backup.
 *
 * @category Library
 *
 * @author   Diego Torres <ifsnop@github.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 *
 * @see     https://github.com/ifsnop/mysqldump-php
 */

namespace Botble\Backup\Supports\MySql;

use Exception;
use PDO;
use PDOException;

/**
 * Class Mysqldump.
 *
 * @category Library
 *
 * @author   Diego Torres <ifsnop@github.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 *
 * @see     https://github.com/ifsnop/mysqldump-php
 */
class MySqlDump
{
    // Same as mysqldump.
    public const MAXLINESIZE = 1000000;

    // List of available compression methods as constants.
    public const NONE = 'None';

    // List of available connection strings.
    public const UTF8 = 'utf8';
    public const BINARY = 'binary';

    /**
     * Database username.
     *
     * @var string
     */
    public $user;

    /**
     * Database password.
     *
     * @var string
     */
    public $pass;

    /**
     * Connection string for PDO.
     *
     * @var string
     */
    public $dsn;

    /**
     * Destination filename, defaults to stdout.
     *
     * @var string
     */
    public $fileName = 'php://stdout';

    // Internal stuff.
    protected $tables = [];
    protected $views = [];
    protected $triggers = [];
    protected $procedures = [];
    protected $functions = [];
    protected $events = [];
    protected $dbHandler = null;
    protected $dbType = '';
    protected $compressManager;
    protected $typeAdapter;
    protected $dumpSettings = [];
    protected $pdoSettings = [];
    protected $version;
    protected $tableColumnTypes = [];
    protected $transformTableRowCallable;
    protected $transformColumnValueCallable;
    protected $infoCallable;

    /**
     * Database name, parsed from dsn.
     *
     * @var string
     */
    protected $dbName;

    /**
     * Host name, parsed from dsn.
     *
     * @var string
     */
    protected $host;

    /**
     * Dsn string parsed as an array.
     *
     * @var array
     */
    protected $dsnArray = [];

    /**
     * Keyed on table name, with the value as the conditions.
     * e.g. - 'users' => 'date_registered > NOW() - INTERVAL 6 MONTH'.
     *
     * @var array
     */
    protected $tableWheres = [];
    protected $tableLimits = [];

    /**
     * Constructor of Mysqldump. Note that in the case of an SQLite database
     * connection, the filename must be in the $db parameter.
     *
     * @param string $dsn PDO DSN connection string
     * @param string $user SQL account username
     * @param string $pass SQL account password
     * @param array $dumpSettings SQL database settings
     * @param array $pdoSettings PDO configured attributes
     *
     * @throws Exception
     */
    public function __construct(
        $dsn = '',
        $user = '',
        $pass = '',
        $dumpSettings = [],
        $pdoSettings = []
    ) {
        $dumpSettingsDefault = [
            'include-tables'             => [],
            'exclude-tables'             => [],
            'include-views'              => [],
            'compress'                   => Mysqldump::NONE,
            'init_commands'              => [],
            'no-data'                    => [],
            'if-not-exists'              => false,
            'reset-auto-increment'       => false,
            'add-drop-database'          => false,
            'add-drop-table'             => false,
            'add-drop-trigger'           => true,
            'add-locks'                  => true,
            'complete-insert'            => false,
            'databases'                  => false,
            'default-character-set'      => Mysqldump::UTF8,
            'disable-keys'               => true,
            'extended-insert'            => true,
            'events'                     => false,
            'hex-blob'                   => true, /* faster than escaped content */
            'insert-ignore'              => false,
            'net_buffer_length'          => self::MAXLINESIZE,
            'no-autocommit'              => false,
            'no-create-info'             => false,
            'lock-tables'                => true,
            'routines'                   => false,
            'single-transaction'         => true,
            'skip-triggers'              => false,
            'skip-tz-utc'                => false,
            'skip-comments'              => false,
            'skip-dump-date'             => false,
            'skip-definer'               => false,
            'where'                      => '',
            /* deprecated */
            'disable-foreign-keys-check' => true,
        ];

        $pdoSettingsDefault = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
        ];

        $this->user = $user;
        $this->pass = $pass;
        $this->parseDsn($dsn);

        // This drops MYSQL dependency, only use the constant if it's defined.
        if ('mysql' === $this->dbType) {
            $pdoSettingsDefault[PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = false;
        }

        $this->pdoSettings = array_replace_recursive($pdoSettingsDefault, $pdoSettings);
        $this->dumpSettings = array_replace_recursive($dumpSettingsDefault, $dumpSettings);
        $this->dumpSettings['init_commands'][] = 'SET NAMES ' . $this->dumpSettings['default-character-set'];

        if (false === $this->dumpSettings['skip-tz-utc']) {
            $this->dumpSettings['init_commands'][] = "SET TIME_ZONE='+00:00'";
        }

        $diff = array_diff(array_keys($this->dumpSettings), array_keys($dumpSettingsDefault));
        if (count($diff) > 0) {
            throw new Exception('Unexpected value in dumpSettings: (' . implode(',', $diff) . ')');
        }

        if (!is_array($this->dumpSettings['include-tables']) ||
            !is_array($this->dumpSettings['exclude-tables'])) {
            throw new Exception('Include-tables and exclude-tables should be arrays');
        }

        // If no include-views is passed in, dump the same views as tables, mimic mysqldump behaviour.
        if (!isset($dumpSettings['include-views'])) {
            $this->dumpSettings['include-views'] = $this->dumpSettings['include-tables'];
        }

        // Create a new compressManager to manage compressed output
        $this->compressManager = CompressManagerFactory::create($this->dumpSettings['compress']);
    }

    /**
     * Destructor of Mysqldump. Unsets dbHandlers and database objects.
     */
    public function __destruct()
    {
        $this->dbHandler = null;
    }

    /**
     * Keyed by table name, with the value as the conditions:
     * e.g. 'users' => 'date_registered > NOW() - INTERVAL 6 MONTH AND deleted=0'.
     */
    public function setTableWheres(array $tableWheres)
    {
        $this->tableWheres = $tableWheres;
    }

    /**
     * @param $tableName
     *
     * @return bool|mixed
     */
    public function getTableWhere($tableName)
    {
        if (!empty($this->tableWheres[$tableName])) {
            return $this->tableWheres[$tableName];
        } elseif ($this->dumpSettings['where']) {
            return $this->dumpSettings['where'];
        }

        return false;
    }

    /**
     * Keyed by table name, with the value as the numeric limit:
     * e.g. 'users' => 3000.
     */
    public function setTableLimits(array $tableLimits)
    {
        $this->tableLimits = $tableLimits;
    }

    /**
     * Returns the LIMIT for the table.  Must be numeric to be returned.
     *
     * @param $tableName
     *
     * @return bool
     */
    public function getTableLimit($tableName)
    {
        if (!isset($this->tableLimits[$tableName])) {
            return false;
        }

        $limit = $this->tableLimits[$tableName];
        if (!is_numeric($limit)) {
            return false;
        }

        return $limit;
    }

    /**
     * Parse DSN string and extract dbname value
     * Several examples of a DSN string
     *   mysql:host=localhost;dbname=testdb
     *   mysql:host=localhost;port=3307;dbname=testdb
     *   mysql:unix_socket=/tmp/mysql.sock;dbname=testdb.
     *
     * @param string $dsn dsn string to parse
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function parseDsn($dsn)
    {
        if (empty($dsn) || (false === ($pos = strpos($dsn, ':')))) {
            throw new Exception('Empty DSN string');
        }

        $this->dsn = $dsn;
        $this->dbType = strtolower(substr($dsn, 0, $pos)); // always returns a string

        if (empty($this->dbType)) {
            throw new Exception('Missing database type from DSN string');
        }

        $dsn = substr($dsn, $pos + 1);

        foreach (explode(';', $dsn) as $kvp) {
            $kvpArr = explode('=', $kvp);
            $this->dsnArray[strtolower($kvpArr[0])] = $kvpArr[1];
        }

        if (empty($this->dsnArray['host']) &&
            empty($this->dsnArray['unix_socket'])) {
            throw new Exception('Missing host from DSN string');
        }
        $this->host = (!empty($this->dsnArray['host'])) ?
            $this->dsnArray['host'] : $this->dsnArray['unix_socket'];

        if (empty($this->dsnArray['dbname'])) {
            throw new Exception('Missing database name from DSN string');
        }

        $this->dbName = $this->dsnArray['dbname'];

        return true;
    }

    /**
     * Connect with PDO.
     *
     * @return null
     *
     * @throws Exception
     */
    protected function connect()
    {
        // Connecting with PDO.
        try {
            switch ($this->dbType) {
                case 'sqlite':
                    $this->dbHandler = @new PDO('sqlite:' . $this->dbName, null, null, $this->pdoSettings);
                    break;
                case 'mysql':
                case 'pgsql':
                case 'dblib':
                    $this->dbHandler = @new PDO(
                        $this->dsn,
                        $this->user,
                        $this->pass,
                        $this->pdoSettings
                    );
                    // Execute init commands once connected
                    foreach ($this->dumpSettings['init_commands'] as $stmt) {
                        $this->dbHandler->exec($stmt);
                    }
                    // Store server version
                    $this->version = $this->dbHandler->getAttribute(PDO::ATTR_SERVER_VERSION);
                    break;
                default:
                    throw new Exception('Unsupported database type (' . $this->dbType . ')');
            }
        } catch (PDOException $e) {
            throw new Exception('Connection to ' . $this->dbType . ' failed with message: ' . $e->getMessage());
        }

        if (is_null($this->dbHandler)) {
            throw new Exception('Connection to ' . $this->dbType . 'failed');
        }

        $this->dbHandler->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);
        $this->typeAdapter = TypeAdapterFactory::create($this->dbType, $this->dbHandler, $this->dumpSettings);
    }

    /**
     * Primary function, triggers dumping.
     *
     * @param string $filename Name of file to write sql dump to
     *
     * @return null
     *
     * @throws \Exception
     */
    public function start($filename = '')
    {
        // Output file can be redefined here
        if (!empty($filename)) {
            $this->fileName = $filename;
        }

        // Connect to database
        $this->connect();

        // Create output file
        $this->compressManager->open($this->fileName);

        // Write some basic info to output file
        $this->compressManager->write($this->getDumpFileHeader());

        // Store server settings and use sanner defaults to dump
        $this->compressManager->write(
            $this->typeAdapter->backupParameters()
        );

        if ($this->dumpSettings['databases']) {
            $this->compressManager->write(
                $this->typeAdapter->getDatabaseHeader($this->dbName)
            );
            if ($this->dumpSettings['add-drop-database']) {
                $this->compressManager->write(
                    $this->typeAdapter->addDropDatabase($this->dbName)
                );
            }
        }

        // Get table, view, trigger, procedures, functions and events structures from
        // database.
        $this->getDatabaseStructureTables();
        $this->getDatabaseStructureViews();
        $this->getDatabaseStructureTriggers();
        $this->getDatabaseStructureProcedures();
        $this->getDatabaseStructureFunctions();
        $this->getDatabaseStructureEvents();

        if ($this->dumpSettings['databases']) {
            $this->compressManager->write(
                $this->typeAdapter->databases($this->dbName)
            );
        }

        // If there still are some tables/views in include-tables array,
        // that means that some tables or views weren't found.
        // Give proper error and exit.
        // This check will be removed once include-tables supports regexps.
        if (0 < count($this->dumpSettings['include-tables'])) {
            $name = implode(',', $this->dumpSettings['include-tables']);
            throw new Exception('Table (' . $name . ') not found in database');
        }

        $this->exportTables();
        $this->exportTriggers();
        $this->exportFunctions();
        $this->exportProcedures();
        $this->exportViews();
        $this->exportEvents();

        // Restore saved parameters.
        $this->compressManager->write(
            $this->typeAdapter->restoreParameters()
        );
        // Write some stats to output file.
        $this->compressManager->write($this->getDumpFileFooter());
        // Close output file.
        $this->compressManager->close();

        return;
    }

    /**
     * Returns header for dump file.
     *
     * @return string
     */
    protected function getDumpFileHeader()
    {
        $header = '';
        if (!$this->dumpSettings['skip-comments']) {
            // Some info about software, source and time
            $header = '-- mysqldump-php https://github.com/ifsnop/mysqldump-php' . PHP_EOL .
                '--' . PHP_EOL .
                '-- Host: ' . $this->host . '	Database: ' . $this->dbName . PHP_EOL .
                '-- ------------------------------------------------------' . PHP_EOL;

            if (!empty($this->version)) {
                $header .= "-- Server version \t" . $this->version . PHP_EOL;
            }

            if (!$this->dumpSettings['skip-dump-date']) {
                $header .= '-- Date: ' . date('r') . PHP_EOL . PHP_EOL;
            }
        }

        return $header;
    }

    /**
     * Returns footer for dump file.
     *
     * @return string
     */
    protected function getDumpFileFooter()
    {
        $footer = '';
        if (!$this->dumpSettings['skip-comments']) {
            $footer .= '-- Dump completed';
            if (!$this->dumpSettings['skip-dump-date']) {
                $footer .= ' on: ' . date('r');
            }
            $footer .= PHP_EOL;
        }

        return $footer;
    }

    /**
     * Reads table names from database.
     * Fills $this->tables array so they will be dumped later.
     *
     * @return null
     */
    protected function getDatabaseStructureTables()
    {
        // Listing all tables from database
        if (empty($this->dumpSettings['include-tables'])) {
            // include all tables for now, blacklisting happens later
            foreach ($this->dbHandler->query($this->typeAdapter->showTables($this->dbName)) as $row) {
                $this->tables[] = current($row);
            }
        } else {
            // include only the tables mentioned in include-tables
            foreach ($this->dbHandler->query($this->typeAdapter->showTables($this->dbName)) as $row) {
                if (in_array(current($row), $this->dumpSettings['include-tables'], true)) {
                    $this->tables[] = current($row);
                    $elem = array_search(
                        current($row),
                        $this->dumpSettings['include-tables']
                    );
                    unset($this->dumpSettings['include-tables'][$elem]);
                }
            }
        }

        return;
    }

    /**
     * Reads view names from database.
     * Fills $this->tables array so they will be dumped later.
     *
     * @return null
     */
    protected function getDatabaseStructureViews()
    {
        // Listing all views from database
        if (empty($this->dumpSettings['include-views'])) {
            // include all views for now, blacklisting happens later
            foreach ($this->dbHandler->query($this->typeAdapter->showViews($this->dbName)) as $row) {
                $this->views[] = current($row);
            }
        } else {
            // include only the tables mentioned in include-tables
            foreach ($this->dbHandler->query($this->typeAdapter->showViews($this->dbName)) as $row) {
                if (in_array(current($row), $this->dumpSettings['include-views'], true)) {
                    $this->views[] = current($row);
                    $elem = array_search(
                        current($row),
                        $this->dumpSettings['include-views']
                    );
                    unset($this->dumpSettings['include-views'][$elem]);
                }
            }
        }

        return;
    }

    /**
     * Reads trigger names from database.
     * Fills $this->tables array so they will be dumped later.
     *
     * @return null
     */
    protected function getDatabaseStructureTriggers()
    {
        // Listing all triggers from database
        if (false === $this->dumpSettings['skip-triggers']) {
            foreach ($this->dbHandler->query($this->typeAdapter->showTriggers($this->dbName)) as $row) {
                $this->triggers[] = $row['Trigger'];
            }
        }

        return;
    }

    /**
     * Reads procedure names from database.
     * Fills $this->tables array so they will be dumped later.
     *
     * @return null
     */
    protected function getDatabaseStructureProcedures()
    {
        // Listing all procedures from database
        if ($this->dumpSettings['routines']) {
            foreach ($this->dbHandler->query($this->typeAdapter->showProcedures($this->dbName)) as $row) {
                $this->procedures[] = $row['procedure_name'];
            }
        }

        return;
    }

    /**
     * Reads functions names from database.
     * Fills $this->tables array so they will be dumped later.
     *
     * @return null
     */
    protected function getDatabaseStructureFunctions()
    {
        // Listing all functions from database
        if ($this->dumpSettings['routines']) {
            foreach ($this->dbHandler->query($this->typeAdapter->showFunctions($this->dbName)) as $row) {
                $this->functions[] = $row['function_name'];
            }
        }

        return;
    }

    /**
     * Reads event names from database.
     * Fills $this->tables array so they will be dumped later.
     *
     * @return null
     */
    protected function getDatabaseStructureEvents()
    {
        // Listing all events from database
        if ($this->dumpSettings['events']) {
            foreach ($this->dbHandler->query($this->typeAdapter->showEvents($this->dbName)) as $row) {
                $this->events[] = $row['event_name'];
            }
        }
    }

    /**
     * Compare if $table name matches with a definition inside $arr.
     *
     * @param $table string
     * @param $arr array with strings or patterns
     *
     * @return bool
     */
    protected function matches($table, $arr)
    {
        $match = false;

        foreach ($arr as $pattern) {
            if ('/' != $pattern[0]) {
                continue;
            }
            if (1 == preg_match($pattern, $table)) {
                $match = true;
            }
        }

        return in_array($table, $arr) || $match;
    }

    /**
     * Exports all the tables selected from database.
     *
     * @return null
     * @throws Exception
     */
    protected function exportTables()
    {
        // Exporting tables one by one
        foreach ($this->tables as $table) {
            if ($this->matches($table, $this->dumpSettings['exclude-tables'])) {
                continue;
            }
            $this->getTableStructure($table);
            if (false === $this->dumpSettings['no-data']) { // don't break compatibility with old trigger
                $this->listValues($table);
            } elseif (true === $this->dumpSettings['no-data']
                || $this->matches($table, $this->dumpSettings['no-data'])) {
                continue;
            } else {
                $this->listValues($table);
            }
        }
    }

    /**
     * Exports all the views found in database.
     *
     * @return null
     */
    protected function exportViews()
    {
        if (false === $this->dumpSettings['no-create-info']) {
            // Exporting views one by one
            foreach ($this->views as $view) {
                if ($this->matches($view, $this->dumpSettings['exclude-tables'])) {
                    continue;
                }
                $this->tableColumnTypes[$view] = $this->getTableColumnTypes($view);
                $this->getViewStructureTable($view);
            }
            foreach ($this->views as $view) {
                if ($this->matches($view, $this->dumpSettings['exclude-tables'])) {
                    continue;
                }
                $this->getViewStructureView($view);
            }
        }
    }

    /**
     * Exports all the triggers found in database.
     *
     * @return null
     */
    protected function exportTriggers()
    {
        // Exporting triggers one by one
        foreach ($this->triggers as $trigger) {
            $this->getTriggerStructure($trigger);
        }
    }

    /**
     * Exports all the procedures found in database.
     *
     * @return null
     */
    protected function exportProcedures()
    {
        // Exporting triggers one by one
        foreach ($this->procedures as $procedure) {
            $this->getProcedureStructure($procedure);
        }
    }

    /**
     * Exports all the functions found in database.
     *
     * @return null
     */
    protected function exportFunctions()
    {
        // Exporting triggers one by one
        foreach ($this->functions as $function) {
            $this->getFunctionStructure($function);
        }
    }

    /**
     * Exports all the events found in database.
     *
     * @return null
     */
    protected function exportEvents()
    {
        // Exporting triggers one by one
        foreach ($this->events as $event) {
            $this->getEventStructure($event);
        }
    }

    /**
     * Table structure extractor.
     *
     * @param string $tableName Name of table to export
     *
     * @return null
     *
     * @throws Exception
     * @todo move specific mysql code to typeAdapter
     */
    protected function getTableStructure($tableName)
    {
        if (!$this->dumpSettings['no-create-info']) {
            $ret = '';
            if (!$this->dumpSettings['skip-comments']) {
                $ret = '--' . PHP_EOL .
                    '-- Table structure for table ' . $tableName . PHP_EOL .
                    '--' . PHP_EOL . PHP_EOL;
            }
            $stmt = $this->typeAdapter->showCreateTable($tableName);
            foreach ($this->dbHandler->query($stmt) as $r) {
                $this->compressManager->write($ret);
                if ($this->dumpSettings['add-drop-table']) {
                    $this->compressManager->write(
                        $this->typeAdapter->dropTable($tableName)
                    );
                }
                $this->compressManager->write(
                    $this->typeAdapter->createTable($r)
                );
                break;
            }
        }
        $this->tableColumnTypes[$tableName] = $this->getTableColumnTypes($tableName);

        return;
    }

    /**
     * Store column types to create data dumps and for Stand-In tables.
     *
     * @param string $tableName Name of table to export
     *
     * @return array type column types detailed
     */
    protected function getTableColumnTypes($tableName)
    {
        $columnTypes = [];
        $columns = $this->dbHandler->query(
            $this->typeAdapter->showColumns($tableName)
        );
        $columns->setFetchMode(PDO::FETCH_ASSOC);

        foreach ($columns as $key => $col) {
            $types = $this->typeAdapter->parseColumnType($col);
            $columnTypes[$col['Field']] = [
                'is_numeric' => $types['is_numeric'],
                'is_blob'    => $types['is_blob'],
                'type'       => $types['type'],
                'type_sql'   => $col['Type'],
                'is_virtual' => $types['is_virtual'],
            ];
        }

        return $columnTypes;
    }

    /**
     * View structure extractor, create table (avoids cyclic references).
     *
     * @param string $viewName Name of view to export
     *
     * @return null
     *
     * @todo move mysql specific code to typeAdapter
     */
    protected function getViewStructureTable($viewName)
    {
        if (!$this->dumpSettings['skip-comments']) {
            $ret = '--' . PHP_EOL .
                "-- Stand-In structure for view `${viewName}`" . PHP_EOL .
                '--' . PHP_EOL . PHP_EOL;
            $this->compressManager->write($ret);
        }
        $stmt = $this->typeAdapter->showCreateView($viewName);

        // create views as tables, to resolve dependencies
        foreach ($this->dbHandler->query($stmt) as $r) {
            if ($this->dumpSettings['add-drop-table']) {
                $this->compressManager->write(
                    $this->typeAdapter->dropView($viewName)
                );
            }

            $this->compressManager->write(
                $this->createStandInTable($viewName)
            );
            break;
        }
    }

    /**
     * Write a create table statement for the table Stand-In, show create
     * table would return a create algorithm when used on a view.
     *
     * @param string $viewName Name of view to export
     *
     * @return string create statement
     */
    public function createStandInTable($viewName)
    {
        $ret = [];
        foreach ($this->tableColumnTypes[$viewName] as $k => $v) {
            $ret[] = "`${k}` ${v['type_sql']}";
        }
        $ret = implode(PHP_EOL . ',', $ret);

        $ret = 'CREATE TABLE IF NOT EXISTS ' . $viewName . ' (' .
            PHP_EOL . $ret . PHP_EOL . ');' . PHP_EOL;

        return $ret;
    }

    /**
     * View structure extractor, create view.
     *
     * @param string $viewName Name of view to export
     *
     * @return null
     *
     * @todo move mysql specific code to typeAdapter
     */
    protected function getViewStructureView($viewName)
    {
        if (!$this->dumpSettings['skip-comments']) {
            $ret = '--' . PHP_EOL .
                "-- View structure for view `${viewName}`" . PHP_EOL .
                '--' . PHP_EOL . PHP_EOL;
            $this->compressManager->write($ret);
        }
        $stmt = $this->typeAdapter->showCreateView($viewName);

        // create views, to resolve dependencies
        // replacing tables with views
        foreach ($this->dbHandler->query($stmt) as $r) {
            // because we must replace table with view, we should delete it
            $this->compressManager->write(
                $this->typeAdapter->dropView($viewName)
            );
            $this->compressManager->write(
                $this->typeAdapter->createView($r)
            );
            break;
        }
    }

    /**
     * Trigger structure extractor.
     *
     * @param string $triggerName Name of trigger to export
     *
     * @return null
     */
    protected function getTriggerStructure($triggerName)
    {
        $stmt = $this->typeAdapter->showCreateTrigger($triggerName);
        foreach ($this->dbHandler->query($stmt) as $r) {
            if ($this->dumpSettings['add-drop-trigger']) {
                $this->compressManager->write(
                    $this->typeAdapter->addDropTrigger($triggerName)
                );
            }
            $this->compressManager->write(
                $this->typeAdapter->createTrigger($r)
            );

            return;
        }
    }

    /**
     * Procedure structure extractor.
     *
     * @param string $procedureName Name of procedure to export
     *
     * @return null
     */
    protected function getProcedureStructure($procedureName)
    {
        if (!$this->dumpSettings['skip-comments']) {
            $ret = '--' . PHP_EOL .
                "-- Dumping routines for database '" . $this->dbName . "'" . PHP_EOL .
                '--' . PHP_EOL . PHP_EOL;
            $this->compressManager->write($ret);
        }
        $stmt = $this->typeAdapter->showCreateProcedure($procedureName);
        foreach ($this->dbHandler->query($stmt) as $r) {
            $this->compressManager->write(
                $this->typeAdapter->createProcedure($r)
            );

            return;
        }
    }

    /**
     * Function structure extractor.
     *
     * @param string $functionName Name of function to export
     *
     * @return null
     */
    protected function getFunctionStructure($functionName)
    {
        if (!$this->dumpSettings['skip-comments']) {
            $ret = '--' . PHP_EOL .
                "-- Dumping routines for database '" . $this->dbName . "'" . PHP_EOL .
                '--' . PHP_EOL . PHP_EOL;
            $this->compressManager->write($ret);
        }
        $stmt = $this->typeAdapter->showCreateFunction($functionName);
        foreach ($this->dbHandler->query($stmt) as $r) {
            $this->compressManager->write(
                $this->typeAdapter->createFunction($r)
            );

            return;
        }
    }

    /**
     * Event structure extractor.
     *
     * @param string $eventName Name of event to export
     *
     * @return null
     */
    protected function getEventStructure($eventName)
    {
        if (!$this->dumpSettings['skip-comments']) {
            $ret = '--' . PHP_EOL .
                "-- Dumping events for database '" . $this->dbName . "'" . PHP_EOL .
                '--' . PHP_EOL . PHP_EOL;
            $this->compressManager->write($ret);
        }
        $stmt = $this->typeAdapter->showCreateEvent($eventName);
        foreach ($this->dbHandler->query($stmt) as $r) {
            $this->compressManager->write(
                $this->typeAdapter->createEvent($r)
            );

            return;
        }
    }

    /**
     * Prepare values for output.
     *
     * @param string $tableName Name of table which contains rows
     * @param array $row Associative array of column names and values to be
     *                          quoted
     *
     * @return array
     */
    protected function prepareColumnValues($tableName, array $row)
    {
        $ret = [];
        $columnTypes = $this->tableColumnTypes[$tableName];

        if ($this->transformTableRowCallable) {
            $row = call_user_func($this->transformTableRowCallable, $tableName, $row);
        }

        foreach ($row as $colName => $colValue) {
            if ($this->transformColumnValueCallable) {
                $colValue = call_user_func($this->transformColumnValueCallable, $tableName, $colName, $colValue, $row);
            }

            $ret[] = $this->escape($colValue, $columnTypes[$colName]);
        }

        return $ret;
    }

    /**
     * Escape values with quotes when needed.
     *
     * @param string $tableName Name of table which contains rows
     * @param array $row Associative array of column names and values to be quoted
     *
     * @return string
     */
    protected function escape($colValue, $colType)
    {
        if (is_null($colValue)) {
            return 'NULL';
        } elseif ($this->dumpSettings['hex-blob'] && $colType['is_blob']) {
            if ('bit' == $colType['type'] || !empty($colValue)) {
                return "0x${colValue}";
            }

            return "''";
        } elseif ($colType['is_numeric']) {
            return $colValue;
        }

        return $this->dbHandler->quote($colValue);
    }

    /**
     * Set a callable that will be used to transform table rows.
     *
     * @param callable $callable
     *
     * @return void
     */
    public function setTransformTableRowHook($callable)
    {
        $this->transformTableRowCallable = $callable;
    }

    /**
     * Set a callable that will be used to transform column values.
     *
     * @param callable $callable
     *
     * @return void
     *
     * @deprecated Use setTransformTableRowHook instead for better performance
     */
    public function setTransformColumnValueHook($callable)
    {
        $this->transformColumnValueCallable = $callable;
    }

    /**
     * Set a callable that will be used to report dump information.
     *
     * @param callable $callable
     *
     * @return void
     */
    public function setInfoHook($callable)
    {
        $this->infoCallable = $callable;
    }

    /**
     * Table rows extractor.
     *
     * @param string $tableName Name of table to export
     *
     * @return null
     *
     * @throws Exception
     */
    protected function listValues($tableName)
    {
        $this->prepareListValues($tableName);

        $onlyOnce = true;
        $lineSize = 0;

        // colStmt is used to form a query to obtain row values
        $colStmt = $this->getColumnStmt($tableName);
        // colNames is used to get the name of the columns when using complete-insert
        if ($this->dumpSettings['complete-insert']) {
            $colNames = $this->getColumnNames($tableName);
        }

        $stmt = 'SELECT ' . implode(',', $colStmt) . ' FROM `' . $tableName . '`';

        // Table specific conditions override the default 'where'
        $condition = $this->getTableWhere($tableName);

        if ($condition) {
            $stmt .= ' WHERE ' . $condition;
        }

        $limit = $this->getTableLimit($tableName);

        if (false !== $limit) {
            $stmt .= ' LIMIT ' . $limit;
        }

        $resultSet = $this->dbHandler->query($stmt);
        $resultSet->setFetchMode(PDO::FETCH_ASSOC);

        $ignore = $this->dumpSettings['insert-ignore'] ? '  IGNORE' : '';

        $count = 0;
        foreach ($resultSet as $row) {
            ++$count;
            $vals = $this->prepareColumnValues($tableName, $row);
            if ($onlyOnce || !$this->dumpSettings['extended-insert']) {
                if ($this->dumpSettings['complete-insert']) {
                    $lineSize += $this->compressManager->write(
                        'INSERT' . $ignore . ' INTO `' . $tableName . '` (' .
                        implode(', ', $colNames) .
                        ') VALUES (' . implode(',', $vals) . ')'
                    );
                } else {
                    $lineSize += $this->compressManager->write(
                        'INSERT' . $ignore . ' INTO `' . $tableName . '` VALUES (' . implode(',', $vals) . ')'
                    );
                }
                $onlyOnce = false;
            } else {
                $lineSize += $this->compressManager->write(',(' . implode(',', $vals) . ')');
            }
            if (($lineSize > $this->dumpSettings['net_buffer_length']) ||
                !$this->dumpSettings['extended-insert']) {
                $onlyOnce = true;
                $lineSize = $this->compressManager->write(';' . PHP_EOL);
            }
        }
        $resultSet->closeCursor();

        if (!$onlyOnce) {
            $this->compressManager->write(';' . PHP_EOL);
        }

        $this->endListValues($tableName, $count);

        if ($this->infoCallable) {
            call_user_func($this->infoCallable, 'table', ['name' => $tableName, 'rowCount' => $count]);
        }
    }

    /**
     * Table rows extractor, append information prior to dump.
     *
     * @param string $tableName Name of table to export
     *
     * @return null
     *
     * @throws Exception
     */
    public function prepareListValues($tableName)
    {
        if (!$this->dumpSettings['skip-comments']) {
            $this->compressManager->write(
                '--' . PHP_EOL .
                '-- Dumping data for table `' . $tableName . '`' . PHP_EOL .
                '--' . PHP_EOL . PHP_EOL
            );
        }

        if ($this->dumpSettings['single-transaction']) {
            $this->dbHandler->exec($this->typeAdapter->setupTransaction());
            $this->dbHandler->exec($this->typeAdapter->startTransaction());
        }

        if ($this->dumpSettings['lock-tables'] && !$this->dumpSettings['single-transaction']) {
            $this->typeAdapter->lockTable($tableName);
        }

        if ($this->dumpSettings['add-locks']) {
            $this->compressManager->write(
                $this->typeAdapter->startAddLockTable($tableName)
            );
        }

        if ($this->dumpSettings['disable-keys']) {
            $this->compressManager->write(
                $this->typeAdapter->startAddDisableKeys($tableName)
            );
        }

        // Disable autocommit for faster reload
        if ($this->dumpSettings['no-autocommit']) {
            $this->compressManager->write(
                $this->typeAdapter->startDisableAutocommit()
            );
        }

        return;
    }

    /**
     * Table rows extractor, close locks and commits after dump.
     *
     * @param string $tableName name of table to export
     * @param int $count number of rows inserted
     *
     * @return void
     * @throws Exception
     */
    public function endListValues($tableName, $count = 0)
    {
        if ($this->dumpSettings['disable-keys']) {
            $this->compressManager->write(
                $this->typeAdapter->endAddDisableKeys($tableName)
            );
        }

        if ($this->dumpSettings['add-locks']) {
            $this->compressManager->write(
                $this->typeAdapter->endAddLockTable($tableName)
            );
        }

        if ($this->dumpSettings['single-transaction']) {
            $this->dbHandler->exec($this->typeAdapter->commitTransaction());
        }

        if ($this->dumpSettings['lock-tables'] && !$this->dumpSettings['single-transaction']) {
            $this->typeAdapter->unlockTable($tableName);
        }

        // Commit to enable autocommit
        if ($this->dumpSettings['no-autocommit']) {
            $this->compressManager->write(
                $this->typeAdapter->endDisableAutocommit()
            );
        }

        $this->compressManager->write(PHP_EOL);

        if (!$this->dumpSettings['skip-comments']) {
            $this->compressManager->write(
                '-- Dumped table `' . $tableName . '` with ' . $count . ' row(s)' . PHP_EOL .
                '--' . PHP_EOL . PHP_EOL
            );
        }

        return;
    }

    /**
     * Build SQL List of all columns on current table which will be used for selecting.
     *
     * @param string $tableName Name of table to get columns
     *
     * @return array SQL sentence with columns for select
     */
    public function getColumnStmt($tableName)
    {
        $colStmt = [];
        foreach ($this->tableColumnTypes[$tableName] as $colName => $colType) {
            if ('bit' == $colType['type'] && $this->dumpSettings['hex-blob']) {
                $colStmt[] = "LPAD(HEX(`${colName}`),2,'0') AS `${colName}`";
            } elseif ($colType['is_blob'] && $this->dumpSettings['hex-blob']) {
                $colStmt[] = "HEX(`${colName}`) AS `${colName}`";
            } elseif ($colType['is_virtual']) {
                $this->dumpSettings['complete-insert'] = true;
            } else {
                $colStmt[] = "`${colName}`";
            }
        }

        return $colStmt;
    }

    /**
     * Build SQL List of all columns on current table which will be used for inserting.
     *
     * @param string $tableName Name of table to get columns
     *
     * @return array columns for sql sentence for insert
     */
    public function getColumnNames($tableName)
    {
        $colNames = [];
        foreach ($this->tableColumnTypes[$tableName] as $colName => $colType) {
            if ($colType['is_virtual']) {
                $this->dumpSettings['complete-insert'] = true;
            } else {
                $colNames[] = "`${colName}`";
            }
        }

        return $colNames;
    }
}
