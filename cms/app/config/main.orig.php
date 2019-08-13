<?php


error_reporting(E_ALL);
ini_set('display_errors', 'on');
define('BASE_DOMAIN', 'yun.cztv.com');
return [
    'secret' => 'bc1326cd3b4b54d8',
    'debug' => true,
    'db' => [
        'host' => 'rm-bp1z774q3mvvcm922.mysql.rds.aliyuncs.com',
        'username' => 'ucenter',
        'password' => 'Ucenter123',
        'dbname' => 'usercenter',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],
    //自动加载
    'autoload' => [
        CORE_PATH . 'libraries/',
        APP_PATH . 'models/',
        APP_PATH . 'libraries/',
    ],
    'captcha' => [
        'foregroundColor' => '#FFAA00',
    ],
    'push_network' => [
        'url' => 'http://test-nyun.cztv.com/',
        'app_id' => '36e41acb49bbdbfdb97aa756ec56a589',
        'app_secret' => '9f5de33155427c0aeafd22bc926b74f8',
    ],
    //为多站点服务
    'sites' => [
        'test-ayun.cztv.com' => [
            'id' => 'backend',
            'paths' => ['controllers'],
            //出错挂载
            'error_handler' => [
                'controller' => 'error',
                'action' => 'index'
            ],
            'static_url' => '//' . BASE_DOMAIN . '/media/',
            'memprefix' => 'test911',
            'static_version' => 3,
            'cdn_url' => [
                'image' => 'http://test-xyun-cztv.oss-cn-hangzhou.aliyuncs.com/',
            ],
            'network_config' => [
                'url' => 'http://test-nyun.cztv.com/',
                'app_id' => '776d0a3681142ed9fa4af34c9fa757b3',
                'app_secret' => '533536ec9ac8665b17238c3453c5049d',
            ]
        ],
        'default' => [
            'id' => 'interaction',
            'paths' => ['controllers'],
            //出错挂载
            'error_handler' => [
                'controller' => 'index',
                'action' => 'error'
            ],
            'cdn_url' => [
                'smarty' => 'http://test-xyun-cztv.oss-cn-hangzhou.aliyuncs.com/',
                'image' => 'http://test-xyun-cztv.oss-cn-hangzhou.aliyuncs.com/',
            ],
            'is_default' => true,
        ],
    ],
    'components' => [
        'AliyunOss' => [
            'alias' => 'Oss',
            'init' => function () {
                $oss = new \GenialCloud\Storage\AliyunOSS(
                    'http://oss-cn-hangzhou.aliyuncs.com',
                    'YvC6zhMCUhj5tbk8',
                    'AXFobWwHnv4qqz2m83YxtcAK8MSk8x'
                );
                $oss->setBucket('test-xyun-cztv');
                return $oss;
            }
        ],
        'lang' => [
            'alias' => 'Lang',
            'init' => function () {
                $lang = new \GenialCloud\Lang\Language([
                    'defaultPath' => APP_PATH . 'lang',
                    'defaultLanguage' => 'zh-CN',
                ]);
                return $lang;
            },
            'shared' => true,
        ],
        'cache' => [
            'alias' => 'MemcacheIO',
            'init' => function () {
                $cache = new Memcached();
                $cache->addServer('04dd988ee46d4757.m.cnhzaliqshpub001.ocs.aliyuncs.com', 11211);
                return $cache;
            },
            'shared' => true,
        ],
        'redis' => [
            'alias' => 'RedisIO',
            'init' => function () {
                $redis = new Redis();
                $redis->connect('35f4f84bad664e9f.m.cnhza.kvstore.aliyuncs.com');
                $redis->auth('35f4f84bad664e9f:Xlanwang123');
                return $redis;

            },
            'shared' => true,
        ],
        'cdn.fast_web.yao' => [
            'init' => function () {
                $cdn = new \GenialCloud\Network\Services\FastWebCDN([
                    'key' => 'e52d4ed223fe84f45c76d0ad0fc41b',
                    'secret' => '79335ef2bb',
                ]);
                return $cdn;
            },
            'shared' => true,
        ],
        'solr.data' => [
            'init' => function () {
                $options = [
                    'hostname' => '192.168.126.6',
                    'wt' => 'json',
                    'path' => 'solr/cztv_cloud_data'
                ];
                return new SolrClient($options);
            },
            'shared' => true,
        ],
    ],
];
