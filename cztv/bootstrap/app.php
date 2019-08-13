<?php

use Phalcon\Loader;
use Phalcon\Dispatcher;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as Database;
use Phalcon\Mvc\Model;
use Phalcon\Config\Adapter\Php as PhalconConfig;
use Phalcon\Db\Profiler;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Crypt;

$config = new PhalconConfig(APP_PATH.'config/main.php');

require CORE_PATH.'libraries/helpers.php';
require CORE_PATH.'vendor/autoload.php';

try {

    $loader = new Loader();
    $loader->registerDirs(site_autoload())->register();

    Config::setCore(function () use ($config) {
        return $config;
    });

    // Create a DI
    $di = new FactoryDefault();
    
    // Setup a base URI so that all generated URIs include the "tutorial" folder
    $di->set('url', function () use ($config) {
        $url = new UrlProvider;
        $url->setBaseUri('/');
        return $url;
    });
    
    $di->set('router', function () {
        /**
         * @var \Phalcon\Mvc\Router $router
         */
        require site_route();
        return $router;
    });
    
    $di->set('profiler', function () {
        return new Profiler;
    }, true);
    
    if(isset(app_site()->error_handler)) {
        $di->set('dispatcher', function () {
            $eventsManager = new EventsManager();
            $eventsManager->attach("dispatch:beforeException", function ($event, $dispatcher, $exception) {
                $errorHandler = app_site()->error_handler->toArray();
                $errorHandler['params'] = [$exception];
                if($exception instanceof \GenialCloud\Exceptions\HttpException) {
                    $dispatcher->forward($errorHandler);
                    return false;
                }
                if($exception instanceof DispatchException) {
                    $dispatcher->forward($errorHandler);
                    return false;
                }
                // 代替控制器或者动作不存在时的路径
                switch($exception->getCode()) {
                    case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $dispatcher->forward($errorHandler);
                        return false;
                }
            });
            $dispatcher = new MvcDispatcher();
            // 将EventsManager绑定到调度器
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        }, true);
    }

    if(isset(app_site()->url_handler)) {
        $di->set('dispatcher', function () {
            $eventsManager = new EventsManager();
            $eventsManager->attach("dispatch:beforeDispatchLoop", function ($event, $dispatcher, $exception) {
                $urlHandler = app_site()->url_handler->toArray();
                $dispatcher->forward($urlHandler);
            });
            $dispatcher = new MvcDispatcher();
            // 将EventsManager绑定到调度器
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        }, true);
    }

    if(isset(app_site()->path_info)) {
        $di->set('dispatcher', function () {
            $eventsManager = new EventsManager();
            $eventsManager->attach("dispatch:beforeDispatchLoop", function ($event, $dispatcher, $exception) {
                $keyParams = array();
                $params    = $dispatcher->getParams();
                // 用奇数参数作key，用偶数作值
                foreach ($params as $number => $value) {
                    if ($number & 1) {
                        $keyParams[$params[$number - 1]] = $value;
                    }
                }
                // 重写参数
                $dispatcher->setParams($keyParams);
                return true;
            });
            $dispatcher = new MvcDispatcher();
            // 将EventsManager绑定到调度器
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        }, true);
    }

    //默认数据库连接
    $di->set('db', function () use ($di, $config) {
        $db = new Database($config->db->toArray());
        $eventsManager = new EventsManager;
        $profiler = $di->getShared('profiler');
        //db log event
        $eventsManager->attach('db', function ($event, $connection) use ($profiler) {
            //start record db execution
            if($event->getType() == 'beforeQuery') {
                $profiler->startProfile($connection->getSQLStatement());
            }
            //record db execution
            if($event->getType() == 'afterQuery') {
                $profiler->stopProfile();
            }
        });
        $db->setEventsManager($eventsManager);
        return $db;
    });

    //互动相关(评论,收藏等)数据库连接
    $di->set('db_interactive', function () use ($di, $config) {
        $db = new Database($config->db_interactive->toArray());
        $eventsManager = new EventsManager;
        $profiler = $di->getShared('profiler');
        //db log event
        $eventsManager->attach('db_interactive', function ($event, $connection) use ($profiler) {
            //start record db execution
            if($event->getType() == 'beforeQuery') {
                $profiler->startProfile($connection->getSQLStatement());
            }
            //record db execution
            if($event->getType() == 'afterQuery') {
                $profiler->stopProfile();
            }
        });
        $db->setEventsManager($eventsManager);
        return $db;
    });
    Model::setup(['notNullValidations' => false]);

    $di->set('session', function () {
        $session = new Session();
        if(!$session->isStarted()) $session->start();
        return $session;
    }, true);

    $di->set('cookies', function() {
        $cookies = new Phalcon\Http\Response\Cookies();
        $cookies->useEncryption(false);
        return $cookies;
    }, true);

    // Setup the view component
    $di->set('view', function () {
        $view = new View();
        $view->setViewsDir(site_view());
        return $view;
    });

    $di->set('crypt', function () use ($config) {
        $crypt = new Crypt();
        // 设置全局加密密钥
        $crypt->setKey($config->secret);
        return $crypt;
    }, true);

    // 注入队列服务Jason Fang
    $di->setShared('queue', function () {
        $queue = new Queue();
        return $queue;
    });

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

    // Handle the request
    $application = new Application($di);

    echo $application->handle()->getContent();

} catch(Exception $e) {
    if(isset($config->debug) && $config->debug) {
        echo '<pre>PhalconException: ', $e->getMessage(), PHP_EOL;
        echo $e->getTraceAsString();
        echo '</pre>';
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo '<h1>Internal Server Error</h1>';
    }
}