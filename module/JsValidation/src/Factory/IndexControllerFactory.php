<?php

declare(strict_types=1);

namespace JsValidation\Factory;

use Interop\Container\ContainerInterface;
use JsValidation\Controller\IndexController;
use JsValidation\Form\PostForm;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $formManager = $container->get('FormElementManager');
        return new IndexController(
            $formManager->get(PostForm::class)
        );
    }
}
