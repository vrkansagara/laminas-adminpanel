<?php

namespace Blog\Factory;

use Interop\Container\ContainerInterface;
use Blog\Model\LaminasDbSqlCommand;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class LaminasDbSqlCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LaminasDbSqlCommand($container->get(AdapterInterface::class));
    }
}