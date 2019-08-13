<?php

$router = new Phalcon\Mvc\Router\Annotations;

$router->addResource('Curl', '/curl');
$router->addResource('Getui', '/getui');
$router->addResource('Mail', '/mail');
$router->addResource('Weixin', '/weixin');
$router->addResource('TxCloud', '/txcloud');

return $router;