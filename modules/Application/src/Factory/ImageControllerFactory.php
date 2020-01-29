<?php

namespace Application\Factory;

use Application\Controller\ImageController;
use Application\Service\ImageManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory for ImageController. Its purpose is to instantiate the
 * controller.
 */
class ImageControllerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        $imageManager = $container->get(ImageManager::class);

        // Instantiate the controller and inject dependencies
        return new ImageController($imageManager);
    }
}
