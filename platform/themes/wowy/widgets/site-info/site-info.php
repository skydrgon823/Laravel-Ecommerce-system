<?php

use Botble\Widget\AbstractWidget;

class SiteInfoWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $frontendTemplate = 'frontend';

    /**
     * @var string
     */
    protected $backendTemplate = 'backend';

    /**
     * @var string
     */
    protected $widgetDirectory = 'site-info';

    /**
     * SiteInfo constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name'        => __('Site information'),
            'description' => __('Widget display site information'),
        ]);
    }
}
