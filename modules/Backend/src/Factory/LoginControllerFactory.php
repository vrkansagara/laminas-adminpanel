<?php

namespace Backend\Factory;

use Backend\Controller\LoginController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class LoginControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // get service manager
        $serviceLocator = $container->get('ServiceManager');

        // get view helper manager
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        // get 'head script' plugin
        $footerScript = $viewHelperManager->get('inlineScript');

        return new LoginController($footerScript);
    }

}