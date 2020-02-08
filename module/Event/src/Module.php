<?php


namespace Event;


use Laminas\EventManager\Event;
use Laminas\EventManager\EventManager;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Mvc\MvcEvent;

class Module implements ConfigProviderInterface
{

    const VERSION = '1.0.0';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function init()
    {
        $events = new EventManager();
        $events->attach('do', function ($e) {
            $event = $e->getName();
            $params = $e->getParams();
            printf(
                'Handled event "%s", with parameters %s',
                $event,
                json_encode($params)
            );
        });

        $params = ['foo' => 'bar', 'baz' => 'bat'];
        $events->trigger('do', null, $params);
    }


}