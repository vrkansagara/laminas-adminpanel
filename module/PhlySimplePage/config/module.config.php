<?php
return array(
    'router' => array(
        'routes' => array(
            'about' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/about',
                    'defaults' => array(
                        'Controller' => \PhlySimplePage\Controller\PageController::class,
                        'template'   => 'application/pages/about',
                        // optionally set a specific layout for this page
//                        'layout'     => 'layout/some-layout',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
//            'PhlySimplePage\Controller\Page' => \PhlySimplePage\Controller\PageController::class,
//            'PhlySimplePage\Controller\Page' => \PhlySimplePage\Controller\PageController::class
        ),
        'factories' => array(
            'PhlySimplePage\Controller\Cache' => 'PhlySimplePage\CacheControllerService',
            \PhlySimplePage\Controller\PageController::class => \PhlySimplePage\Factory\IndexControllerFactory::class,

        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'PhlySimplePage\PageCacheListener' => 'PhlySimplePage\PageCacheListenerService',
        ),
    ),
    'console' => array('router' => array('routes' => array(
        'phly-simple-page-clearall' => array('options' => array(
            'route' => 'phlysimplepage cache clear all',
            'defaults' => array(
                'Controller' => 'PhlySimplePage\Controller\Cache',
                'action'     => 'clearAll',
            ),
        )),
        'phly-simple-page-clearone' => array('options' => array(
            'route' => 'phlysimplepage cache clear --page=',
            'defaults' => array(
                'Controller' => 'PhlySimplePage\Controller\Cache',
                'action'     => 'clearOne',
            ),
        )),
    ))),
);
