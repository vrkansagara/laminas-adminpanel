<?php

use Laminas\Stdlib\ArrayUtils;

$applicationModules = [
    'Application',
    'PhlyContact',
    'EdpSuperluminal',
    'PhlySimplePage',
    // 'Blog',
    'AlbumTableGatway',
//    'User',
//    'Event'
    'JsValidation',
];

$frameworkModule = [
    'Laminas\Mvc\Plugin\FilePrg',
    'Laminas\Mvc\Plugin\FlashMessenger',
    'Laminas\Mvc\Plugin\Identity',
    'Laminas\Mvc\Plugin\Prg',
    'Laminas\Router',
    'Laminas\Validator',
    'Laminas\I18n',
    'Laminas\Cache',
    'Laminas\Session',
    'Laminas\Db',
    'Laminas\Paginator',
    'Laminas\Mail',
    'Laminas\Form',
    'Laminas\Hydrator',
    'Laminas\InputFilter',
    'Laminas\Filter',
    'Laminas\Navigation',
    'Laminas\DeveloperTools',
    'DoctrineModule',
    'DoctrineORMModule'
];
return ArrayUtils::merge($frameworkModule, $applicationModules);
