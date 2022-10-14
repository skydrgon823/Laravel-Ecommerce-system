<?php

return [
    [
        'name' => 'Ads',
        'flag' => 'ads.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'ads.create',
        'parent_flag' => 'ads.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'ads.edit',
        'parent_flag' => 'ads.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'ads.destroy',
        'parent_flag' => 'ads.index',
    ],
];
