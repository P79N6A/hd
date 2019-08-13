<?php
// 主域名
define('BASE_DOMAIN', 'hudong.cztv.com');

define("WEB_HOST", 'http://sso.cztv.com');
define('ACTIVE_EMAIL_KEY', 'e653b2aa0d2c152f24a9092f7cbc38d8');//激活邮箱
define('LOGIN_KEY', 'e653b2aa0d2c152f24a9092f7cbc38d8'); //登录
define('SIGNSYSLOGNG', 0);//0不记录日志,1记录
define('LETV_CHANNEL_ID', 1);//channel_id =1 letv.蓝TV
define('PTV_LOGIN', 'e653b2aa0d2c152f24a9092f7cbc38d8');//ptv 登录ptv用户信息cookie加密
define('HD_KEY', 'e653b2aa0d2c152f24a9092f7cbc38d8');//用户登录加密秘钥
define('WRITE_RULE', 0);//0先写redis再入mysql库，1先入mysql库再写redis
define('MAX_COMMENT_COUNT', 10000); //每页最大评论数
define('TRIEFILTERID', 'trie_filter_id');
define('CLIENTSENDMSG', 'e653b2aa0d2c152f24a9092f7cbc38d8'); //客户端发送手机短信接口：发送手机注册激活码

define('CZTV_VPC_OSS', 1); //是否使用VPC专用OSS

define('CZTV_API_SIGN_ST',0); //是否启用API签名调用


define('CZTV_PROXY_ST', 0);//是否启用代理：1启用，0不启用
define('CZTV_PROXY_IP', '192.168.127.38');
define('CZTV_PROXY_PORT', '3128');

// sina 中国蓝TV网站接入
define("SINA_AKEY", '1193006747');
define("SINA_SKEY", 'f6bb85f5bb91c5d2fdd946186a05bafd');
define("SINA_CALLBACK", WEB_HOST . '/oauth/sinacallback');
define("APP_SINA_CALLBACK", WEB_HOST . '/oauth/sinacallback');

//qq 中国蓝新闻客户端
define("QQ_AKEY", '1101148092');
define("QQ_SKEY", 'quI3Mkk8R5HJJ6Qn');
define("QQ_CALLBACK", WEB_HOST . '/oauth/qqcallback');

//qq 中国蓝TV客户端
define("QQ_TV_APP_AKEY", '1104558493');
define("QQ_TV_APP_SKEY", 'apu9bLmRyhyuRIvk');
define("QQ_TV_APP_CALLBACK", WEB_HOST . '/oauth/qqcallback');

//qq 中国蓝TV网站接入
define("QQ_TV_AKEY", '101215929');
define("QQ_TV_SKEY", '45dc179821d8fb714879941623043a59');
define("QQ_TV_CALLBACK", WEB_HOST . '/oauth/qqcallback');

//weixin 中国蓝TV网站接入
define("WEIXIN_AKEY", 'wxcd38cb5229dceef5');
define("WEIXIN_SKEY", 'd8404be167244fd9abd27a7e5d02ea5a');
define("WEIXIN_CALLBACK", WEB_HOST . '/oauth/weixincallback');

//error_reporting(E_ALL);
//ini_set('display_errors','on');

