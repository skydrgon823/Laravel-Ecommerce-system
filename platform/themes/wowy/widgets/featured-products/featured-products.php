<?php

use Botble\Widget\AbstractWidget;

class FeaturedProductsWidget extends AbstractWidget
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
    protected $widgetDirectory = 'featured-products';

    /**
     * FeaturedProducts constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name'           => __('FeaturedProducts'),
            'description'    => __('Widget display featured products'),
            'number_display' => 3,
        ]);
    }
}
