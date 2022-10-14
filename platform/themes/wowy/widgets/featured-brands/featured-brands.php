<?php

use Botble\Widget\AbstractWidget;

class FeaturedBrandsWidget extends AbstractWidget
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
    protected $widgetDirectory = 'featured-brands';

    /**
     * FeaturedBrands constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name'           => __('FeaturedBrands'),
            'description'    => __('Widget display featured brands'),
            'number_display' => 10,
        ]);
    }
}
