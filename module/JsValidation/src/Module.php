<?php

namespace JsValidation;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    const VERSION = '1.0.0';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
