<?php

namespace Album;

use Album\Controller\AlbumController;
use Laminas\Router\Http\Segment;

return [
    'db' => [
        'driver' => 'Pdo',
        'dsn' => sprintf('sqlite:%s/../data/album.db', __DIR__),
    ],
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'album' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/album[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => AlbumController::class,
                        'action' => 'index',
                        'page' => 1,
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\AlbumController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => ['index', 'info'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view",  actions to authorized users only.
                ['actions' => ['add', 'edit', 'view'], 'allow' => '@']
            ],
        ]
    ],
];
