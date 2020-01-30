<?php

namespace Application;

use Application\Factory\ContactusControllerFactory;
use Application\Factory\ImageControllerFactory;
use Application\Factory\RegistrationControllerFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

$navigation = [
    'default' => [
        [
            'label' => 'Home',
            'route' => 'home'
        ],
        [
            'label' => 'Album',
            'route' => 'album',
            'pages' => [
                [
                    'label' => 'Add',
                    'route' => 'album',
                    'action' => 'add',
                ],
                [
                    'label' => 'Edit',
                    'route' => 'album',
                    'action' => 'edit',
                ],
                [
                    'label' => 'Delete',
                    'route' => 'album',
                    'action' => 'delete',
                ], [
                    'label' => 'Info',
                    'route' => 'album',
                    'action' => 'info',
                ],
            ],
        ],
        [
            'label' => 'Blog',
            'route' => 'blog',
            'pages' => [
                [
                    'label' => 'Detail',
                    'route' => 'blog',
                    'action' => 'detail',
                ],
                [
                    'label' => 'Add',
                    'route' => 'blog/add',
                    'action' => 'add',
                ],
                [
                    'label' => 'Edit',
                    'route' => 'blog/add',
                    'action' => 'edit',
                ],
                [
                    'label' => 'Delete',
                    'route' => 'blog/add',
                    'action' => 'delete',
                ],
            ],
        ],
        [
            'label' => 'Contact us',
            'route' => 'contact',
        ], [
            'label' => 'Image library',
            'route' => 'library',
            'pages' => [
                [
                    'label' => 'Image upload',
                    'route' => 'library/upload',
                    'action' => 'upload',
                ],

            ]
        ],
        [
            'label' => 'User',
            'route' => 'users',
            'pages' => [
                [
                    'label' => 'Add',
                    'route' => 'users',
                    'action' => 'add',
                ],
                [
                    'label' => 'Edit',
                    'route' => 'users',
                    'action' => 'edit',
                ],
                [
                    'label' => 'Delete',
                    'route' => 'users',
                    'action' => 'delete',
                ], [
                    'label' => 'Info',
                    'route' => 'users',
                    'action' => 'info',
                ],
            ],
        ],
        // [
        //     'label' => 'Blog with ORM',
        //     'route' => 'blogwithorm',
        //     'pages' => [
        //         [
        //             'label' => 'Detail',
        //             'route' => 'blogwithorm',
        //             'action' => 'detail',
        //         ],
        //         [
        //             'label' => 'Add',
        //             'route' => 'blogwithorm/add',
        //             'action' => 'add',
        //         ],
        //         [
        //             'label' => 'Edit',
        //             'route' => 'blogwithorm/add',
        //             'action' => 'edit',
        //         ],
        //         [
        //             'label' => 'Delete',
        //             'route' => 'blogwithorm/add',
        //             'action' => 'delete',
        //         ],
        //     ],
        // ],
    ],
];
return [
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'options' => [
            // The access filter can work in 'restrictive' (recommended) or 'permissive'
            // mode. In restrictive mode all controller actions must be explicitly listed
            // under the 'access_filter' config key, and access is denied to any not listed
            // action for not logged in users. In permissive mode, if an action is not listed
            // under the 'access_filter' key, access to it is permitted to anyone (even for
            // not logged in users. Restrictive mode is more secure and recommended to use.
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                // Allow anyone to visit "index" and "about" actions
                ['actions' => ['index', 'about'], 'allow' => '*'],
                // Allow authorized users to visit "settings" action
                ['actions' => ['settings'], 'allow' => '@']
            ],
        ]
    ],
    'navigation' => $navigation,
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'ping' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/ping',
                    'defaults' => [
                        'controller' => Controller\PingController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'application' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'contact' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/contact[/:action]',
                    'defaults' => [
                        'controller' => Controller\ContactusController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'library' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/library[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\ImageController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'registration' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/registration[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\RegistrationController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'session_containers' => [
        'UserRegistration'
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
            Controller\PingController::class => InvokableFactory::class,
            Controller\ContactusController::class => ContactusControllerFactory::class,
            Controller\ImageController::class => ImageControllerFactory::class,
            Controller\RegistrationController::class => RegistrationControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\MailSender::class => InvokableFactory::class,
            Service\ImageManager::class => InvokableFactory::class
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],    // The following key allows to define custom styling for FlashMessenger view helper.
    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format'      => '<div%s><ul><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        ]
    ],
];
