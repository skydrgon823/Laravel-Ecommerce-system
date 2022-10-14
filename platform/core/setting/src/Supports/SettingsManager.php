<?php

namespace Botble\Setting\Supports;

use Illuminate\Support\Manager;

class SettingsManager extends Manager
{
    /**
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return config('core.setting.general.driver');
    }

    /**
     * @return JsonSettingStore
     */
    public function createJsonDriver(): JsonSettingStore
    {
        return new JsonSettingStore(app('files'));
    }

    /**
     * @return DatabaseSettingStore
     */
    public function createDatabaseDriver(): DatabaseSettingStore
    {
        return new DatabaseSettingStore();
    }
}
