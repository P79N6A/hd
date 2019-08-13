<?php

/**
 * @class:   SecretController口令验证h5界面
 * @author:  汤荷
 * @version: 1.0
 * @date:    2016/11/18
 */

use \GenialCloud\Exceptions\HttpException;

/**
 * @RoutePrefix("/secret")
 */
class SecretController extends InteractionBaseController   {
    const KEY = '4PkZBxWgBHH7sthBDHo8QYRXtZLmTcGj';

    protected $domain_id;

    public function initialize(){
        parent::initialize();
        $this->crossDomain();
        $this->domain_id = $this->domain->id;
    }

    public function createTemplate(){
        $allows = [
            'Request' => [
                'get', 'getPost',
            ],
            'Input' => [
                'init', 'fetch', 'checked', 'selected',
            ],
        ];

        return new CZTVSmarty($this->domain->id, $this->channel_id, $allows, 1, []);
    }
    /**
     * 校验签名
     * @throws
     */
    protected function checkSignatureTv() {
        error_reporting(1);
        $params = array();
        $params['timestamp'] = (string)$_GET['timestamp'];
        $params['key'] = substr(self::KEY, $params['timestamp'][strlen($params['timestamp']) - 1]);
        if(isset($_GET['terminal_id'])) {
            $params['terminal_id'] = $_GET['terminal_id'];
        }
        else {
            $params['terminal_id'] = "cztv_activity";
        }
        $params['id'] = $_GET['id'];
        $params['type'] = $_GET['type'];
        $params['is_login'] = $_GET['is_login'];
        $params['uid'] = $_GET['uid'];
        $params['gender'] = $_GET['gender'];
        $params['mobile'] = $_GET['mobile'];
        $params['email'] = urldecode($_GET['email']);
        $params['name'] = urldecode($_GET['name']);
        $params['client_id'] = $_GET['client_id'];
        ksort($params);
        $str = http_build_query($params);
        $params['signature'] = md5(base64_encode($str));
        $this->client_id = 'a:'.$params['client_id'];
        //签名失败
        if($params['signature'] != Request::get('signature')){
            echo 'signature validate failed.';
            exit();
        }
    }


    /**
     * 允许跨域请求
     */
    private function crossDomain(){
        $host = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';

        if(false !== strpos($host,'cztv')) {
            header('content-type:application:json;charset=utf8');
            header('Access-Control-Allow-Origin:' . $host);
            header('Access-Control-Allow-Methods:POST,GET,PUT');
            header('Access-Control-Allow-Headers:x-requested-with,content-type');
        }

//        header('content-type:application:json;charset=utf8');
//        header('Access-Control-Allow-Origin:*' );
//        header('Access-Control-Allow-Methods:POST,GET,PUT');
//        header('Access-Control-Allow-Headers:x-requested-with,content-type');

    }

    /** app端
     * @Get('/{id:[0-9]+}')
     */
    public function getAction($data_id) {
        $channelId = Request::getQuery("channel_id");
        $verify = 0;

        //app端get
        if ( Request::getQuery("signature") ){
            $this->checkSignatureTv();
            $verify = 1;
        }

        if ( 0 == $verify ){ //浏览器直接打开
            $secretUrl = Data::getRedisSecretInputUrl($data_id);
            if(empty($secretUrl)){
                $uri = Request::getURI();
                $host = Request::getHttpHost();
                $this->cookies->set("url",$host.$uri);
                $this->response->redirect("secret/login");
            }else{
                $this->cookies->set("url",$secretUrl);
                $this->response->redirect($secretUrl);
            }
        }else{
            $this->showTemplate($channelId,$data_id);
        }

    }

