<?php

namespace Botble\Translation\Console;

use Botble\Translation\Manager;
use Illuminate\Console\Command;

class DownloadLocaleCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'cms:translations:download-locale {locale : The locale that you want to download}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download translation files from https://github.com/botble/translations';

    /**
     * @var Manager
     */
    protected $manager;

    /**
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
     */
    public function handle()
    {
        $this->info('Downloading...');

        $result = $this->manager->downloadRemoteLocale($this->argument('locale'));

        if ($result['error']) {
            $this->error($result['message']);

            return 1;
        }

        $this->info($result['message']);

        return 0;
    }
}
