<?php

namespace PhlySimplePage\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\IndexController;
use PhlySimplePage\Controller\PageController;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * Controller and inject dependencies into it.
 */
class PageControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PageController();
    }
}