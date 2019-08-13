<?php

/*
 * @filename Static.php
 * @encoding UTF-8
 * @author king <347498228@qq.com >
 * @datetime 2015-9-6  9:23:17
 * @version 1.0
 */

class D {

    public static function authIgnore() {
        return ['index/index', 'personal_data/index', 'personal_data/modify'];
    }

    public static function redisKey($type, $key) {
        $config = [
            'smscode' => 'smscode',
            'token' => 'token',
            'speccomcfg' => 'speccomcfg',//主题评论配置信息
            'speccommitem' => 'speccommitem',//主题评论信息
            'actsignupextfields' => 'actsignupextfields', //报名表扩展字段名称
            'actsignupextdefvalue' => 'actsignupextdefvalue', //活动报名表扩展字段默认值
            'mobtoken' => 'mobtoken', //用于APP终端对应的手机号码
            'QKQuee' => 'QKQuee',
            'DgsVedioFile' => 'DgsVedioFile',
            'user_ids'=>'user_ids', //手机用户各种Ids
            'ltv_anchor' => 'anchor',   //蓝魅主播
            'ltv_anchor_stream'=>'ltv_anchor_stream', //主播流信息
            'ltv_wx_order'=>'ltv_wx_order', //Redis微信订单
            'ltv_apple_order'=>'ltv_apple_order', //Redis微信订单
            'queue_count'=>'queue_count', //ugc订单充值成功通知次数
            'summit_author'=>'summit_author',//G20峰会报名id
            'secret_key'=>'secret_key',//口令
            'secret_url'=>'secret_url',//口令url

        ];
        return implode(":", [$config[$type], $key]);
    }

    /**
     * @param $type
     * @param array $params
     * @return string
     */
    public static function memKey($type, array $params) {
        $config = [
            // API ------------------ start
            //  站点信息
            'SiteInfo' => 'SiteInfo',
            // 分类缓存
            'list_category'=>'list_category',          
            // 分类下栏目缓存
            'apiFindByCategory' => 'apiFindByCategory',
            // app菜单列表
            'apiGetMenu' => 'apiGetMenu',
            // stations 列表
            'apiGetStationsByType' => 'apiGetStationsByType',
            // 某个电台的直播流
            'apiGetEpgById' => 'apiGetEpgById',
            // 活动列表
            'apiGetActivity' => 'apiGetActivity',
            'getWorkById' => 'getWorkById',
            'getWorkList' => 'getWorkList',
            // 单个活动详情
            'apiGetActivityById' => 'apiGetActivityById',
            // 某个电台信息
            'apiGetStationsById' => 'apiGetStationsById',
            'apiGetStationsByCode' => 'apiGetStationsByCode',
            // 节目单
            'apiGetProgramById' => 'apiGetProgramById',
            'apiGetNowProgramById' => 'apiGetNowProgramById',
            'apiGetProgramByProgramId' => 'apiGetProgramByProgramId',
            'apiFindStationsProgramOrder' => 'apiFindStationsProgramOrder',
            'apiGetActivitySignupByParameter' => 'apiGetActivitySignupByParameter',
            'apiGetActivitySignupRankingListByParameter'=>'apiGetActivitySignupRankingListByParameter',
            // 媒资
            'apiGetDataById' => 'apiGetDataById',
            // 搜索的热词
            'apiGetHotwords' => 'apiGetHotwords',
            // 相册关联图片
            'apiFindByData' => 'apiFindByData',
            // 获取推荐位
            'apiGetFeatures' => 'apiGetFeatures',
            // 获取用户的收藏
            'apiGetFavorites' => 'apiGetFavorites',
            // 爆料列表
            'apiGetBaoliaoByUser' => 'apiGetBaoliaoByUser',

            // 模板 ------------------ start
            // 推荐位
            'tplFeatures' => 'tpl:fts',
            'tplStations' => 'tpl:stations',
            'tplStation' => 'tpl:station',
            'tplEpgs' => 'tpl:epgs',
            'tplPrograms' => 'tpl:progs',
            'tplDomainId'=> 'tpl:domain_id',

            //订阅
            'apiFindSetAll' => 'apiFindSetAll',
            'apiFindSubscriptionByUid' => 'apiFindSubscriptionByUid',
            'apiFindOneSubscription' => 'apiFindOneSubscription',
            'apiFindOneSetBySetId' => 'apiFindOneSetBySetId',
            'apiFindAllIsKeyword' => 'apiFindAllIsKeyword',
            //APPOriginId的用户信息获取
            'apiFindOneByOriginId'=>'apiFindOneByOriginId',
            //获取app版本信息
            'getAppBySku' => 'getAppBySku',
            'apiGetAppVersionData' => 'apiGetAppVersionData',
        ];
        ksort($params);
        return implode(":", [$config[$type], http_build_query($params)]);
    }