    /** h5端
     * @Post('/{id:[0-9]+}')
     */
    public function postAction($data_id) {
        $channelId = Request::getQuery("channel_id");
        $secretKey = Request::getPost("key");
        $secretUrl = Data::getRedisSecretInputUrl($data_id);
        if(empty($secretUrl)){
            $redirectUrl = "/secret/login?{$data_id}&channel_id={$channelId}";
        }else{
            $redirectUrl = $secretUrl;
        }
        if( $secretKey == md5("") || empty($secretKey) ){
            header("Content-type: text/html; charset=utf-8");
            echo "<script charset='utf-8'  > alert('口令错误，请重新输入');parent.location.href='{$redirectUrl}'; </script>";
            exit();
        }
        $secretInfo = Data::getSecretUrlAndStatus($channelId, $secretKey);
        if ( $secretInfo["url"] != "" && $secretInfo["status"]  == 1){
            $this->showTemplate($channelId,$data_id);
        }else{
            header("Content-type: text/html; charset=utf-8");
            echo "<script charset='utf-8' > alert('口令错误，请重新输入');parent.location.href='{$redirectUrl}'; </script>";
            exit();
        }

    }

    //显示模板
    public function showTemplate($channelId,$data_id){
        View::disable();

        $tpl = TemplateFriends::getTplByDataId($data_id);
        if(!$tpl){
            $this->response->setContent("template not found.");
            $this->response->send();
            exit();
        }
        $this->domain_id = $tpl["domain_id"];
        list($templates, $page_templates, $error_template) = Templates::tplNoneStatic($this->domain_id);
        $allows = [
            'SmartyData' => [
                'url',
                'getNews', 'getNewsContent', 'getAlbum', 'getVideo', 'getVideoCollection',
                'getLatest', 'getLatestWithSort',
                'getLatestByCode', 'getLatestByCodeWithSort',
                'getLatestInIds', 'getLatestWithSortInIds',
                'getLatestInCodes', 'getLatestWithSortInCodes',
                'getRegionLatest', 'getRegionLatestWithSort',
                'getRegionLatestByCode', 'getRegionLatestByCodeWithSort',
                'getCategory', 'getCategoryByCode', 'getCategoryBreadcrumbs',
                'getSubCategory','getSubCategorySort', 'getSubRegion', 'getRegion',
                'getBlockByCode',
                'getSpecial', 'getSpecialDataByCode', 'getSpecialDataById',
                'getDataList',
                'getMediaTypeValue',
                'getFeature',
                'listStations', 'getEpgs', 'getPrograms', 'getNewid'
            ],
        ];

        $smarty = new CZTVSmarty($this->domain_id, $channelId, $allows, 1, $templates);
        list($template, $data_id, $data_type, $category_id, $region_id) = $this->fetchTemplate($templates, $page_templates, "/".$data_id);
        $code = 500;
        $message = 'Internal Server Error';
        $output = '';
        $html = '';
        try {
            if(!$template) {
                throw new HttpException(404);
            }
            $smarty->assign(compact('data_id', 'data_type', 'category_id', 'region_id'));
            $smarty->display('mem:'.$template['path']);
        } catch(SmartyCompilerException $e) {
            $output = '模板出错: ' . $e->getMessage();
        } catch(HttpException $e) {
            $code = $e->getCode();
            $message = $output = $e->getMessage();
            if($error_template) {
                try {
                    $html = $smarty->display('mem:'.$error_template['path']);
                } catch (Exception $e) {
                    $output = '错误页模板: ' . $e->getMessage();
                }
            }
        } catch(Exception $e) {
            $output = 'Error: ' . $e->getMessage();
        }
        if($output) {
            header('HTTP/1.1 '.$code.' '.$message);
            echo $html;
            if(Config::get('debug', false)) {
                echo '<!-- ', $output, ' -->';
            }
        }
        if(Config::get('debug', false)) {
            debug_sql($this);
        }

    }

