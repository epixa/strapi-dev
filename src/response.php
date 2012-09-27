<?php
/* @var $this Strapi\Runtime */

$parser = $this->load('content-negotiation');

return new Strapi\Response($parser);
