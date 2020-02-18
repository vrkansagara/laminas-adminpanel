<?php

namespace Blog;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'blog' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/blog',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
                // The following allows "/blog" to match on its own if no child routes match:
                'may_terminate' => true,
                'child_routes' => [
                    'posts' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/posts[/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\PostController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'about' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/about',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'about',
                            ],
                        ],
                    ],
                ]
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\PostController::class => Controller\Factory\PostControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\PostManager::class => Service\Factory\PostManagerFactory::class,
        ],
    ],
    // The following registers our custom view 
    // helper classes in view plugin manager.
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => InvokableFactory::class,
            View\Helper\Breadcrumbs::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class,
            'pageBreadcrumbs' => View\Helper\Breadcrumbs::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
//    'access_filter' => [
//        'controllers' => [
//            Controller\IndexController::class => [
//                ['actions' => ['index', 'about'], 'allow' => '*'],
//            ],
//            Controller\PostController::class => [
//                ['actions' => ['delete'], 'allow' => '#']
//            ]
//        ]
//    ],
];
