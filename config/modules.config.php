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
];

$frameworkModule = [
    'Laminas\Router',
    'Laminas\Navigation',
    'Laminas\Form',
    'Laminas\Mail',
    'Laminas\Paginator',
    'Laminas\Db',
    'Laminas\Validator',
    'Laminas\Session',

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
    'DoctrineORMModule',

];
return ArrayUtils::merge($frameworkModule, $applicationModules);
