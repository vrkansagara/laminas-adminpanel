<?php

declare(strict_types=1);

namespace Application\Factory;

use Application\Controller\RegistrationController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory for RegistrationController. Its purpose is to instantiate the
 * Controller and inject dependencies into it.
 */
class RegistrationControllerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        $sessionContainer = $container->get('UserRegistration');

        // Instantiate the Controller and inject dependencies
        return new RegistrationController($sessionContainer);
    }
}
