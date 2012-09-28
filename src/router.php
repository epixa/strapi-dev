<?php
/* @var $this \Strapi\Runtime */

// The default Strapi router requires pre-configured routes.
return new Strapi\Router($this->load('config')['routes']);
