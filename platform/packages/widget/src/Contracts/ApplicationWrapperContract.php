<?php

namespace Botble\Widget\Contracts;

interface ApplicationWrapperContract
{
    /**
     * Wrapper around app()->call().
     *
     * @param string|array $method
     * @param array $params
     * @return mixed
     */
    public function call($method, array $params = []);

    /**
     * Get the specified configuration value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function config(string $key, $default = null);

    /**
     * Wrapper around app()->getNamespace().
     *
     * @return string
     */
    public function getNamespace(): string;

    /**
     * Wrapper around app()->make().
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make(string $abstract, array $parameters = []);
}
