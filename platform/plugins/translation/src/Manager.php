<?php

namespace Botble\Translation;

use BaseHelper;
use Botble\Base\Supports\MountManager;
use Botble\Base\Supports\PclZip as Zip;
use Botble\Translation\Models\Translation;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Lang;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem as Flysystem;
use Symfony\Component\VarExporter\Exception\ExceptionInterface;
use Symfony\Component\VarExporter\VarExporter;
use Theme;
use ZipArchive;

class Manager
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var array|\ArrayAccess
     */
    protected $config;

    /**
     * Manager constructor.
     * @param Application $app
     * @param Filesystem $files
     */
    public function __construct(Application $app, Filesystem $files)
    {
        $this->app = $app;
        $this->files = $files;
        $this->config = $app['config']['plugins.translation.general'];
    }

    /**
     * @param bool $replace
     * @return int
     */
    public function importTranslations(bool $replace = false): int
    {
        try {
            $this->publishLocales();
        } catch (Exception $exception) {
            info($exception->getMessage());
        }

        $counter = 0;

        foreach ($this->files->directories($this->app['path.lang']) as $langPath) {
            $locale = basename($langPath);
            foreach ($this->files->allfiles($langPath) as $file) {
                $info = pathinfo($file);
                $group = $info['filename'];
                if (in_array($group, $this->config['exclude_groups'])) {
                    continue;
                }
                $subLangPath = str_replace($langPath . DIRECTORY_SEPARATOR, '', $info['dirname']);
                $subLangPath = str_replace(DIRECTORY_SEPARATOR, '/', $subLangPath);
                $langDirectory = $group;
                if ($subLangPath != $langPath) {
                    $langDirectory = $subLangPath . '/' . $group;
                    $group = substr($subLangPath, 0, -3) . '/' . $group;
                }

                $translations = Lang::getLoader()->load($locale, $langDirectory);
                if ($translations && is_array($translations)) {
                    foreach (Arr::dot($translations) as $key => $value) {
                        $importedTranslation = $this->importTranslation(
                            $key,
                            $value,
                            ($locale != 'vendor' ? $locale : substr($subLangPath, -2)),
                            $group,
                            $replace
                        );
                        $counter += $importedTranslation ? 1 : 0;
                    }
                }
            }
        }

        return $counter;
    }

    public function publishLocales()
    {
        $paths = ServiceProvider::pathsToPublish(null, 'cms-lang');

        foreach ($paths as $from => $to) {
            if ($this->files->isFile($from)) {
                if (!$this->files->isDirectory(dirname($to))) {
                    $this->files->makeDirectory(dirname($to), 0755, true);
                }
                $this->files->copy($from, $to);
            } elseif ($this->files->isDirectory($from)) {
                $manager = new MountManager([
                    'from' => new Flysystem(new LocalAdapter($from)),
                    'to'   => new Flysystem(new LocalAdapter($to)),
                ]);

                foreach ($manager->listContents('from://', true) as $file) {
                    if ($file['type'] === 'file') {
                        $manager->put('to://' . $file['path'], $manager->read('from://' . $file['path']));
                    }
                }
            }
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $locale
     * @param string $group
     * @param bool $replace
     * @return bool
     */
    public function importTranslation($key, $value, $locale, $group, bool $replace = false): bool
    {
        // process only string values
        if (is_array($value)) {
            return false;
        }

        $value = (string)$value;
        $translation = Translation::firstOrNew([
            'locale' => $locale,
            'group'  => $group,
            'key'    => $key,
        ]);

        // Check if the database is different from files
        $newStatus = $translation->value === $value ? Translation::STATUS_SAVED : Translation::STATUS_CHANGED;
        if ($newStatus !== (int)$translation->status) {
            $translation->status = $newStatus;
        }

        // Only replace when empty, or explicitly told so
        if ($replace || !$translation->value) {
            $translation->value = $value;
        }

        $translation->save();

        return true;
    }

    /**
     * @param null $group
     * @throws ExceptionInterface
     */
    public function exportTranslations($group = null)
    {
        if (!empty($group)) {
            if (!in_array($group, $this->config['exclude_groups'])) {
                if ($group == '*') {
                    return $this->exportAllTranslations();
                }

                $tree = $this->makeTree(Translation::ofTranslatedGroup($group)->orderByGroupKeys(Arr::get(
                    $this->config,
                    'sort_keys',
                    false
                ))->get());

                foreach ($tree as $locale => $groups) {
                    if (isset($groups[$group])) {
                        $translations = $groups[$group];
                        $file = $locale . '/' . $group;

                        if (!$this->files->isDirectory(lang_path($locale))) {
                            $this->files->makeDirectory(lang_path($locale), 755, true);
                            system('find ' . lang_path($locale) . ' -type d -exec chmod 755 {} \;');
                        }

                        $groups = explode('/', $group);
                        if (count($groups) > 1) {
                            $folderName = Arr::last($groups);
                            Arr::forget($groups, count($groups) - 1);

                            $dir = 'vendor/' . implode('/', $groups) . '/' . $locale;
                            if (!$this->files->isDirectory(lang_path($dir))) {
                                $this->files->makeDirectory(lang_path($dir), 755, true);
                                system('find ' . lang_path($dir) . ' -type d -exec chmod 755 {} \;');
                            }

                            $file = $dir . '/' . $folderName;
                        }
                        $path = lang_path($file . '.php');
                        $output = "<?php\n\nreturn " . VarExporter::export($translations) . ";\n";
                        $this->files->put($path, $output);
                    }
                }

                Translation::ofTranslatedGroup($group)->update(['status' => Translation::STATUS_SAVED]);
            }
        }
    }

    /**
     * @return bool
     * @throws ExceptionInterface
     */
    public function exportAllTranslations(): bool
    {
        $groups = Translation::whereNotNull('value')->selectDistinctGroup()->get('group');

        foreach ($groups as $group) {
            $this->exportTranslations($group->group);
        }

        return true;
    }

    /**
     * @param array|object $translations
     * @return array
     */
    protected function makeTree($translations): array
    {
        $array = [];
        foreach ($translations as $translation) {
            Arr::set($array[$translation->locale][$translation->group], $translation->key, $translation->value);
        }

        return $array;
    }

    /**
     * @throws Exception
     */
    public function cleanTranslations()
    {
        Translation::whereNull('value')->delete();
    }

    public function truncateTranslations()
    {
        Translation::truncate();
    }

    /**
     * @param null|string $key
     * @return mixed
     */
    public function getConfig(?string $key = null)
    {
        if ($key == null) {
            return $this->config;
        }

        return $this->config[$key];
    }

    /**
     * @return bool
     */
    public function removeUnusedThemeTranslations(): bool
    {
        if (!defined('THEME_MODULE_SCREEN_NAME')) {
            return false;
        }

        foreach ($this->files->allFiles(lang_path()) as $file) {
            if ($this->files->isFile($file) && $file->getExtension() === 'json') {
                $locale = $file->getFilenameWithoutExtension();

                if ($locale == 'en') {
                    continue;
                }

                $translations = BaseHelper::getFileData($file->getRealPath());

                $defaultEnglishFile = theme_path(Theme::getThemeName() . '/lang/en.json');

                if ($defaultEnglishFile) {
                    $enTranslations = BaseHelper::getFileData($defaultEnglishFile);
                    $translations = array_merge($enTranslations, $translations);

                    $enTranslationKeys = array_keys($enTranslations);

                    foreach ($translations as $key => $translation) {
                        if (!in_array($key, $enTranslationKeys)) {
                            Arr::forget($translations, $key);
                        }
                    }
                }

                ksort($translations);

                $this->files->put($file->getRealPath(), json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        }

        return true;
    }

    /**
     * @return array|string[]
     */
    public function getRemoteAvailableLocales(): array
    {
        $client = new Client(['verify' => false]);

        try {
            $info = $client->request('GET', 'https://api.github.com/repos/botble/translations/git/trees/master', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ],
            ]);

            $info = json_decode($info->getBody()->getContents(), true);

            $availableLocales = [];

            foreach ($info['tree'] as $tree) {
                if (in_array($tree['path'], ['.gitignore', 'README.md'])) {
                    continue;
                }

                $availableLocales[] = $tree['path'];
            }
        } catch (Exception|GuzzleException $exception) {
            $availableLocales = ['ar', 'es', 'vi'];
        }

        return $availableLocales;
    }

    /**
     * @param string $locale
     * @return array|false[]
     */
    public function downloadRemoteLocale(string $locale): array
    {
        $repository = 'https://github.com/botble/translations';

        $destination = storage_path('app/translation-files.zip');

        $client = new Client(['verify' => false]);

        $availableLocales = $this->getRemoteAvailableLocales();

        if (!in_array($locale, $availableLocales)) {
            return [
                'error'   => true,
                'message' => 'This locale is not available on ' . $repository,
            ];
        }

        try {
            $client->request('GET', $repository . '/archive/refs/heads/master.zip', [
                'sink' => Utils::tryFopen($destination, 'w'),
            ]);
        } catch (Exception|GuzzleException $exception) {
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }

        if (class_exists('ZipArchive', false)) {
            $zip = new ZipArchive();
            $res = $zip->open($destination);
            if ($res === true) {
                $zip->extractTo(storage_path('app'));
                $zip->close();
            } else {
                return [
                    'error'   => true,
                    'message' => 'Extract translation files failed!',
                ];
            }
        } else {
            $archive = new Zip($destination);
            $archive->extract(PCLZIP_OPT_PATH, storage_path('app'));
        }

        if (File::exists($destination)) {
            unlink($destination);
        }

        $localePath = storage_path('app/translations-master/' . $locale);

        File::copyDirectory($localePath . '/' . $locale, lang_path($locale));
        File::copyDirectory($localePath . '/vendor', lang_path('vendor'));
        if (File::exists($localePath . '/' . $locale . '.json')) {
            File::copy($localePath . '/' . $locale . '.json', lang_path($locale . '.json'));
        }

        File::deleteDirectory(storage_path('app/translations-master'));

        foreach (File::directories(lang_path('vendor/packages')) as $package) {
            if (!File::isDirectory(package_path(File::basename($package)))) {
                File::deleteDirectory($package);
            }
        }

        foreach (File::directories(lang_path('vendor/plugins')) as $plugin) {
            if (!File::isDirectory(plugin_path(File::basename($plugin)))) {
                File::deleteDirectory($plugin);
            }
        }

        $this->removeUnusedThemeTranslations();

        return [
            'error'   => false,
            'message' => 'Downloaded translation files!',
        ];
    }
}
