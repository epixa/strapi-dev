<?php

require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'FUnit' . DIRECTORY_SEPARATOR . 'FUnit.php';

spl_autoload_register(function($className){
    if (strpos($className, 'Strapi\\') === 0) {
        $lastNsPos = strripos($className, '\\');
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $fileName;
    }
});

spl_autoload_register(function($className){
    if (strpos($className, 'StrapiMock\\') === 0) {
        $lastNsPos = strripos($className, '\\');
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        require __DIR__ . DIRECTORY_SEPARATOR . $fileName;
    }
});