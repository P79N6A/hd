<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Templates extends Model {

    const PAGE_SIZE = 50;

    /**
     * 类型常量
     */
    //静态文件
    const TPL_STATIC = 0;
    //布局模板
    const TPL_LAYOUT = 1;
    //自定义模板
    const TPL_CUSTOM = 2;
    //单页模板
    const TPL_PAGE = 10;
    //首页模板
    const TPL_INDEX = 11;
    //出错模板
    const TPL_ERROR = 12;
    //详情模板: 新闻, 相册, 视频, 视频集, 专题, 活动
    const TPL_DETAIL_NEWS = 101;
    const TPL_DETAIL_ALBUM = 102;
    const TPL_DETAIL_VIDEO = 103;
    const TPL_DETAIL_VIDEO_COLLECTION = 104;
    const TPL_DETAIL_SPECIAL = 105;
    const TPL_DETAIL_ACTIVITY = 106;
    //分类模板: 通用, 相册, 视频, 视频集, 专题, 活动
    const TPL_CATEGORY = 201;
    const TPL_CATEGORY_ALBUM = 202;
    const TPL_CATEGORY_VIDEO = 203;
    const TPL_CATEGORY_VIDEO_COLLECTION = 204;
    const TPL_CATEGORY_SPECIAL = 205;
    const TPL_CATEGORY_ACTIVITY = 206;
    //地区模板
    const TPL_REGION = 300;
    //地区分类模板: 通用, 相册, 视频, 视频集, 专题, 活动
    const TPL_REGION_CATEGORY = 301;
    const TPL_REGION_CATEGORY_ALBUM = 302;
    const TPL_REGION_CATEGORY_VIDEO = 303;
    const TPL_REGION_CATEGORY_VIDEO_COLLECTION = 304;
    const TPL_REGION_CATEGORY_SPECIAL = 305;
    const TPL_REGION_CATEGORY_ACTIVITY = 306;
    //ugc模板
    const TPL_UGC_VIDEO = 403;
    const TPL_UGC_SIGNAL = 407;

    //交互模板
    //登录
    const TPL_LOGIN = 1000;
    //注册
    const TPL_REGISTER = 1001;
    //重置密码
    const TPL_RESET = 1002;
    //关联已有账号
    const TPL_CONNECT = 1003;
    //社交账号连接注册
    const TPL_CONNECT_NEW = 1004;
    //用户中心 - 用户信息
    const TPL_MEMBER_INFO = 1100;
    //用户中心 - 用户信息编辑
    const TPL_MEMBER_EDIT = 1101;
    //用户中心 - 我要爆料
    const TPL_MEMBER_TIP_OFF = 1102;
    //用户中心 - 我的爆料列表
    const TPL_MEMBER_TIP_OFF_LIST = 1103;
    //用户中心 - 我的评论
    const TPL_MEMBER_COMMENTS = 1104;

    //口令，输入口令
    const TPL_SECRET_LOGIN = 2001;
    /**
     * 类型 map  ******* 新增类型常量的时候需要修改 *******
     * @var array
     */
    protected static $typeMaps = [
        self::TPL_STATIC => '静态文件',
        self::TPL_LAYOUT => '布局页',
        self::TPL_CUSTOM => '自定义页',
        self::TPL_PAGE => '单页',
        self::TPL_INDEX => '首页',
        self::TPL_ERROR => '出错页',
        self::TPL_DETAIL_NEWS => '新闻详情页',
        self::TPL_DETAIL_ALBUM => '相册详情页',
        self::TPL_DETAIL_VIDEO => '视频详情页',
        self::TPL_DETAIL_VIDEO_COLLECTION => '视频集详情页',
        self::TPL_DETAIL_SPECIAL => '专题详情页',
        self::TPL_DETAIL_ACTIVITY => '活动详情页',
        self::TPL_CATEGORY => '分类页',
        self::TPL_CATEGORY_ALBUM => '相册分类页',
        self::TPL_CATEGORY_VIDEO => '视频分类页',
        self::TPL_CATEGORY_VIDEO_COLLECTION => '视频集分类页',
        self::TPL_CATEGORY_SPECIAL => '专题分类页',
        self::TPL_CATEGORY_ACTIVITY => '活动分类页',
        self::TPL_REGION => '地区页',
        self::TPL_REGION_CATEGORY => '地区分类页',
        self::TPL_REGION_CATEGORY_ALBUM => '地区相册分类页',
        self::TPL_REGION_CATEGORY_VIDEO => '地区视频分类页',
        self::TPL_REGION_CATEGORY_VIDEO_COLLECTION => '地区视频集分类页',
        self::TPL_REGION_CATEGORY_SPECIAL => '地区专题分类页',
        self::TPL_REGION_CATEGORY_ACTIVITY => '地区活动分类页',
        self::TPL_UGC_VIDEO => 'UGC点播',
        self::TPL_UGC_SIGNAL => 'UGC直播',
    ];

    /**
     * 类型 交互模板  ******* 新增类型常量的时候需要修改 *******
     * @var array
     */
    protected static $interactionMaps = [
        self::TPL_STATIC => '静态文件',
        self::TPL_LAYOUT => '布局页',
        self::TPL_LOGIN => '登录',
        self::TPL_REGISTER => '注册',
        self::TPL_RESET => '重置密码',
        self::TPL_CONNECT => '社交账号连接注册',
        self::TPL_MEMBER_INFO => '用户中心 - 用户信息',
        self::TPL_MEMBER_EDIT => '用户中心 - 用户信息编辑',
        self::TPL_MEMBER_TIP_OFF => '用户中心 - 我要爆料',
        self::TPL_MEMBER_TIP_OFF_LIST => '用户中心 - 我的爆料列表',
        self::TPL_MEMBER_COMMENTS => '用户中心 - 我的评论',
        self::TPL_SECRET_LOGIN => '口令输入',
    ];

    /**
     * 媒资类型映射 ******* 新增类型常量的时候需要修改 *******
     *
     * @var array
     */
    protected static $mediaTypeMaps = [
        'news' => self::TPL_DETAIL_NEWS,
        'album' => self::TPL_DETAIL_ALBUM,
        'video' => self::TPL_DETAIL_VIDEO,
        'video_collection' => self::TPL_DETAIL_VIDEO_COLLECTION,
        'special' => self::TPL_DETAIL_SPECIAL,
        'activity' => self::TPL_DETAIL_ACTIVITY,
        'ugc_video' => self::TPL_UGC_VIDEO,
        'ugc_signal' => self::TPL_UGC_SIGNAL,
    ];

    /**
     * 类型区间 ******* 新增类型常量的时候需要修改 *******
     *
     * @var array
     */
    protected static $typeRanges = [
        'detail' => [self::TPL_DETAIL_NEWS, self::TPL_DETAIL_ACTIVITY],
        'category' => [self::TPL_CATEGORY, self::TPL_CATEGORY_ACTIVITY],
        'region' => [self::TPL_REGION, self::TPL_REGION],
        'region_category' => [self::TPL_REGION_CATEGORY, self::TPL_REGION_CATEGORY_ACTIVITY],
        'ugc' => [self::TPL_UGC_VIDEO, self::TPL_UGC_SIGNAL],
    ];

    /**
     * 类型必须绑定的参数 ******* 新增类型常量的时候需要修改 *******
     * index 小于 10 的选绑
     *
     * @var array
     */
    protected static $typeParams = [
        'custom' => ['region_id', 'category_id', 'data_id'],
        'detail' => ['data_id'],
        'category' => ['category_id'],
        'region' => ['region_id'],
        'region_category' => ['region_id', 'category_id'],
    ];

    public function getSource() {
        return 'templates';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'domain_id', 'author_id', 'type', 'name', 'path', 'content', 'url_rules', 'url_prefix_group', 'created_at', 'updated_at', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'domain_id', 'author_id', 'type', 'name', 'path', 'content', 'url_rules', 'url_prefix_group', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'domain_id', 'author_id', 'type', 'name', 'path', 'content', 'url_rules', 'url_prefix_group', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'domain_id' => Column::TYPE_INTEGER,
                'author_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'path' => Column::TYPE_VARCHAR,
                'content' => Column::TYPE_TEXT,
                'url_rules' => Column::TYPE_VARCHAR,
                'url_prefix_group' => Column::TYPE_VARCHAR,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'domain_id', 'author_id', 'type', 'created_at', 'updated_at', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'domain_id' => Column::BIND_PARAM_INT,
                'author_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'path' => Column::BIND_PARAM_STR,
                'content' => Column::BIND_PARAM_STR,
                'url_rules' => Column::BIND_PARAM_STR,
                'url_prefix_group' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'type' => '2',
                'name' => '',
                'path' => '',
                'url_rules' => '',
                'url_prefix_group' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * 获取媒资类型值
     *
     * @param $type
     * @return int
     */
    public static function getMediaTypeValue($type) {
        if (isset(self::$mediaTypeMaps[$type])) {
            return self::$mediaTypeMaps[$type];
        } else {
            return 0;
        }
    }

    /**
     * 获取类型值区间
     *
     * @return array
     */
    public static function getTypeRanges() {
        return self::$typeRanges;
    }

    public static function findInteraction($domain_id) {
        $query = self::query()
            ->andCondition('domain_id', $domain_id)
            ->andCondition('status', 1)
            ->columns(['id', 'type', 'name', 'path', 'url_rules', 'url_prefix_group'])
            ->orderBy('type ASC');
        $rs = $query
            ->andCondition('type', '>=', self::TPL_LOGIN)
            ->execute()
            ->toArray();
        $commons = [];
        foreach ($rs as $r) {
            $commons[$r['type']] = $r;
        }
        $error = $query
            ->andCondition('type', self::TPL_ERROR)
            ->first();
        if ($error) {
            $commons[$error['type']] = $error->toArray();
        }
        return $commons;
    }

    /**
     * 交互模板读取
     *
     * @param $domain_id
     * @return array
     */
    public static function interactionByType($domain_id, $type) {
        $commons = self::findInteraction($domain_id);
        $r = [];
        if (isset($commons[$type])) {
            $r = $commons[$type];
        }
        return $r;
    }

    public static function allNoneStatic() {
        return self::query()
            ->andCondition('type', '>', self::TPL_STATIC)
            ->andCondition('status', 1)
            ->execute();
    }

    public static function findNoneStatic($domain_id) {
        $rs = self::query()
            ->andCondition('domain_id', $domain_id)
            ->andCondition('status', 1)
            ->andCondition('type', '>=', self::TPL_PAGE)
            ->columns(['id', 'type', 'name', 'path', 'url_rules', 'url_prefix_group'])
            ->orderBy('type ASC')
            ->execute()
            ->toArray();
        $commons = [];
        $pages = [];
        $error = [];
        foreach ($rs as $r) {
            if ($r['type'] == self::TPL_ERROR) {
                $error = $r;
            } elseif ($r['type'] < self::TPL_DETAIL_NEWS) {
                $r['url_pattern'] = '';
                $pages[$r['url_rules']] = $r;
            } else {
                $r['url_pattern'] = str_replace(['{category_id}', '{data_id}', '{region_id}'], '(\d+)', $r['url_rules']);
                if ($r['url_prefix_group'] == '') {
                    $commons['main'][$r['type']] = $r;
                } else {
                    $commons['groups'][$r['url_prefix_group']][$r['type']] = $r;
                }
            }
        }
        return [$commons, $pages, $error];
    }

    /**
     * @param $domain_id
     * @return array
     */
    public static function tplNoneStatic($domain_id) {
        return self::findNoneStatic($domain_id);
    }

    public static function makeValidator($input) {
        $validator = Validator::make(
            $input, [
            'channel_id' => "required",
            'domain_id' => "required",
            'path' => "required",
        ], [
                'channel_id.required' => '频道ID必填',
                'domain_id.required' => '域名ID必填',
                'path.required' => '路径必填',
            ]
        );
        return $validator;
    }

    public static function findAll($domain_id) {
        if ($domain_id > 0) {
            return self::query()
                ->where('channel_id=' . Session::get('user')->channel_id . ' and domain_id = ' . $domain_id)
                ->orderBy('updated_at desc,id desc')
                ->paginate(self::PAGE_SIZE, 'Pagination');
        } else {
            return [];
        }

    }

    public static function checkUnique($domain_id, $path) {
        return self::query()
            ->andCondition('domain_id', $domain_id)
            ->andCondition('path', $path)
            ->first();
    }

    public static function listType() {
        return self::$typeMaps;
    }

    public static function listInterMaps() {
        return self::$interactionMaps;
    }


    /**
     * @param $domain_id
     * @param $url
     * @return null|array
     */
    public static function fetchWithFriendUrl($domain_id, $url) {
        $r = self::query()
            ->columns([
                'Templates.id', 'f.channel_id', 'f.domain_id', 'category_id', 'data_id', 'region_id', 'url',
                'url_rules', 'name', 'path', 'content'
            ])
            ->leftJoin('TemplateFriends', 'f.template_id = Templates.id', 'f')
            ->andWhere('Templates.status = 1 AND f.domain_id = :domain_id: AND f.url = :url:', ['domain_id' => $domain_id, 'url' => $url])
            ->first();
        if ($r) {
            $r = $r->toArray();
        } else {
            $r = null;
        }
        return $r;
    }

    /**
     * 通过类型获取模板
     *
     * @param $domain_id
     * @param $type
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function tplByType($domain_id, $type) {
        return self::query()
            ->andCondition('domain_id', $domain_id)
            ->andCondition('type', $type)
            ->first();
    }

    public static function getOne($id) {
        $tpl = self::findFirst($id);
        return $tpl;
    }
    public static function getOneByType($id, $type) {
    	$tpl = self::query()
            ->andCondition('id', $id)
            ->andCondition('type', $type)
            ->first();
    	return $tpl;
    }

}