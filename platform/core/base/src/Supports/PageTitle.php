<?php

namespace Botble\Base\Supports;

class PageTitle
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param bool $full
     * @return string
     */
    public function getTitle(bool $full = true): ?string
    {
        $baseTitle = setting('admin_title', config('core.base.general.base_name'));

        if (empty($this->title)) {
            return $baseTitle;
        }

        if (!$full) {
            return $this->title;
        }

        return $this->title . ' | ' . $baseTitle;
    }
}
