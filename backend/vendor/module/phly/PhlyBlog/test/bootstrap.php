<?php declare(strict_types=1);

include __DIR__ . '/../autoload_register.php';

set_include_path(implode(PATH_SEPARATOR, [
    '.',
    __DIR__ . '/../../PhlyCommon/src',
    get_include_path(),
]));

spl_autoload_register(function ($class) {
    $file = str_replace(['\\', '_'], DIRECTORY_SEPARATOR, $class) . '.php';
    if (false === ($realpath = stream_resolve_include_path($file))) {
        return false;
    }
    include_once $realpath;
});
