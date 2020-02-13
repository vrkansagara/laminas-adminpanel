<?php

declare(strict_types=1);

namespace PhlyContact;

use Laminas\Captcha\Dumb;
use Laminas\Mail\Transport\Sendmail;

return [
    'phly-contact' => [
        'captcha' => [
            'class' => Dumb::class,
        ],
        'form' => [
            'name' => 'contact',
        ],
        'mail_transport' => [
            'class' => Sendmail::class,
            'options' => [
            ]
        ],
        'message' => [
            'to' => [
                'to@laminas-adminpanel.local' => 'laminas-adminpanel',
            ],
            'sender' => [
                'address' => 'sender@laminas-adminpanel.local',
                'laminas-adminpanel' => 'laminas-adminpanel',
            ],
            'from' => [
                'from@laminas-adminpanel.local' => 'laminas-adminpanel',
            ],

        ],

    ],
    'controllers' => [
        'factories' => [
            Controller\ContactController::class => Service\ContactControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'contact' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/contact',
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'process' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/process',
                            'defaults' => [
                                'action' => 'process',
                            ],
                        ],
                    ],
                    'thank-you' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/thank-you',
                            'defaults' => [
                                'action' => 'thank-you',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'PhlyContactCaptcha' => 'PhlyContact\Service\ContactCaptchaFactory',
            'PhlyContactForm' => 'PhlyContact\Service\ContactFormFactory',
            'PhlyContactMailMessage' => 'PhlyContact\Service\ContactMailMessageFactory',
            'PhlyContactMailTransport' => 'PhlyContact\Service\ContactMailTransportFactory',
        ],
    ],
    'view_manager' => [
        'template_map' => [
//            'phly-contact/contact/index' => __DIR__ . '/../view/phly-contact/contact/index.phtml',
//            'phly-contact/contact/thank-you' => __DIR__ . '/../view/phly-contact/contact/thank-you.phtml',
        ],
        'template_path_stack' => [
            'phly-contact' => __DIR__ . '/../view',
        ],
    ],
];