return [
    // 密钥
    'secret' => 'bc1326cd3b424532',
    // 调试模式
    'debug' => false,
    //数据库
    'db' => [
        'host' => 'rm-bp1434ipp166rfl8o.mysql.rds.aliyuncs.com',
        'username' => 'userid',
        'password' => '27151cacf1',
        'dbname' => 'ucenter',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],
    //互动数据库(评论, 视频收藏, 视频点赞...)
    'db_interactive' => [
        'host' => 'rm-bp13112vy6pqb7120.mysql.rds.aliyuncs.com',
        'username' => 'commer',
        'password' => 'de00fe711a',
        'dbname' => 'comment',
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
    'push_network' => [
        'url' => 'http://nhudong.cztv.com/',
        'app_id' => '36e41acb49bbdbfdb97aa756ec56a589',
        'app_secret' => '9f5de33155427c0aeafd22bc926b74f8',
    ],
    //为多站点服务
    'sites' => [
        'nhudong.cztv.com' => [
            'id' => 'networkapi',
            'paths' => ['controllers'],
            //出错挂载
            'error_handler' => [
                'controller' => 'error',
                'action' => 'index'
            ],
            'static_url' => '//' . BASE_DOMAIN . '/media/',
            'memprefix' => 'test911',
            'static_version' => 3,
            'network_config' => [
                'url' => 'http://nhudong.cztv.com/',
                'app_id' => '776d0a3681142ed9fa4af34c9fa757b3',
                'app_secret' => '533536ec9ac8665b17238c3453c5049d',
            ]
        ],
        'ahudong.cztv.com' => [
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
                'image' => 'https://hudong-cztv.oss-cn-hangzhou.aliyuncs.com/',
            ],
            'network_config' => [
                'url' => 'http://nhudong.cztv.com/',
                'app_id' => '776d0a3681142ed9fa4af34c9fa757b3',
                'app_secret' => '533536ec9ac8665b17238c3453c5049d',
            ]
        ],
        'ihudong.cztv.com' => [
            'id' => 'api',
            'paths' => ['controllers'],
            'open_cache' => false,
            //抽奖入围资格
            'lottery_qualify' => 1000,
            'memprefix' => 'test911',
            'cdn_url' => [
                'image' => 'http://hudong-cztv.oss-cn-hangzhou.aliyuncs.com/',
                'video' => 'http://video.xianghunet.com/'
            ]
        ],
        //api.my.cztv.com 评论
        'api.my.cztv.com' => [
            'id' => 'api.my.cztv.com',
            'paths' => ['controllers'],
            'cdn_url' => [
                'image' => 'http://hudong-cztv.oss-cn-hangzhou.aliyuncs.com/',
            ],
            'network_config' => [
                'url' => 'http://nhudong.cztv.com/',
                'app_id' => '776d0a3681142ed9fa4af34c9fa757b3',
                'app_secret' => '533536ec9ac8665b17238c3453c5049d',
            ]
        ],

        //sso.cztv.com 用户中心sso
        'sso.cztv.com' => [
            'id' => 'sso.cztv.com',
            'paths' => ['controllers'],
            'cdn_url' => [
                'image' => 'http://hudong-cztv.oss-cn-hangzhou.aliyuncs.com/',
            ],
            'network_config' => [
                'url' => 'http://nhudong.cztv.com/',
                'app_id' => '776d0a3681142ed9fa4af34c9fa757b3',
                'app_secret' => '533536ec9ac8665b17238c3453c5049d',
            ],
            'email_sendcloud' => [
                'email_api_user' => 'happycztv_zgltv_01',
                'email_api_key' => 'HRFSE8LIsClDXIci',
                'email_from' => 'cztv@mailserver.cztv.com',

            ]
        ],

        //api.sso.cztv.com 用户中心sso api
        'api.sso.cztv.com' => [
            'id' => 'sso.cztv.com',
            'paths' => ['controllers'],
            'cdn_url' => [
                'image' => 'http://hudong-cztv.oss-cn-hangzhou.aliyuncs.com/',
            ],
            'network_config' => [
                'url' => 'http://nhudong.cztv.com/',
                'app_id' => '776d0a3681142ed9fa4af34c9fa757b3',
                'app_secret' => '533536ec9ac8665b17238c3453c5049d',
            ],
            'email_sendcloud' => [
                'email_api_user' => 'happycztv_zgltv_01',
                'email_api_key' => 'HRFSE8LIsClDXIci',
                'email_from' => 'cztv@mailserver.cztv.com',

            ]
        ],

        //my.cztv.com 用户基本信息
        'my.cztv.com' => [
            'id' => 'my.cztv.com',
            'paths' => ['controllers'],
            'vpc_domain' => 'vpc100-oss-cn-hangzhou.aliyuncs.com',
            'cdn_url' => [
                'image' => 'http://hudong-cztv.oss-cn-hangzhou.aliyuncs.com/',
            ],
            'email_sendcloud' => [
                'email_api_user' => 'happycztv_test_Hl9YOv',
                'email_api_key' => 'Dub6ZWbzb55fttFc',
                'email_from' => 'zhq@3UUPilDyrzJ8z75A0JWryNHihHD5BP4F.sendcloud.org',
            ]
        ],

        //favorite.cztv.com 点赞喜欢
        'favorite.cztv.com' => [
            'id' => 'favorite.cztv.com',
            'paths' => ['controllers'],
            'cdn_url' => [
                'image' => 'http://hudong-cztv.oss-cn-hangzhou.aliyuncs.com/',
            ],
            'network_config' => [
                'url' => 'http://nhudong.cztv.com/',
                'app_id' => '776d0a3681142ed9fa4af34c9fa757b3',
                'app_secret' => '533536ec9ac8665b17238c3453c5049d',
            ],
            'email_sendcloud' => [
                'email_api_user' => 'happycztv_test_Hl9YOv',
                'email_api_key' => 'Dub6ZWbzb55fttFc',
                'email_from' => 'zhq@3UUPilDyrzJ8z75A0JWryNHihHD5BP4F.sendcloud.org',
            ]
        ],

        // API
        'api.yao.' . BASE_DOMAIN => [
            'id' => 'api',
            'paths' => ['controllers'],
            //抽奖入围资格
            'lottery_qualify' => 100,
            // 图片CDN访问地址
            'cdn_url' => [
                'image' => 'http://hudong-cztv.oss-cn-hangzhou.aliyuncs.com/',
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
                'smarty' => 'http://hudong-cztv.oss-cn-hangzhou.aliyuncs.com/',
                'image' => 'http://hudong-cztv.oss-cn-hangzhou.aliyuncs.com/',
            ],
            'is_default' => true,
        ],
    ],
    'components' => [
        // 阿里云oss
        'AliyunOss' => [
            'alias' => 'Oss',
            'init' => function () {
                $oss = new \GenialCloud\Storage\AliyunOSS(
                    'http://vpc100-oss-cn-hangzhou.aliyuncs.com/',
                    'Yb8rhC6UaTD1KMVV',
                    'MjA7yrdRyblSv9wLUjRjNnKHZ1ofxu'
                );
                $oss->setBucket('hudong-cztv');
                return $oss;
            }
        ],

        // 语言包
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
        //memcache
        'cache' => [
            'alias' => 'MemcacheIO',
            'init' => function () {
                $cache = new Memcached();
                $cache->addServer('b9c1d1ef681a45b9.m.cnhzaliqshpub001.ocs.aliyuncs.com', 11211);
                return $cache;
            },
            'shared' => true,
        ],
        //redis
        'redis' => [
            'alias' => 'RedisIO',
            'init' => function () {
                $redis = new Redis();
                $redis->connect('31772f959e264eb2.m.cnhza.kvstore.aliyuncs.com');
                $redis->auth('35f4f84bad664e9f:Xlwhdxm123');
                return $redis;

            },
            'shared' => true,
        ],
        // CDN刷新接口
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
                    'hostname' => '192.168.127.9',
                    'wt' => 'json',
                    'path' => 'solr/cztv_cloud_data'
                ];
                return new SolrClient($options);
            },
            'shared' => true,
        ],
        'solr.user' => [
            'init' => function () {
                $options = [
                    'hostname' => '192.168.127.9',
                    'wt' => 'json',
                    'path' => 'solr/cztv_cloud_user'
                ];
                return new SolrClient($options);
            },
            'shared' => true,
        ],
        'solr.comment' => [
            'init' => function () {
                $options = [
                    'hostname' => '192.168.127.9',
                    'wt' => 'json',
                    'path' => 'solr/cztv_cloud_comment'
                ];
                return new SolrClient($options);
            },
            'shared' => true,
        ],
    ],

    //评论配置
    '_source' => [
        1 => 'Web',
        2 => 'iPhone',
        3 => 'Android',
        4 => 'wPhone',
        5 => 'Pad',
        6 => 'Tv',
        7 => 'Pc',
        8 => 'Msite',
        9 => 'Weibo',
    ],

    '_source2cn' => [
        1 => '中国蓝TV（web）',
        2 => '中国蓝TV（iPhone）',
        3 => '中国蓝TV（Android）',
        4 => '中国蓝TV（wPhone）',
        5 => '中国蓝TV（Pad）',
        6 => '中国蓝TV（Tv）',
        7 => '中国蓝TV（Pc）',
        8 => '中国蓝TV（Msite）',
        9 => '新浪微博',
    ],

    '_sourceDefault' => 1,
    '_sourceFlagWeb' => 1,
    '_sourceFlagiPhone' => 2,
    '_sourceFlagAndroid' => 3,
    '_sourceFlagwPhone' => 4,
    '_sourceFlagPad' => 5,
    '_sourceFlagTv' => 6,
    '_sourceFlagPc' => 7,
    '_sourceFlagMsite' => 8,
    '_sourceFlagSinaWeibo' => 9,

    //新版评论类型
    '_allowedType' => [
        'video' => '',  #视频评论
        'news' => '',  #新闻评论
        'broadcast' => '',  #广播评论
        'chat' => '', //聊天室
    ],

    '_cmtType' => [
        'cmt' => 'cmt', //普通评论
        'img' => 'img', //截图评论
        'vote' => 'vote',//投票评论
    ],

    '_cmtTypeMath' => [
        'cmt' => 1, //普通评论
        'img' => 2, //截图评论
        'vote' => 4, //投票评论
    ],

    '_vcm_like_type' => [
        'comment' => 'cmt',
        'reply' => 'reply',
    ],
    //评论缓存配置
    '_pagesize' => [
        'list' => 300,
        'feed' => 60,
    ],

    '_authRuleOn' => 1,
    '_authRuleOff' => 0,
    '_user_liked' => 'liked', //投票时就可以显示结果
    '_user_not_liked' => 'not_liked', //投票时就可以显示结果

    '_cacheConf' => [
        'allowedType' => [
            'video' => 'video',//2015-11-11，之前被注释掉了
        ],
        'limit' => 300,
        'memCacheKeys' => [
            'list' => 'List::v2:%s:%s:%s',            //ex:list::video:(pid/xid):id
            'listnopic' => 'List::nopic:v2:%s:%s:%s',        //ex:list::video:(pid/xid):id
            'listpic' => 'List::pic:v2:%s:%s:%s',        //ex:list::video:(pid/xid):id
            'listvote' => 'List::vote:v2:%s:%s:%s',        //ex:list::vote:(pid/xid):id
            'listNoPicAndNoVote' => 'List::npv:v2:%s:%s:%s',        //ex:list::npv:(pid/xid):id
            'listnovote' => 'List::novote:v2:%s:%s:%s',        //ex:list::vote:(pid/xid):id
            'listall' => 'List::all:v2:%s:%s:%s',            //ex:list::video:(pid/xid):id
            'listtotal' => 'List::total:v3:new:%s:%s:%s',

            'single' => 'Comment::%s:%s',                //ex:Comment:video:commentId
            'feedtotal' => 'Feed::total:%s',
            'feed' => 'Feed::list:%s',                //ex:Feed::list:sortType
            'marktotal' => 'Mark::total:%s',
            'mark' => 'Mark::list:%s',                //ex:Feed::list:sortType
            'list_nopic_total' => 'List::totalnopic:%s:%s:%s',    //ex:List::total:nopic::video:(pid/xid):id
            'user_lastctime' => 'User::lastctime:%s',            //ex:User::lastctime:uid
            'user_lastrtime' => 'User::lastrtime:%s',            //ex:User::lastrtime:uid
            'replylist' => 'List::reply:%s:%s',            //ex:list::video:(pid/xid):id
            'singlereply' => 'Reply::%s:%s',                    //ex:singlereply:video:replyid
            'replyrecord' => 'Reply::record:%s:%s',            //ex:Reply::record:video:commentid
            'picCmtTop' => 'pic::cmt:top',
            'userinfo' => 'Comment::user:%s',
            'vote_detial' => 'Comment::vote:%s',
            'user_voted' => 'Comment::user:voted:%s:%s',
            'userAuthCommentFlag' => 'User::auth:cmt:flag:%s:%s:%s',
            'user_liked' => 'Vcm::user:liked:%s:%s:%s',
        ],
        'memCacheExpire' => [
            'list' => 3600 * 12,
            'list_notenough' => 3600 * 6,
            'single' => 3600 * 6,
            'feedtotal' => 3600,
            'feed' => 3600,
            'feed_notenough' => 1800,
            'marktotal' => 0,
            'mark' => 600,//3600 * 24,
            'mark_notenough' => 600,//3600 * 4,
            'list_nopic_total' => 3600 * 12,
            'user_lastctime' => 3600,
            'user_lastrtime' => 3600,
            'replylist' => 3600 * 24,
            'replylist_notenough' => 3600 * 6,
            'singlereply' => 3600 * 6,
            'replyrecord' => 3600 * 24,
            'picCmtTop' => 86400 * 10,
            'userinfo' => 3600 * 4,
            'vote_detial' => 3600 * 24 * 5,
            'user_voted' => 3600 * 2,
            'userAuthCommentFlag' => 3600 * 2,
            'user_liked' => 3600 * 2,
            'listtotal' => 3600 * 24 * 5,
        ],
    ],
];
