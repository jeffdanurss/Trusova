<?php

spl_autoload_register(function ($class) {
    $dirs = [ 'Core', 'Controllers', 'Models' ];
    foreach ($dirs as $dir) {
        $file = __DIR__ . '/../' . $dir . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});