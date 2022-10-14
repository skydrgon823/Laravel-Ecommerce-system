<?php

namespace Botble\Base\Supports;

use BaseHelper;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\PluginManagement\Services\PluginService;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Mimey\MimeTypes;
use RvMedia;
use Setting;

class BaseSeeder extends Seeder
{
    /**
     * @param string $folder
     * @param string|null $basePath
     * @return array
     */
    public function uploadFiles(string $folder, ?string $basePath = null): array
    {
        File::deleteDirectory(config('filesystems.disks.public.root') . '/' . $folder);
        MediaFile::where('url', 'LIKE', $folder . '/%')->forceDelete();
        MediaFolder::where('name', $folder)->forceDelete();

        $mimeType = new MimeTypes();

        $files = [];

        $folderPath = ($basePath ?: database_path('seeders/files')) . '/' . $folder;

        if (!File::isDirectory($folderPath)) {
            return [];
        }

        foreach (File::allFiles($folderPath) as $file) {
            $type = $mimeType->getMimeType(File::extension($file));
            $files[] = RvMedia::uploadFromPath($file, 0, $folder, $type);
        }

        return $files;
    }

    /**
     * @return array
     */
    public function activateAllPlugins(): array
    {
        try {
            $plugins = array_values(BaseHelper::scanFolder(plugin_path()));

            $pluginService = app(PluginService::class);

            foreach ($plugins as $plugin) {
                $pluginService->activate($plugin);
            }

            return $plugins;
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return array
     */
    public function prepareRun(): array
    {
        Setting::forgetAll();

        return $this->activateAllPlugins();
    }

    /**
     * @param int $from
     * @param int $to
     * @param array $exceptions
     * @return int
     */
    protected function random(int $from, int $to, array $exceptions = []): int
    {
        sort($exceptions); // lets us use break; in the foreach reliably
        $number = rand($from, $to - count($exceptions)); // or mt_rand()

        foreach ($exceptions as $exception) {
            if ($number >= $exception) {
                $number++; // make up for the gap
            } else { /*if ($number < $exception)*/
                break;
            }
        }

        return $number;
    }
}
