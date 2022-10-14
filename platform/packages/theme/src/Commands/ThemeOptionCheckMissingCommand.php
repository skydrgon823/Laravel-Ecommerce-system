<?php

namespace Botble\Theme\Commands;

use Botble\Setting\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Language;
use Theme;
use ThemeOption;

class ThemeOptionCheckMissingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:theme:options:check {--R|reverse}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check difference theme options between database and option definitions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $isReverse = $this->option('reverse');

        $theme = Theme::getThemeName();
        $fields = array_map(function ($name) use ($theme) {
            return 'theme-' . $theme . '-' . $name;
        }, array_keys(Arr::get(ThemeOption::getFields(), 'theme')));

        $existsOptionsQuery = Setting::query();
        $existsOptionsQuery->where('key', 'LIKE', 'theme-' . $theme . '-%');

        if (is_plugin_active('language')) {
            foreach (Language::getSupportedLanguagesKeys() as $language) {
                $existsOptionsQuery->where('key', 'NOT LIKE', 'theme-' . $theme . '-' . $language . '-%');
            }
        }

        $existsOptions = $existsOptionsQuery->pluck('key')->all();
        $missingKeys = $isReverse
            ? $this->missingKeys($existsOptions, $fields)
            : $this->missingKeys($fields, $existsOptions);

        if ($missingKeys->isEmpty()) {
            $this->info('No missing option found!');
            return 0;
        }

        $missingKeysCount = $missingKeys->count();
        $pluralKeyWord = Str::plural('key', $missingKeysCount);
        $this->line(
            $isReverse
                ? 'We found <info>' . $missingKeysCount . '</info> ' . $pluralKeyWord . ' are not exists in settings table (database).'
                : 'We found <info>' . $missingKeysCount . '</info> ' . $pluralKeyWord . ' are not defined in theme options.'
        );
        $this->table(['#', 'Key'], $missingKeys->toArray());

        return 0;
    }

    /**
     * @param array $items
     * @param array $origin
     * @return Collection
     */
    protected function missingKeys(array $items, array $origin): Collection
    {
        return collect($items)->filter(function ($item) use ($origin) {
            return !in_array($item, $origin);
        })->values()->map(function ($item, $key) {
            return [$key, $item];
        });
    }
}