    public static function apiError($key) {
        $config = [
            4001 => Lang::_('Params Empty'),
            4002 => Lang::_('No Site Info'),
            4003 => Lang::_('Signature Check Error'),
            4004 => Lang::_('Time Expired'),
        ];
        return isset($config[$key]) ? $config[$key] : $key;
    }

    public static function avatar($img) {
        return !empty($img) ? cdn_url('image', $img) : '/assets/admin/layout/img/avatar.png';
    }

    public static function defaultImage($name) {
        $config = [
            'logo' => '/assets/admin/layout/cztv_img/logo.png',
            'cztv-qrcode' => '/assets/admin/layout/cztv_img/erweima.png',
            'hdcztvqrcode' => '/assets/admin/layout/cztv_img/hdcztvqrcode.png',
        ];
        return $config[$name];
    }

    public static function adminStatus($status) {
        $config = [
            0 => '禁用',
            1 => '正常',
            2 => '未激活'
        ];
        return $config[$status];
    }

    /**
     * 获取管理员标签
     * @param Admin $admin
     * @return string
     */
    public static function adminLabel($admin) {
        if ($admin->channel_id == 0) {
            return "管理员";
        } elseif ($admin->is_admin == 1) {
            return '频道管理员';
        }
        return "普通管理员";
    }

    /**
     * 初始化setting
     * @param string $key
     * @return mix
     */
    public static function initSetting($key) {
        $setting = [
            'is.login.message' => [
                'name' => '登录短信验证',
                'key' => 'is.login.message',
                'value' => '0',
                'channel_id' => '0'
            ]
        ];
        return isset($setting[$key]) ? $setting[$key] : false;
    }

    /**
     * 获取系统配置，如果不存在，自动创建
     * @param string $key
     * @return mix
     */
    public static function getSetting($key) {
        $data = Setting::getByKey($key);
        if ($data) {
            return $data->toArray()['value'];
        } else if ($setting = D::initSetting($key)) {
            $model = new Setting();
            $model->save($setting);
            return $setting['value'];
        }
        return -1;
    }

    /**
     * 渲染栏目分类
     * @param $tree
     * @param $father_id
     * @param string $ext 当前控制器数据
     * @return string
     */
    public static function childRender($tree, $father_id, $ext) {
        $html = '<ul class="sub-menu">';
        $child = $tree->getChild($father_id);
        if (!empty($child)) {
            foreach ($child as $id) {
                $name = $tree->getValue($id);
                $hasChild = $tree->getChild($id);
                $href = Url::get($ext['controller'] . "/" . $ext['action'], ['category_id' => $id]);
                if ($hasChild) {
                    $html .= "<li><a target='mainiframe' href='javascript:;' data-url='{$href}'><span class='title'>{$name}</span><span class='arrow'></span></a>";
                    $html .= D::childRender($tree, $id, $ext);
                    $html .= "</li>";
                } else {
                    $html .= "<li><a target='mainiframe' href='javascript:;' data-url='{$href}'>{$name}</a></li>";
                }
            }
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * 渲染私有分类
     * @param $tree
     * @param $father_id
     * @param string $ext 当前控制器数据
     * @return string
     */
    public static function privatechildRender($tree, $father_id, $ext) {
        $html = '<ul class="sub-menu">';
        if ($father_id == 0) {
            $html .= "<li><a target='mainiframe'  href='javascript:;' data-url='" . Url::get($ext['controller'] . "/" . $ext['action']) . "'>全部</a></li>";
        }
        $child = $tree->getChild($father_id);
        if (!empty($child)) {
            foreach ($child as $id) {
                $name = $tree->getValue($id);
                $hasChild = $tree->getChild($id);
                $href = Url::get($ext['controller'] . "/" . $ext['action'], ['private_category_id' => $id]);
                if ($hasChild) {
                    $html .= "<li><a href='javascript:;' data-url='{$href}'><span class='title'>{$name}</span><span class='arrow'></span></a>";
                    $html .= D::privatechildRender($tree, $id, $ext);
                    $html .= "</li>";
                } else {
                    $html .= "<li><a target='mainiframe' href='javascript:;' data-url='{$href}'>{$name}</a></li>";
                }
            }
        }
        $html .= '</ul>';
        return $html;
    }

}