    public function fetchTemplate(&$templates, &$page_templates, $uri) {
        $template = null;
        //系统级匹配
        $data_id = 0;
        $data_type = '';
        $category_id = 0;
        $region_id = 0;
        //首页
        if($uri == '/') {
            if(!isset($page_templates['/'])) {
                abort(404);
            }
            $template = $page_templates['/'];
            $data_type = 'index';
            return [$template, $data_id, $data_type, $category_id, $region_id];
        }
        //page 类页面
        if(isset($page_templates[$uri])) {
            $template = $page_templates[$uri];
            $data_type = 'page';
            return [$template, $data_id, $data_type, $category_id, $region_id];
        }
        $groups = explode('/', ltrim($uri, '/'));
        $group = '';
        if(isset($groups[0]) && $groups[0]) {
            $group = $groups[0];
        }
        if(isset($templates['groups'][$group])) {
            $templates = $templates['groups'][$group];
        } else {
            $templates = isset($templates['main']) ? $templates['main'] : $templates;
        }
        $ranges = Templates::getTypeRanges();
        $details = range($ranges['detail'][0], $ranges['detail'][1]);
        //详情优先
        foreach($details as $detail) {
            if(isset($templates[$detail])) {
                $t = $templates[$detail];
                if(preg_match('!^'.$t['url_pattern'].'!', $uri, $matches)) {
                    $data_id = (int)$matches[1];
                    $template = $t;
                    $type_codes = [
                        Templates::TPL_DETAIL_NEWS => 'news',
                        Templates::TPL_DETAIL_ALBUM => 'album',
                        Templates::TPL_DETAIL_VIDEO => 'video',
                        Templates::TPL_DETAIL_VIDEO_COLLECTION => 'video_collection',
                        Templates::TPL_DETAIL_SPECIAL => 'special',
                    ];
                    $data_type = $type_codes[$detail];
                    return [$template, $data_id, $data_type, $category_id, $region_id];
                }
            }
        }
        //其次自定义
        $template = Templates::fetchWithFriendUrl($this->domain_id, $uri);
        if($template) {
            $category_id = $template['category_id'];
            $data_id = $template['data_id'];
            $region_id = $template['region_id'];
            return [$template, $data_id, $data_type, $category_id, $region_id];
        }
        //匹配通用分类
        $categories = range($ranges['category'][0], $ranges['category'][1]);
        foreach($categories as $category) {
            if(isset($templates[$category])) {
                $t = $templates[$category];
                if(preg_match('!^'.$t['url_pattern'].'!', $uri, $matches)) {
                    $category_id = (int)$matches[1];
                    $template = $t;
                    return [$template, $data_id, $data_type, $category_id, $region_id];
                }
            }
        }
        //地区
        $region = $ranges['region'][0];
        if(isset($templates[$region])) {
            $t = $templates[$region];
            if(preg_match('!^'.$t['url_pattern'].'!', $uri, $matches)) {
                $region_id = (int)$matches[1];
                $template = $t;
                return [$template, $data_id, $data_type, $category_id, $region_id];
            }
        }
        //匹配通用分类
        $categories = range($ranges['region_category'][0], $ranges['region_category'][1]);
        foreach($categories as $category) {
            if(isset($templates[$category])) {
                $t = $templates[$category];
                if(preg_match_all('!^'.$t['url_pattern'].'!', $uri, $matches)) {
                    $cpos = strpos($t['url_rules'], '{category_id}');
                    $rpos = strpos($t['url_rules'], '{region_id}');
                    if($cpos < $rpos) {
                        $cpos = 1;
                        $rpos = 2;
                    } else {
                        $cpos = 2;
                        $rpos = 1;
                    }
                    $category_id = (int)$matches[$cpos][0];
                    $region_id = (int)$matches[$rpos][0];
                    $template = $t;
                    return [$template, $data_id, $data_type, $category_id, $region_id];
                }
            }
        }
        return [$template, $data_id, $data_type, $category_id, $region_id];
    }

    public function errorAction() {
        echo 'Http error.';
    }

    /**s
     * 口令验证
     */
    public function loginAction(){
        $url = "http://".$this->cookies->get("url")->getValue();
        $this->runTemplate(Templates::TPL_SECRET_LOGIN,compact('url'));
    }

}