<?php

declare(strict_types=1);

namespace BlogSqlite\Factory;

use BlogSqlite\Model\LaminasDbSqlCommand;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;

class LaminasDbSqlCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $configArray = $container->get('config');
        $dbAdapter = new Adapter($configArray['blog']['db']);
//        return new LaminasDbSqlCommand($container->get(AdapterInterface::class));
        return new LaminasDbSqlCommand($dbAdapter);
    }
}
