<?php

declare(strict_types=1);

namespace BlogSqlite;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\ModuleManager;
use Laminas\Mvc\MvcEvent;

class Module implements ConfigProviderInterface
{
    // The "init" method is called on application start-up and
    // allows to register an event listener.
    public function init(ModuleManager $manager)
    {
        // Get event manager.
        $eventManager = $manager->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method.
//        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    // Event listener method.
    public function onDispatch(MvcEvent $event)
    {
        // Get controller to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        // Get fully qualified class name of the controller.
        $controllerClass = get_class($controller);
        // Get module name of the controller.
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
//         Switch layout only for controllers belonging to our module.
         if ($moduleNamespace == __NAMESPACE__) {
             $viewModel = $event->getViewModel();
             $viewModel->setTemplate('layout/layout');
         }
    }
}
