<?php

use GenialCloud\Auth\Signature;

class LetvApiBaseController extends BaseController {

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

    //过滤相关参数
    private $allow_http_value       = false;
    private $allow_htmlspecialchars = true;
    private $input;
    private $preg_patterns = array(
        // Fix &entity\n
        '!(&#0+[0-9]+)!' => '$1;',
        '/(&#*\w+)[\x00-\x20]+;/u' => '$1;>',
        '/(&#x*[0-9A-F]+);*/iu' => '$1;',
        //any attribute starting with "on" or xmlns
        '#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu' => '$1>',
        //javascript: and vbscript: protocols
        '#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu' => '$1=$2nojavascript...',
        '#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu' => '$1=$2novbscript...',
        '#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u' => '$1=$2nomozbinding...',
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        '#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i' => '$1>',
        '#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu' => '$1>',
        // namespaced elements
        '#</*\w+:\w[^>]*+>#i' => '',
        //unwanted tags
        '#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i' => ''
    );
    private $normal_patterns = array(
        '\'' => '&lsquo;',
        '"' => '&quot;',
        '&' => '&amp;',
        '<' => '&lt;',
        '>' => '&gt;'
    );

    protected $_Err;


    public function initialize() {
        parent::initialize();
        //$this->checkSignature();
        //$this->parsePage();
        $this->_Err       = array(
            'verify'       => 'verify',				//验证码不正确
            'error'        => 'error',				//参数不正确
            'type'         => 'type',				//类型不正确
            'filter'       => 'filter',				//存在过滤词
            'time'         => 'time',				//30s内禁止发评论
            'forbidIP'     => 'forbidIP',			//禁IP
            'forbidUser'   => 'forbidUser',			//禁用户
            'more'         => 'more',				//5分钟发评论超过30条
            'repeat'       => 'repeat',				//重复发评论
            'short'        => 'short',				//内容太短
            'ok'           => 'ok',					//发表成功
            'notlogged'    => 'notlogged',	 		//没有登录
            'long'         => 'long',				//内容太长
            'fail'         => 'fail',				//发送失败
            'size'         => 'size',				//图片过大
            'format'       => 'format',				//图片格式有误
            //加精相关
            'marked'       => 'marked',				//已加精
            'notmarked'    => 'notmarked',			//未加精
            //投票相关
            'voted'		   => 'voted',
            'vote_expire'  => 'vote_expire',
            //防csrf攻击
            'antiCsrf'     => 'antiCsrf',
        );
    }

