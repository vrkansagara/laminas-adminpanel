<?php
namespace JsValidation;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'validation' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/validation',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'detail' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/:id',
                            'defaults' => [
                                'action' => 'detail',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
                    ],
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'add',
                            ],
                        ],
                    ],
                ]
            ]
        ],
        'controllers' => [
            'factories' => [
                Controller\IndexController::class => Factory\IndexControllerFactory::class,

            ],
        ],
        'service_manager' => [
            'factories' => [
            ],
        ],
        'view_helpers' => []
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],    // The following key allows to define custom styling for FlashMessenger view helper.
];
