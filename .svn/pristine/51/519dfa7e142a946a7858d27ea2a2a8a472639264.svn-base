<?php

use GenialCloud\Auth\Signature;

/**
 * Class ApiBaseController
 */
class ApiBaseController extends BaseController {

    // 设置请求时间戳超时
    static $expireTime = 5;

    // 设置终端类型
    static $terminal = 'app';

    // 页数
    protected $page = 1;

    // 分页
    protected $per_page = 10;

    // 频道ID
    protected $channel_id;

    // 站点名
    protected $name;

    // 站点logo
    protected $logo;

    // 站点域名
    protected $domain;

    // 授权电台ID
    protected $stations;

    // 临时存储的用户介质，非实时有效
    protected $user;

    // 生成的真实前台访问地址
    protected $cdn_alias;

   
    protected $_Err;


    public function initialize() {
        parent::initialize();

        // 7月4日,饶佳修改
        if (CZTV_API_SIGN_ST == 1) {
            $this->checkSignature();
            $this->parsePage();
        }

        $this->_Err = array(
            'verify' => 'verify',                //验证码不正确
            'error' => 'error',                //参数不正确
            'type' => 'type',                //类型不正确
            'filter' => 'filter',                //存在过滤词
            'time' => 'time',                //30s内禁止发评论
            'forbidIP' => 'forbidIP',            //禁IP
            'forbidUser' => 'forbidUser',            //禁用户
            'more' => 'more',                //5分钟发评论超过30条
            'repeat' => 'repeat',                //重复发评论
            'short' => 'short',                //内容太短
            'ok' => 'ok',                    //发表成功
            'notlogged' => 'notlogged',            //没有登录
            'long' => 'long',                //内容太长
            'fail' => 'fail',                //发送失败
            'size' => 'size',                //图片过大
            'format' => 'format',                //图片格式有误
            //加精相关
            'marked' => 'marked',                //已加精
            'notmarked' => 'notmarked',            //未加精
            //投票相关
            'voted' => 'voted',
            'vote_expire' => 'vote_expire',
            //防csrf攻击
            'antiCsrf' => 'antiCsrf',
        );
    }

    public function initSmartData() {
        $host = $this->domain;
        $domain = Domains::tplByDomainAndType($host, 'frontend');
        if ($domain) {
            $this->cdn_alias = $domain->cdn_alias;
            $templates = Templates::tplNoneStatic($domain->id)[0];
            SmartyData::init($domain->channel_id, $domain->id);
            SmartyData::initTemplates($templates);
        }
    }

    /**
     * 获取wap页面的url
     * @param $data
     * @return string
     */
    protected function mediaUrl($data) {
        $path = 'http://' . ($this->cdn_alias ? $this->cdn_alias : $this->domain);
        $path .= SmartyData::url([
            'data_id' => $data['id'],
        ], Templates::getMediaTypeValue($data['type']));
        return $path;
    }

    /**
     * 验证登录令牌
     */
    protected function checkToken() {
        $token = Request::getQuery('token');
        $user = RedisIO::get(D::redisKey('token', $token));
        if (!$user) {
            $this->_json([], 403, 'token error');
        }
        $this->user = json_decode($user);
    }

