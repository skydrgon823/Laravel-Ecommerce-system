<?php

use Botble\Widget\AbstractWidget;

class BlogCategoriesWidget extends AbstractWidget
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
    protected $widgetDirectory = 'blog-categories';

    /**
     * Widget constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name'           => 'Blog Categories',
            'description'    => __('Widget display blog categories'),
            'number_display' => 10,
        ]);
    }
}
