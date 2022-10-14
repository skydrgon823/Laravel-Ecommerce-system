<?php

return [
    'schema_supported' => [
        'Botble\Page\Models\Page',
        'Botble\Blog\Models\Post',
    ],
    'use_language_v2' => env('FAQ_USE_LANGUAGE_VERSION_2', false),
];
