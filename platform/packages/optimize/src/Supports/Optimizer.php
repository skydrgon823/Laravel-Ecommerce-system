<?php

namespace Botble\Optimize\Supports;

class Optimizer
{
    /**
     * @var bool
     */
    protected $isEnabled = true;

    public function __construct()
    {
        $this->isEnabled = !is_in_admin() &&
            setting('optimize_page_speed_enable', 0) &&
            !app()->runningInConsole();
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @return $this
     */
    public function enable(): self
    {
        $this->isEnabled = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function disable(): self
    {
        $this->isEnabled = false;

        return $this;
    }
}
