<?php

namespace Application\Factory;

use Application\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * Controller and inject dependencies into it.
 */
class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        // Instantiate the Controller and inject dependencies
        return new IndexController($entityManager);
    }
}
