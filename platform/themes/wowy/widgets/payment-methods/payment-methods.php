<?php

use Botble\Widget\AbstractWidget;

class PaymentMethodsWidget extends AbstractWidget
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
    protected $widgetDirectory = 'payment-methods';

    /**
     * SiteInfo constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name'        => __('Payments'),
            'description' => __('Widget display accepted payment methods.'),
            'image'       => null,
        ]);
    }
}
