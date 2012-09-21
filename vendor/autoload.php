<?php

spl_autoload_register(function($className){
    if (strpos($className, 'Strapi\\') === 0) {
        $lastNsPos = strripos($className, '\\');
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', '/', $namespace) . '/';
        $fileName .= str_replace('_', '/', $className) . '.php';
        require dirname(__FILE__) . '/strapi-common/lib/' . $fileName;
    }
});