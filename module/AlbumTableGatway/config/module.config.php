<?php

namespace AlbumTableGatway;

use Laminas\Router\Http\Segment;

return [
    'db' => [
        'driver' => 'Pdo',
        'dsn' => sprintf('sqlite:%s/../data/laminastutorial.db', realpath(__DIR__))
    ],
//    'controllers' => [
//        'factories' => [
//            Controller\AlbumController::class => InvokableFactory::class,
//        ],
//    ],

    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'album' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/album[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AlbumController::class,
                        'action'     => 'index',
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
];
