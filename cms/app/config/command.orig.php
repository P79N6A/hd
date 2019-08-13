<?php
$main = require APP_PATH . 'config/main.php';
return [
    'debug' => $main['debug'],
    //模型生成目录
    'model_path' => APP_PATH . 'models/',
    //控制器生成目录
    'controller_path' => APP_PATH . 'sites/backend/controllers/',
    //视图生成目录
    'view_path' => APP_PATH . 'sites/backend/views/',
    //自动加载
    'autoload' => [
        CORE_PATH . 'libraries/',
        CORE_PATH . 'commands/',
        BASE_PATH . 'tasks/',
        APP_PATH . 'models/',
        APP_PATH . 'libraries/',
    ],
    'db' => $main['db'],
    'components' => $main['components'],
    'push_network' => $main['push_network'],
];