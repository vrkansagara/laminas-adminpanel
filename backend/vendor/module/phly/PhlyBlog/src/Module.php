<?php

declare(strict_types=1);

namespace PhlyBlog;

use Laminas\Console\Adapter\AdapterInterface as Console;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Laminas\View\Model;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\View;

class Module implements ConsoleUsageProviderInterface
{
    public static $config;

    public static function prepareCompilerView($view, $config, $services)
    {
        $renderer = $services->get('BlogRenderer');
        $view->addRenderingStrategy(function ($e) use ($renderer) {
            return $renderer;
        }, 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return ['factories' => [
            'blogrequest' => function ($services) {
                return new Request();
            },
            'blogresponse' => function ($services) {
                return new Response();
            },
            'blogrenderer' => function ($services) {
                $helpers = $services->get('ViewHelperManager');
                $resolver = $services->get('ViewResolver');

                $renderer = new PhpRenderer();
                $renderer->setHelperPluginManager($helpers);
                $renderer->setResolver($resolver);

                $config = $services->get('Config');
                if ($services->has('MvcEvent')) {
                    $event = $services->get('MvcEvent');
                    $model = $event->getViewModel();
                } else {
                    $model = new Model\ViewModel();
                }
                $layout = 'layout/layout';
                if (isset($config['view_manager']['layout'])) {
                    $layout = $config['view_manager']['layout'];
                }
                $model->setTemplate($layout);
                $helpers->get('view_model')->setRoot($model);

                return $renderer;
            },
        ]];
    }


    public function getControllerConfig()
    {
        return ['factories' => [
            'PhlyBlog\CompileController' => function ($controllers) {
                $services = $controllers->getServiceLocator();
                $config = $services->get('Config');
                $config = isset($config['blog']) ? $config['blog'] : [];

                $request = $services->get('BlogRequest');
                $response = $services->get('BlogResponse');
                $view = new View();
                $view->setRequest($request);
                $view->setResponse($response);

                $controller = new CompileController();
                $controller->setConfig($config);
                $controller->setConsole($services->get('Console'));
                $controller->setView($view);
                return $controller;
            },
        ]];
    }

    public function getConsoleBanner(Console $console)
    {
        return 'Phly Static Blog Generator';
    }

    public function getConsoleUsage(Console $console)
    {
        return [
            'blog compile [--all|-a] [--entries|-e] [--archive|-c] [--year|-y] [--month|-m] [--day|-d] [--tag|-t] [--author|-r]' => 'Compile blog',
            ['--all|-a', 'Execute all actions (default)'],
            ['--entries|-e', 'Compile entries'],
            ['--archive|-c', 'Compile paginated archive (and feed)'],
            ['--year|-y', 'Compile paginated entries by year'],
            ['--month|-m', 'Compile paginated entries by month'],
            ['--day|-d', 'Compile paginated entries by day'],
            ['--tag|-t', 'Compile paginated entries by tag (and feeds)'],
            ['--author|-r', 'Compile paginated entries by author (and feeds)'],
        ];
    }

    public function onBootstrap($e)
    {
        $app = $e->getApplication();
        $services = $app->getServiceManager();
        self::$config = $services->get('config');
    }
}
