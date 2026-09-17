
<?php

return [
    'routes' => [
        'admin' => [
            'prefix' => env('ADMIN_PREFIX', 'admin'),
        ],
        'user_frontend' => [
            'prefix' => env('USER_PREFIX', ''),
        ],
        'dashboard' => [
            'prefix' => 'dashboard',
        ],
        'user' => [
            'prefix' => 'users',
        ],
        'order' => [
            'prefix' => 'orders',
        ],
        'deposit' => [
            'prefix' => 'deposits',
        ],
        'withdraw' => [
            'prefix' => 'withdraws',
        ],
        'transfer' => [
            'prefix' => 'transfers',
        ],
        'commission' => [
            'prefix' => 'commissions',
        ],
        'tickets' => [
            'prefix' => 'tickets',
        ],
        'news' => [
            'prefix' => 'news',
        ],
        'category' => [
            'prefix' => 'categories',
        ],
        'log_activities_adm' => [
            'prefix' => 'log-activities-adm',
        ],
        'huan_luyen_vien' => [
            'prefix' => 'huan-luyen-vien',
        ],
        'cau_lac_bo' => [
            'prefix' => 'cau-lac-bo',
        ],
        'chi_nhanh' => [
            'prefix' => 'chi-nhanh',
        ],
        'khoa_hoc' => [
            'prefix' => 'khoa-hoc',
        ],
        'bai_quyen' => [
            'prefix' => 'bai-quyen',
        ],
        'cap_dai' => [
            'prefix' => 'cap-dai',
        ],
    ],
];

