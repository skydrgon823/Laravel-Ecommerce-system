<?php

return [
    'prefix'                               => 'ecommerce_',
    'display_big_money_in_million_billion' => env('DISPLAY_BIG_MONEY_IN_MILLION_BILLION', false),
    'bulk-import' => [
        'mime_types' => [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv',
            'application/csv',
            'text/plain',
        ],
        'mimes'      => [
            'xls',
            'xlsx',
            'csv',
        ],
    ],

    'enable_faq_in_product_details' => true,

    'digital_products' => [
        'allowed_mime_types' => env(
            'DIGITAL_PRODUCT_ALLOWED_MIME_TYPES',
            RvMedia::getConfig('allowed_mime_types')
        ),
    ],
];
