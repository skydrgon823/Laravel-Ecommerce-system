<?php

namespace Botble\Translation\Console;

use Botble\Translation\Manager;
use Botble\Translation\Models\Translation;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Symfony\Component\VarExporter\Exception\ExceptionInterface;

class RemoveUnusedTranslationsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:translations:remove-unused-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unused translations';

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * RemoveUnusedTranslationsCommand constructor.
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws ExceptionInterface
     */
    public function handle()
    {
        $this->info('Remove unused translations in resource/lang...');

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

        $this->manager->removeUnusedThemeTranslations();

        $this->info('Importing...');
        $this->manager->importTranslations();

        $groups = Translation::groupBy('group')->pluck('group');

        $counter = 0;
        foreach ($groups as $group) {
            $keys = Translation::where('group', $group)
                ->where('locale', 'en')
                ->pluck('key');

            $counter += Translation::where('locale', '!=', 'en')
                ->where('group', $group)
                ->whereNotIn('key', $keys)
                ->delete();
        }

        $this->manager->exportAllTranslations();

        $this->info('Exporting...');
        $this->info('Done! Deleted ' . $counter . ' items!');

        return 0;
    }
}
