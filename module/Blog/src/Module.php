<?php

namespace Blog;

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
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
        // Register the event listener method.
        $sharedEventManager->attach(__NAMESPACE__, 'route', [$this, 'onRoute'], 100);
        // Register the event listener method.
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onError'], 100);
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_RENDER_ERROR, [$this, 'onError'], 100);
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

        // Switch layout only for controllers belonging to our module.
        // if ($moduleNamespace == __NAMESPACE__) {
        //     $viewModel = $event->getViewModel();
        //     $viewModel->setTemplate('layout/layout2');
        // }
    }

    // Event listener method.
    public function onRoute(MvcEvent $event)
    {
        return;
        if (php_sapi_name() == "cli") {
            // Do not execute HTTPS redirect in console mode.
            return;
        }

        // Get request URI
        $uri = $event->getRequest()->getUri();
        $scheme = $uri->getScheme();
        // If scheme is not HTTPS, redirect to the same URI, but with
        // HTTPS scheme.
        file_put_contents('/tmp/zf3.txt', print_r($scheme, 1), FILE_APPEND);
        if ($scheme != 'https') {
            $uri->setScheme('https');
            $response = $event->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $uri);
            $response->setStatusCode(301);
            $response->sendHeaders();
            return $response;
        }
    }

    // Event listener method.
    public function onError(MvcEvent $event)
    {
        // Get the exception information.
        $exception = $event->getParam('exception');
        if ($exception != null) {
            $exceptionName = $exception->getMessage();
            $file = $exception->getFile();
            $line = $exception->getLine();
            $stackTrace = $exception->getTraceAsString();
        }
        $errorMessage = $event->getError();
        $controllerName = $event->getController();

        // Prepare email message.
        $to = 'admin@yourdomain.com';
        $subject = 'Your Website Exception';

        $body = '';
        if (isset($_SERVER['REQUEST_URI'])) {
            $body .= "Request URI: " . $_SERVER['REQUEST_URI'] . "\n\n";
        }
        $body .= "Controller: $controllerName\n";
        $body .= "Error message: $errorMessage\n";
        if ($exception != null) {
            $body .= "Exception: $exceptionName\n";
            $body .= "File: $file\n";
            $body .= "Line: $line\n";
            $body .= "Stack trace:\n\n" . $stackTrace;
        }

        $body = str_replace("\n", "<br>", $body);

        // Send an email about the error.
        mail($to, $subject, $body);
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
