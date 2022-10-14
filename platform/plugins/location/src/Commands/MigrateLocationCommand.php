<?php

namespace Botble\Location\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Location;

class MigrateLocationCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:location:migrate {--class=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate location columns to table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $className = str_replace('/', '\\', $this->option('class'));
        $error = true;

        if (!$className) {
            foreach (Location::supportedModels() as $className) {
                $this->runSchema($className);
                $error = false;
            }
        } elseif (Location::isSupported($className)) {
            $this->runSchema($className);
            $error = false;
        }

        if ($error) {
            $this->error('Not supported model');
        } else {
            $this->info('Migrate location successfully!');
        }
    }

    /**
     * @param string $className
     */
    public function runSchema(string $className)
    {
        $model = new $className();
        Schema::connection($model->getConnectionName())->table($model->getTable(), function (Blueprint $table) use ($className) {
            $table->location($className);
        });
    }
}
