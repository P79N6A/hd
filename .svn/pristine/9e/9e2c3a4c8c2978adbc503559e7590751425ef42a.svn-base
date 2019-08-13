<?php
use Phalcon\CLI\Console as ConsoleApp;
use Phalcon\Config\Adapter\Php as PhalconConfig;
use Phalcon\Db\Adapter\Pdo\Mysql as Database;
use Phalcon\Mvc\Model;
use Phalcon\Session\Adapter\Files as Session;

require CORE_PATH.'libraries/helpers.php';

$config = new PhalconConfig(APP_PATH.'config/command.php');

// 使用CLI工厂类作为默认的服务容器
$di = new Phalcon\DI\FactoryDefault\CLI;

/**
 * 注册类自动加载器
 */
$loader = new \Phalcon\Loader();
$loader->registerDirs($config->autoload->toArray());
$loader->register();

Config::setCore(function() use ($config) {
    return $config;
});

$di->setShared('config', function() use ($config) {
    return $config;
});

$di->set('db', function () use ($di, $config) {
    $db = new Database($config->db->toArray());
    return $db;
});
$di->set('db_interactive', function () use ($di, $config) {
    $db_interactive = new Database($config->db_interactive->toArray());
    return $db_interactive;
});
Model::setup(['notNullValidations' => false]);

if(isset($config->components)) {
    $components = $config->components;
    foreach($components as $name => $component) {
        $shared = false;
        if(isset($component->shared) && $component->shared) {
            $shared = true;
        }
        if(!is_callable($component->init)) {
            $component->init = $component->init->toArray();
        }
        $di->set($name, $component->init, $shared);
    }
}

$di->set('session', function () {
    $session = new Session();
    $session->start();
    return $session;
}, true);

// 注入队列服务Jason Fang
$di->setShared('queue', function () {
    $queue = new Queue();
    return $queue;
});

// 创建console应用
$console = new ConsoleApp();
$console->setDI($di);

/**
 * 处理console应用参数
 */
$arguments = [];
$arguments['params'] = [];
foreach($argv as $k => $arg) {
    if($k == 1) {
        $arguments['task'] = $arg;
    } elseif($k == 2) {
        $arguments['action'] = $arg;
    } elseif($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

// 定义全局的参数， 设定当前任务及动作
define('CURRENT_TASK', (isset($argv[1])? $argv[1]: null));
define('CURRENT_ACTION', (isset($argv[2])? $argv[2]: null));

try {
    // 处理参数
    $console->handle($arguments);
} catch(\Phalcon\Exception $e) {
    echo $e->getMessage(), PHP_EOL;
    exit(255);
}