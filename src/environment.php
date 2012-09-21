<?php
/* @var $this Strapi\Runtime */
return $this->load('request')->env('APPLICATION_ENV') ?: 'development';
