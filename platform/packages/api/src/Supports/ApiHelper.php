<?php

namespace Botble\Api\Supports;

use App\Models\User;
use Botble\Base\Models\BaseModel;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;

class ApiHelper
{
    /**
     * @return string
     */
    public function modelName(): string
    {
        return (string)$this->getConfig('model', User::class);
    }

    /**
     * @param string $modelName
     * @return $this
     */
    public function setModelName(string $modelName): self
    {
        config(['packages.api.api.provider.model' => $modelName]);

        return $this;
    }

    /**
     * @return string|null
     */
    public function guard(): ?string
    {
        return $this->getConfig('guard');
    }

    /**
     * @return string|null
     */
    public function passwordBroker(): ?string
    {
        return $this->getConfig('password_broker');
    }

    /**
     * @param string $key
     * @param $default
     * @return Repository|Application|mixed
     */
    public function getConfig(string $key, $default = null)
    {
        return config('packages.api.api.provider.' . $key, $default);
    }

    /**
     * @return Repository|Application|mixed
     */
    public function setConfig(array $config)
    {
        return config(['packages.api.api.provider' => $config]);
    }

    /**
     * @return BaseModel|mixed
     */
    public function newModel()
    {
        $model = $this->modelName();

        if (!$model || !class_exists($model)) {
            return new BaseModel();
        }

        return new $model();
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->newModel()->getTable();
    }

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        return setting('api_enabled', 1) == 1;
    }
}
