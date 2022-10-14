<?php

use Botble\Widget\AbstractWidget;

class BlogSearchWidget extends AbstractWidget
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
    protected $widgetDirectory = 'blog-search';

    /**
     * Widget constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name'        => 'Blog Search',
            'description' => __('Search blog posts'),
        ]);
    }
}
