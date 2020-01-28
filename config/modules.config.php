<?php

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */

use Laminas\Stdlib\ArrayUtils;

$applicationModules = [
    'Application',
    'Album',
    'Blog',
    'User'
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
