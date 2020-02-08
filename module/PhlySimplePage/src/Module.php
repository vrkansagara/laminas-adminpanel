<?php
/**
 * @link      https://github.com/weierophinney/PhlySimplePage for the canonical source repository
 * @copyright Copyright (c) 2012 Matthew Weier O'Phinney (http://mwop.net)
 * @license   https://github.com/weierophinney/PhlySimplePage/blog/master/LICENSE.md New BSD License
 */

namespace PhlySimplePage;

use Laminas\Mvc\Application;
use Laminas\Stdlib\ResponseInterface;

/**
 * Module class for use with ZF2
 */
class Module
{
    /**
     * Retrieve autoloader configuration for this module
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array('Laminas\Loader\StandardAutoloader' => array(
            'namespaces' => array(
                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
            ),
        ));
    }

    /**
     * Retrieve application configuration for this module
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Provide console usage messages for console endpoints
     *
     * @return array
     */
    public function getConsoleUsage()
    {
        return array(
            'phlysimplepage cache clear all' => 'Clear caches for all static pages',
            'phlysimplepage cache clear --page=' => 'Clear caches for a single static page',
            array('--page', 'Page name as matched via routing'),
        );
    }

    /**
     * Listen to the application bootstrap event
     *
     * Registers a post-routing event. Additionally, if the
     * "PhlySimplePage\PageCache" service is registered, it will pull the
     * "PhlySimplePage\PageCacheListener" service and attach it to the
     * event manager.
     *
     * @param  \Laminas\Mvc\MvcEvent $e
     */
    public function onBootstrap($e)
    {
        $app    = $e->getTarget();
        $events = $app->getEventManager();
        $events->attach('route', array($this, 'onRoutePost'), -100);

        $services = $app->getServiceManager();
        if ($services->has('PhlySimplePage\PageCache')) {
            $listener = $services->get('PhlySimplePage\PageCacheListener');
            $events->attach($listener);
        }
    }

    /**
     * Listen to the application route event
     *
     * Registers a post-dispatch listener on the Controller if the matched
     * Controller is the PageController from this module.
     *
     * @param  \Laminas\Mvc\MvcEvent $e
     */
    public function onRoutePost($e)
    {
        $matches = $e->getRouteMatch();
        if (!$matches) {
            return;
        }

        $controller = $matches->getParam('Controller');
        if ($controller != 'PhlySimplePage\Controller\Page') {
            return;
        }

        $app    = $e->getTarget();
        $events = $app->getEventManager();
        $shared = $events->getSharedManager();
        $shared->attach('PhlySimplePage\PageController', 'dispatch', array($this, 'onDispatchPost'), -1);
    }

    /**
     * Listen to the dispatch event from the PageController
     *
     * If the Controller result is a 404 status, triggers the application
     * dispatch.error event.
     *
     * @param  \Laminas\Mvc\MvcEvent $e
     */
    public function onDispatchPost($e)
    {
        $target = $e->getTarget();
        if (!$target instanceof PageController) {
            return;
        }

        $error = $e->getError();
        if ($error != Application::ERROR_CONTROLLER_INVALID) {
            return;
        }

        $app     = $e->getApplication();
        $results = $app->getEventManager()->trigger('dispatch.error', $app, $e);
        $return  = $results->last();

        if ($return instanceof ResponseInterface) {
            return $return;
        }

        if ($return) {
            $e->setResult($return);
        }
    }

    /**
     * Normalize a cache key
     *
     * @param  string $key
     * @return string
     */
    public static function normalizeCacheKey($key)
    {
        return str_replace(array('/', '\\', '.'), '_', $key);
    }
}
