<?php

define('PROJECT_ROOT', dirname(dirname(__FILE__)));

require PROJECT_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$strapi = new Strapi\Runtime(PROJECT_ROOT . DIRECTORY_SEPARATOR . 'src');
$strapi->run();