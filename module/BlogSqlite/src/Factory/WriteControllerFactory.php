<?php

declare(strict_types=1);

namespace BlogSqlite\Factory;

use BlogSqlite\Controller\WriteController;
use BlogSqlite\Form\PostForm;
use BlogSqlite\Model\PostCommandInterface;
use BlogSqlite\Model\PostRepositoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class WriteControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return WriteController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $formManager = $container->get('FormElementManager');
        return new WriteController(
            $container->get(PostCommandInterface::class),
            $formManager->get(PostForm::class),
            $container->get(PostRepositoryInterface::class),
        );
    }
}
