<?php

use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Validator\RemoteAddr;

return [
//    'db' => [
//        'adapters' => [
//            'Application\Db\WriteAdapter' => [
//                'driver' => 'Pdo',
//                'dsn'    => 'mysql:dbname=application;host=canonical.example.com;charset=utf8',
//            ],
//            'Application\Db\ReadOnlyAdapter' => [
//                'driver' => 'Pdo',
//                'dsn'    => 'mysql:dbname=application;host=replica.example.com;charset=utf8',
//            ],
//        ],
//    ],
    'router' => [
        'router_class' => Laminas\Mvc\I18n\Router\TranslatorAwareTreeRouteStack::class,
    ],
    'translator' => [
        'locale' => 'en_US',
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => getcwd() .  '/data/language',
                'pattern'  => '%s.mo',
            ],
        ],
    ],
    'doctrine' => [
        // migrations configuration
        'migrations_configuration' => [
            'orm_default' => [
                'directory' => 'data/migrations',
                'name' => 'Doctrine Database Migrations',
                'namespace' => 'Migrations',
                'table' => 'migrations',
            ],
        ],
    ],
    // Session configuration.
    'session_config' => [
        // Session cookie will expire in 1 hour.
        'cookie_lifetime' => 60 * 60 * 1,
        // Session data will be stored on server maximum for 30 days.
        'gc_maxlifetime' => 60 * 60 * 24 * 30,
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
];
