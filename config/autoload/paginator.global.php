<?php
use Laminas\Paginator\ConfigProvider;

return [
    'service_manager' => (new ConfigProvider())->getDependencyConfig(),
];