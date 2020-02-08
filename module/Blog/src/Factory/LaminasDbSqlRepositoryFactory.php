<?php
namespace Blog\Factory;

use Interop\Container\ContainerInterface;
use Blog\Model\Post;
use Blog\Model\LaminasDbSqlRepository;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Hydrator\Reflection as ReflectionHydrator;
use Laminas\ServiceManager\Factory\FactoryInterface;


class LaminasDbSqlRepositoryFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return LaminasDbSqlRepository
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LaminasDbSqlRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Post('', '')
        );    }
}