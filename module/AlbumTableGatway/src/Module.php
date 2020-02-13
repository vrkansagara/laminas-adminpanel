<?php declare(strict_types=1);

namespace AlbumTableGatway;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AlbumTable::class => function (ContainerInterface $container) {
                    $tableGateway = $container->get(Model\AlbumTableGateway::class);
                    return new Model\AlbumTable($tableGateway);
                },
                Model\AlbumTableGateway::class => function (ContainerInterface $container) {
//                    $dbAdapter = $container->get(AdapterInterface::class);
                    $configArray = $container->get('config');
                    $dbAdapter = new Adapter($configArray['album-db']['db']);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Album());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AlbumController::class => function (ContainerInterface $container) {
                    // get service manager
                    $serviceLocator = $container->get('ServiceManager');
                    // get view helper manager
                    $viewHelperManager = $serviceLocator->get('ViewHelperManager');
                    // get 'head script' plugin
                    $footerScript = $viewHelperManager->get('inlineScript');

                    return new Controller\AlbumController(
                        $container->get(Model\AlbumTable::class),
                        $footerScript
                    );
                },
            ],
        ];
    }
}
