<?php

$router = new Phalcon\Mvc\Router\Annotations;

$router->addResource('User', '/user');

return $router;