<?php

$router = new Phalcon\Mvc\Router\Annotations;

$router->add('/yao/{id:\d+}', 'Yao::index')->setName('yao');
$router->add('/draw/{id:\d+}', 'Yao::draw')->setName('yao.draw');
$router->add('/lottery/search_winners/{id:\d+}', 'Lottery::searchWinners');


$router->addResource('KaiGeLottery', '/kaige');
$router->addResource('GetKaiGe', '/getkaige');

return $router;