    public function initSmartData(){
        $host = $this->domain;
        $domain = Domains::tplByDomainAndType($host, 'frontend');
        if($domain) {
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
        $path = 'http://'.($this->cdn_alias? $this->cdn_alias: $this->domain);
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
        if(!$user) {
            $this->_json([], 403, 'token error');
        }
        $this->user = json_decode($user);
    }

    /**
     * 尝试着去获取token，如果没有，就算了
     */
    protected function tryToken() {
        $token = Request::getQuery('token');
        if($token) {
            $user = RedisIO::get(D::redisKey('token', $token));
            $this->user = json_decode($user);
            if(isset($this->user->id)) {
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
        if(!issets($input, ['app_id', 'signature', 'timestamp'])) {
            $this->_json([], 404, D::apiError(4001));
        }
        // 站点信息读取
        $data = Site::getByAppId($input['app_id']);
        if(empty($data)) {
            $this->_json([], 404, D::apiError(4002));
        }
        foreach($data as $k => $v) {
            $this->$k = $v;
        }
        if(!(isset($input['debug']) && $input['debug'] == Config::get('secret'))) {
            // 签名匹配
            if(!Signature::MD5SimpleCheck(Request::getParams(), $data)) {
                $this->_json([], 404, D::apiError(4003));
            }
            // 校验时间戳有效期
//            if(time() - $input['timestamp'] > self::$expireTime) {
//                $this->_json([], 404, D::apiError(4004));
//            }
        }
    }

    /**
     * 解析分页参数
     */
    protected function parsePage() {
        $input = Request::getQuery();
        if(isset($input['page'])) {
            $this->page = (int)$input['page'];
        }
        if(isset($input['per_page'])) {
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
        if(!empty($data)) {
            foreach($data as $v) {
                $return[] = $v['path'];
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
    protected function __getListReqParams()
    {
        global $config;
        $userModel = new User();
        //加载相关类
        $_source = $config['_source'];
        $source  = $this->request->getQuery('source', null, $config['_sourceFlagWeb']);
        if (isset($_source[$source]))
        {
            $sourceType = $_source[$source];
        }
        else
        {
            $source       = $config['_sourceFlagWeb'];
            $sourceType   = $_source[$source];
        }
        $commonParams = array(
            'ssouid'          => false,
            'loginUserInfo'   => array(),
            'clientIp'        => 0,
            'source'          => $source
        );

        switch(true)
        {
            case 'iPhone'	== $sourceType || 'Android' == $sourceType || 'wPhone' == $sourceType || 'Pad' == $sourceType || 'Tv' == $sourceType || 'Pc' == $sourceType:
                $sso_tk        = $this->request->getQuery('sso_tk');
                $login_info    = $userModel->isLoginNoCookie($sso_tk);
                if (!empty($login_info) && is_array($login_info))
                {
                    $commonParams['loginUserInfo']   = $login_info;
                    $commonParams['ssouid']           = intval($login_info['ssouid']);
                }
                $clientIp                      = $this->request->getQuery('clientIp');
                $clientIp                      = sprintf('%u', ip2long($clientIp));
                $commonParams['clientIp']     = empty($clientIp) ? 0 : $clientIp;
                $commonParams['ifFormatIcon'] = false;
                $commonParams['ifStripHtml']  = true;
                $commonParams['source']   	    = $source;
                break;
            default:
                $login_info    = $userModel->isLogin();
                if (!empty($login_info) && is_array($login_info))
                {
                    $commonParams['loginUserInfo']   = $login_info;
                    $commonParams['ssouid']           = intval($login_info['ssouid']);
                }
                $commonParams['clientIp']       = $this->getIntIp();
                $commonParams['source']   	      = $config['_sourceFlagWeb'];
        }
        //判断评论类型
        $type         = trim($this->request->getQuery('type', null, 'video'));
        $_allowedType = $config['_allowedType'];
        if (!isset($_allowedType[$type]))
        {
            $this->output(array('result' => $this->_Err['type']), false, $this->request->getQuery('jscript', 0));
        }
        $commonParams['type'] = $type;
        $ifPic         = trim($this->request->getQuery('ifpic', null, 'both'));
        $commonParams['ctype'] = 0;
        $_cmtType     = $config['_cmtType'];
        $_cmtTypeMath = $config['_cmtTypeMath'];
        if ('n' !== $ifPic && 'y' !== $ifPic)
        {
            $commonParams['ifPic'] = '';
        }
        else
        {
            $commonParams['ifPic'] = ('n' === $ifPic) ? false : true;
        }
        if (true === $commonParams['ifPic'])
        {
            $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['img']];
        }
        else if(false === $commonParams['ifPic'])
        {
            $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['cmt']];
        }
        else if ('' == $commonParams['ifPic'])
        {
            $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['img']];
            $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['cmt']];
        }
        $ctype    = $this->request->getQuery('ctype');
        if (!is_null($ctype))
        {
            $commonParams['ctype'] = array();
            $ctype    = array_values(array_filter(explode(",", trim($ctype))));
            if (in_array($_cmtType['cmt'], $ctype))
            {
                $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['cmt']];
            }
            if (in_array($_cmtType['img'], $ctype))
            {
                $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['img']];
            }
            if (in_array($_cmtType['vote'], $ctype))
            {
                $commonParams['ctype'] |= $_cmtTypeMath[$_cmtType['vote']];
            }
            if (empty($commonParams['ctype']))
            {
                $commonParams['ctype'] = $_cmtTypeMath[$_cmtType['cmt']] |= $_cmtTypeMath[$_cmtType['img']] |= $_cmtTypeMath[$_cmtType['vote']];
            }
        }
        return $commonParams;
    }

    /**
     * @desc 得到无符号整数表示的ip地址
     * @return string
     */
    public function getIntIp()
    {
        return sprintf('%u', ip2long($this->getRealIp()));
    }

    /**
     * @desc 获取客户端IP.
     * @return mixed
     */
    public function getRealIp()
    {
        $pattern = '/(\d{1,3}\.){3}\d{1,3}/';
        if (isset($_SERVER ["HTTP_X_FORWARDED_FOR"]) && preg_match_all($pattern, $_SERVER ['HTTP_X_FORWARDED_FOR'], $mat))
        {
            foreach ($mat [0] as $ip)
            {
                //得到第一个非内网的IP地址
                if ((0 != strpos($ip, '192.168.')) && (0 != strpos ( $ip, '10.')) && (0 != strpos($ip, '172.16.')))
                {
                    return $ip;
                }
            }
            return $ip;
        }
        else
        {
            if (isset($_SERVER ["HTTP_CLIENT_IP"]) && preg_match($pattern, $_SERVER["HTTP_CLIENT_IP"]))
            {
                return $_SERVER["HTTP_CLIENT_IP"];
            }
            else
            {
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
    protected function output($result, $jsonp = false, $jscript = false)
    {
        if (!$jscript)
        {
            if (empty($jsonp))
            {
                $jsonp  = $this->request->getQuery('jsonp', null, false);
                if (empty($jsonp))
                {
                    $jsonp = false;
                }
                else
                {
                    $jsonp  = $this->filter_it($jsonp);
                }
            }
            exit($jsonp ? $jsonp."(".json_encode($result).")" : json_encode($result));
        }
        else
        {
            $data = json_encode($result);
            $callback = $this->request->getQuery('callback', null, false);
            if (empty($callback))
            {
                $callback = false;
            }
            else
            {
                $callback  = $this->filter_it($callback);
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
    protected function __checkCommentExist($commentid, $type = 'video', $isComment = true, $isBreak = true)
    {
        $commentModelObj = new Comment();
        $comment_info    = $commentModelObj->getCommentByIds(array($commentid), $type);
        $comment_info = (is_array($comment_info) && isset($comment_info[$commentid])) ? $comment_info[$commentid] : array();
        if (empty($comment_info) && false !== $isBreak)
        {
            $this->output(array('result' => $this->_Err['error']), false, $this->request->getQuery('jscript', null, 0));
        }
        return $comment_info;
    }

    /**
     * @desc Syslog.
     * @param $logName
     * @param $msg
     */
    protected function __syslog($logName, $msg)
    {
        $userModel               = new User();
        $property['datetime']   = date("Y-m-d H:i:s");
        $property['clientip']   = $_SERVER['REMOTE_ADDR'];
        $property['message']    = $msg;
        $userModel->signSysLog('api',$logName, '', '', $property);
    }

//=======================xss_filter begin=====================================
    /**
     * @param $in
     * @return string
     */
    public function filter_it($in)
    {
        $this->input = html_entity_decode($in, ENT_NOQUOTES, 'UTF-8');
        $this->normal_replace();
        $this->do_grep();
        return $this->input;
    }

    private function normal_replace()
    {
        $this->input = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $this->input);
        if ($this->allow_http_value == false)
        {
            $this->input = str_replace(array('&', '%', 'script', 'http', 'localhost'), array('', '', '', '', ''), $this->input);
        }
        else
        {
            $this->input = str_replace(array('&', '%', 'script', 'localhost'), array('', '', '', ''), $this->input);
        }
        if ($this->allow_htmlspecialchars == true)
        {
            foreach ($this->normal_patterns as $pattern => $replacement)
            {
                $this->input = str_replace($pattern,$replacement,$this->input);
            }
        }
    }

    private function do_grep()
    {
        foreach ($this->preg_patterns as $pattern => $replacement)
        {
            $this->input = preg_replace($pattern,$replacement,$this->input);
        }
    }

//=======================xss_filter end=====================================

}
