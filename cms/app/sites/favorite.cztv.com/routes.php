<?php

$router = new Phalcon\Mvc\Router;

$router->add('/:controller', [
    'controller' => 1,
    'action' => 'index',
]);

$router->add('/:controller/:action', [
    'controller' => 1,
    'action' => 2,
]);

$router->add('/signin', 'Index::signIn');

return $router;