<?php

declare(strict_types=1);

namespace JsValidation;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'vrkansagara-laminas-jsvalidation' => [
        /*
         * Default view used to render Javascript validation code
         *
         * Supported: 'jsvalidation::bootstrap', 'jsvalidation::bootstrap4'
         *  */
        'view' => 'jsvalidation::bootstrap',

        /*
         * Default JQuery selector find the form to be validated.
         * By default, the validations are applied to all forms.
         */
        'form_selector' => 'form',

        /*
         * If you change the focus on detect some error then active
         * this parameter to move the focus to the first error found.
         */
        'focus_on_error' => false,

        /*
         * Duration time for the animation when We are moving the focus
         * to the first error, http://api.jquery.com/animate/ for more information.
         */
        'duration_animate' => 1000,

        /*
         * Enable or disable Ajax validations of Database and custom rules.
         * By default Unique, ActiveURL, Exists and custom validations are validated via AJAX
         */
        'disable_remote_validation' => false,

        /*
         * Field name used in the remote validation Ajax request
         * You can change this value to avoid conflicts wth your field names
         */
        'remote_validation_field' => '_jsvalidation',
    ],
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
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],    // The following key allows to define custom styling for FlashMessenger view helper.
];
