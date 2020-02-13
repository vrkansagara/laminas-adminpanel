<?php declare(strict_types=1);

namespace Application\Factory;

use Application\Controller\ContactusController;
use Application\Service\MailSender;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ContactusControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mailSender = $container->get(MailSender::class);

        // Instantiate the Controller and inject dependencies
        return new ContactusController($mailSender);
    }
}
