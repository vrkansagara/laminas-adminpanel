<?php

declare(strict_types=1);

namespace PhlyContact\Service;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use PhlyContact\Controller\ContactController;

class ContactControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $form = $container->get('PhlyContactForm');
        $message = $container->get('PhlyContactMailMessage');
        $transport = $container->get('PhlyContactMailTransport');

        $controller = new ContactController();
        $controller->setContactForm($form);
        $controller->setMessage($message);
        $controller->setMailTransport($transport);

        return $controller;
    }
}
