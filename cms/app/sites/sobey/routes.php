<?php

$router = new Phalcon\Mvc\Router\Annotations;

$router->addResource('Guanzhi', '/guanzhi');
$router->addResource('Xianghu', '/xianghu');
$router->addResource('Sobey', '/sobey');

return $router;