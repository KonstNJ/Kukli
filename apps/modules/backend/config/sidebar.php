<?php
use Phalcon\Config;
return new Config([
    'content_block' => [
        [
            'title' => 'content_title',
            'icon' => 'cil-line-weight',
            'items' => ['pages','news','products']
        ],
        [
            'title' => 'content_users_title',
            'icon' => 'cil-comment-square',
            'items' => ['album','offers','shops','blogs','reviews','community','reviews']
        ],
    ],
    'management_block' => [
        [
            'title' => 'management_platform_title',
            'icon' => 'cil-settings',
            'items' => ['category','heroes','producer','entity_type','brands','brands_universe','languages']
        ],
        [
            'title' => 'management_services',
            'icon' => 'cil-briefcase',
            'items' => ['paid_services'],
        ],
        [
            'title' => 'currency_title',
            'icon' => 'cil-dollar',
            'items' => ['currency','currency_course'],
        ],
        [
            'title' => 'management_advertising_title',
            'icon' => 'cil-line-style',
            'items' => ['advertising']
        ],
    ],
    'trading_block' => [
        [
            'title' => 'trading_platform_title',
            'icon' => 'cil-dollar',
            'items' => ['orders','payments'],
        ]
    ],
    'users_block' => [
        [
            'title' => 'users_settings_title',
            'icon' => 'cil-shield-alt',
            'items' => ['roles','status','interests']
        ],
        [
            'title' => 'users_title',
            'icon' => 'cil-people',
            'items' => ['users','guest']
        ]
    ]
]);