    /**
     * 尝试着去获取token，如果没有，就算了
     */
    protected function tryToken() {
        $token = Request::getQuery('token');
        if ($token) {
            $user = RedisIO::get(D::redisKey('token', $token));
            $this->user = json_decode($user);
            if (isset($this->user->id)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 创建令牌
     * @param $id
     * @param $data
     * @return string
     */
    protected function createToken($id, $data) {
        $token = Hash::createToken($id);
        $key = D::redisKey('token', $token);
        RedisIO::set($key, json_encode($data));
        return $token;
    }

    /**
     * 签名校验
     */
    protected function checkSignature() {
        $input = Request::getQuery();
        if (!issets($input, ['app_id', 'signature', 'timestamp'])) {
            $this->_json([], 404, D::apiError(4001));
        }
        // 站点信息读取
        $data = Site::getByAppId($input['app_id']);
        if (empty($data)) {
            $this->_json([], 404, D::apiError(4002));
        }
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
        if (!(isset($input['debug']) && $input['debug'] == Config::get('secret'))) {
            // 签名匹配
            if (!Signature::MD5SimpleCheck(Request::getParams(), $data)) {
                $this->_json([], 404, D::apiError(4003));
            }
            // 校验时间戳有效期
//            if(time() - $input['timestamp'] > self::$expireTime) {
//                $this->_json([], 404, D::apiError(4004));
//            }
        }
    }
    
    /*
     * CDN预分发中心签名校验-用户向
     * md5(app_id.app_secret.time)
     */
    protected function checkSignatureCdnUser(){
        $input = Request::getParams();
        if (!issets($input, ['app_id', 'signature', 'timestamp'])) {
            $this->_json([], 404, D::apiError(4001));
        }
        $cdnuser = CdnUser::getCdnUserByAppid($input['app_id']);
        $check = md5($input['app_id'].$cdnuser['app_secret'].$input['timestamp']);
        if($check!=$input['signature']){
             $this->_json([], 404, D::apiError(4003));
        }
    }
    
    /*
     * CDN预分发中心签名校验-产商向
     * md5(username.password.time)
     */
    protected function checkSignatureCdnPro(){
        $input = Request::getParams();
        if (!issets($input, ['username', 'signature', 'timestamp'])) {
            $this->_json([], 404, D::apiError(4001));
        }
        $producer = CdnProducer::getProducerByName($input['username']);
        $check = md5($producer['username'].$producer['password'].$input['timestamp']);
        if($check!=$input['signature']){
             $this->_json([], 404, D::apiError(4003));
        }
    }
    
    
    /**
     * 解析分页参数
     */
    protected function parsePage() {
        $input = Request::getQuery();
        if (isset($input['page'])) {
            $this->page = (int)$input['page'];
        }
        if (isset($input['per_page'])) {
            $this->per_page = (int)$input['per_page'];
        }
    }

    /**
     * @param $albumId
     * @return array
     */
    protected function getAlbumImage($albumId) {
        $data = AlbumImage::apiFindByData($albumId);
        $return = [];
        if (!empty($data)) {
            foreach ($data as $v) {
                $return[] = (false===stripos($v['path'], "image.xianghunet.com"))?cdn_url('image',$v['path']):$v['path'];
            }
        }
        return $return;
    }

    protected function _json($data, $code = 200, $msg = "success") {
        header('Content-type: application/json');
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }

    /**
     * @desc 获取公共参数
     * @return array
     */
    protected function __getListReqParams() {
        global $config;
        $userModel = new User();
        //加载相关类
        $_source = $config['_source'];
        $source = $this->request->getQuery('source', 'string', $config['_sourceFlagWeb']);
        if (isset($_source[$source])) {
            $sourceType = $_source[$source];
        } else {
            $source = $config['_sourceFlagWeb'];
            $sourceType = $_source[$source];
        }
        $commonParams = array(
            'ssouid' => false,
            'loginUserInfo' => array(),
            'clientIp' => 0,
            'source' => $source
        );

        switch (true) {
            case 'iPhone' == $sourceType || 'Android' == $sourceType || 'wPhone' == $sourceType || 'Pad' == $sourceType || 'Tv' == $sourceType || 'Pc' == $sourceType:
                //raojia增加
                //增加对sso_tk参数的非法处理
                $sso_tk = $this->request->getQuery('sso_tk', "string", null);
                //如果没有sso_tk则不进行SSO登录验证
                $login_info = (!empty($sso_tk) && strlen($sso_tk) > 15) ? $userModel->isLoginNoCookie($sso_tk) : null;
                if (!empty($login_info) && is_array($login_info)) {
                    $commonParams['loginUserInfo'] = $login_info;
                    $commonParams['ssouid'] = intval($login_info['ssouid']);
                }
                $clientIp = $this->request->getQuery('clientIp', "string", "0");
                $clientIp = sprintf('%u', ip2long($clientIp));
                $commonParams['clientIp'] = empty($clientIp) ? 0 : $clientIp;
                $commonParams['ifFormatIcon'] = false;
                $commonParams['ifStripHtml'] = true;
                $commonParams['source'] = $source;
                break;
            default:
                $login_info = $userModel->isLogin();
                if (!empty($login_info) && is_array($login_info)) {
                    $commonParams['loginUserInfo'] = $login_info;
                    $commonParams['ssouid'] = intval($login_info['ssouid']);
                }
                $commonParams['clientIp'] = $this->getIntIp();
                $commonParams['source'] = $config['_sourceFlagWeb'];
        }
        //判断评论类型
        $type = trim($this->request->getQuery('type', null, 'video'));
        $_allowedType = $config['_allowedType'];
        if (!isset($_allowedType[$type])) {
            $this->output(array('result' => $this->_Err['type']), false, $this->request->getQuery('jscript', 0));
        }
        $commonParams['type'] = $type;
        $ifPic = trim($this->request->getQuery('ifpic', null, 'both'));
        $commonParams['ctype'] = 0;
        $_cmtType = $config['_cmtType'];
        $_cmtTypeMath = $config['_cmtTypeMath'];
        if ('n' !== $ifPic && 'y' !== $ifPic) {
            $commonParams['ifPic'] = '';
        } else {
            $commonParams['ifPic'] = ('n' === $ifPic) ? false : true;
        }
        if (true === $commonParams['ifPic']) {
            $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['img']];
        } else if (false === $commonParams['ifPic']) {
            $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['cmt']];
        } else if ('' == $commonParams['ifPic']) {
            $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['img']];
            $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['cmt']];
        }
        $ctype = $this->request->getQuery('ctype');
        if (!is_null($ctype)) {
            $commonParams['ctype'] = array();
            $ctype = array_values(array_filter(explode(",", trim($ctype))));
            if (in_array($_cmtType['cmt'], $ctype)) {
                $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['cmt']];
            }
            if (in_array($_cmtType['img'], $ctype)) {
                $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['img']];
            }
            if (in_array($_cmtType['vote'], $ctype)) {
                $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['vote']];
            }
            if (empty($commonParams['ctype'])) {
                $commonParams['ctype'] = $_cmtTypeMath[$_cmtType['cmt']] |= $_cmtTypeMath[$_cmtType['img']] |= $_cmtTypeMath[$_cmtType['vote']];
            }
        }
        return $commonParams;
    }

    /**
     * @desc 得到无符号整数表示的ip地址
     * @return string
     */
    public function getIntIp() {
        return sprintf('%u', ip2long($this->getRealIp()));
    }

    /**
     * @desc 获取客户端IP.
     * @return mixed
     */
    public function getRealIp() {
        $pattern = '/(\d{1,3}\.){3}\d{1,3}/';
        if (isset($_SERVER ["HTTP_X_FORWARDED_FOR"]) && preg_match_all($pattern, $_SERVER ['HTTP_X_FORWARDED_FOR'], $mat)) {
            foreach ($mat [0] as $ip) {
                //得到第一个非内网的IP地址
                if ((0 != strpos($ip, '192.168.')) && (0 != strpos($ip, '10.')) && (0 != strpos($ip, '172.16.'))) {
                    return $ip;
                }
            }
            return $ip;
        } else {
            if (isset($_SERVER ["HTTP_CLIENT_IP"]) && preg_match($pattern, $_SERVER["HTTP_CLIENT_IP"])) {
                return $_SERVER["HTTP_CLIENT_IP"];
            } else {
                return $_SERVER['REMOTE_ADDR'];
            }
        }
    }

    /**
     * @desc 输出
     * @param $result
     * @param bool $jsonp jsonp  为空则返回json格式
     * @param bool $jscript
     */
    protected function output($result, $jsonp = false, $jscript = false) {
        if (!$jscript) {
            if (empty($jsonp)) {
                $jsonp = $this->request->getQuery('jsonp', null, false);
                if (empty($jsonp)) {
                    $jsonp = false;
                } else {
                    $jsonp = $this->filter_it($jsonp);
                }
            }
            exit($jsonp ? $jsonp . "(" . json_encode($result) . ")" : json_encode($result));
        } else {
            $data = json_encode($result);
            $callback = $this->request->getQuery('callback', null, false);
            if (empty($callback)) {
                $callback = false;
            } else {
                $callback = $this->filter_it($callback);
            }

            exit("<script type='text/javascript'>"
                . "try{"
                . "document.domain='cztv.com';window.parent.{$callback}({$data})}"
                . "catch(e){}"
                . "</script>");
        }
    }

    /**
     * @desc 检测被回复内容是否存在
     * @param $commentid
     * @param string $type
     * @param bool $isComment
     * @param bool $isBreak
     * @return array|mixed
     */
    protected function __checkCommentExist($commentid, $type = 'video', $isComment = true, $isBreak = true) {
        $commentModelObj = new Comment();
        $comment_info = $commentModelObj->getCommentByIds(array($commentid), $type);
        $comment_info = (is_array($comment_info) && isset($comment_info[$commentid])) ? $comment_info[$commentid] : array();
        if (empty($comment_info) && false !== $isBreak) {
            $this->output(array('result' => $this->_Err['error']), false, $this->request->getQuery('jscript', null, 0));
        }
        return $comment_info;
    }

    /**
     * @desc Syslog.
     * @param $logName
     * @param $msg
     */
    protected function __syslog($logName, $msg) {
        $userModel = new User();
        $property['datetime'] = date("Y-m-d H:i:s");
        $property['clientip'] = $_SERVER['REMOTE_ADDR'];
        $property['message'] = $msg;
        $userModel->signSysLog('api', $logName, '', '', $property);
    }
    
}
