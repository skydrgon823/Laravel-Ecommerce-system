<?php

use Botble\Widget\AbstractWidget;

class RecentPostsWidget extends AbstractWidget
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
    protected $widgetDirectory = 'recent-posts';

    /**
     * RecentPostsWidget constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name'           => __('Recent posts'),
            'description'    => __('Recent posts widget.'),
            'number_display' => 6,
        ]);
    }
}
