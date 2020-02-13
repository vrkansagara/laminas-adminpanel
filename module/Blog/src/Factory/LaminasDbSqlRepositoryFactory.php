<?php declare(strict_types=1);

namespace Blog\Factory;

use Blog\Model\LaminasDbSqlRepository;
use Blog\Model\Post;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
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
        $configArray = $container->get('config');
        $dbAdapter = new Adapter($configArray['blog']['db']);
//        return new LaminasDbSqlCommand($container->get(AdapterInterface::class));
        return new LaminasDbSqlRepository(
            $dbAdapter,
            new ReflectionHydrator(),
            new Post('', '')
        );
//        return new LaminasDbSqlRepository(
//            $container->get(AdapterInterface::class),
//            new ReflectionHydrator(),
//            new Post('', '')
//        );
    }
}
