<?php

use Laminas\Navigation\ConfigProvider;

return [
    'service_manager' => (new ConfigProvider())->getDependencyConfig(),
];
