<?php
/* @var $this Strapi\Runtime */
$environment = $this->load('environment');
return $this->load('configs' . DIRECTORY_SEPARATOR . $environment);
