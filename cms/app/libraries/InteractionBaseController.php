<?php

/**
 * Created by PhpStorm.
 * User: xwsoul
 * Date: 15/12/18
 * Time: 上午11:39
 */
class InteractionBaseController extends BaseController
{

    protected $channel_id = 0;
    protected $domain_id = 0;

    /**
     * 默认配置下必须实现域名检测     *
     * @param string $host
     * @return bool
     */
    protected function defaultDomainCheck($host)
    {
        $this->host = $host;
        $this->domain = Domains::tplByDomainAndType($host, 'interaction');
        if (!$this->domain) {
            abort(403);
        }
        $this->domain_id = $this->domain->id;
        $this->channel_id = $this->domain->channel_id;
        return true;
    }

    public function initialize()
    {
        parent::initialize();
        $this->crossDomain();  //跨域支持
        //$this->lastModifiedCaChe();  //lastmodeified关闭浏览器端缓存
        View::disable();
    }

    /**
     * 用于post请求中特殊字符和html代码注入
     * @param $input
     * @return mixed
     */
    protected function xss_filter($input)
    {
        foreach ($input as $key => $value) {
            $input[$key] = htmlentities($input[$key], ENT_NOQUOTES);
            $input[$key] = $this->q($input[$key]);

        }
        return $input;
    }

    protected function q($v) {
        if(!get_magic_quotes_gpc()) {
            $v = addslashes($v);
        }
        return $v;
    }

    /**
     * 运行模板
     *
     * @param $type
     * @param $params
     */
    protected function runTemplate($type, $params)
    {

        $code = 500;
        $message = 'Internal Server Error';
        $output = '';
        $template = Templates::interactionByType($this->domain_id, $type);
        $smarty = $this->createTemplate();
        try {
            if (!$template) {
                abort(404);
            }
            $smarty->assign($params);
            $smarty->display('mem:' . $template['path']);
        } catch (SmartyCompilerException $e) {
            $output = '模板出错: ' . $e->getMessage();
        } catch (HttpException $e) {
            $code = $e->getCode();
            $message = $output = $e->getMessage();
            $error_template = Templates::interactionByType($this->domain_id, Templates::TPL_ERROR);
            if ($error_template) {
                try {
                    $output = $smarty->fetch('mem:' . $error_template['path']);
                } catch (Exception $e) {
                    $output = '错误页模板: ' . $e->getMessage();
                }
            }
        } catch (Exception $e) {
            $output = 'Error: ' . $e->getMessage();
        }
        if ($output) {
            header('HTTP/1.1 ' . $code . ' ' . $message);
            if (Config::get('debug', false)) {
                echo $output;
            }
        }
        if (Config::get('debug', false)) {
            debug_sql($this);
        }
    }

    /**
     * 允许跨域请求
     */
    private function crossDomain()
    {
        $host = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        $root_domain = "";
        if (!empty($host)) {
            $root_domain = $this->getUrlToDomain($host);
        }
        //跨域白名单
        $domains = array(
            "cztv.com",
            "cztvcloud.com",
            "xianghunet.com",
            "szttkk.com",
            "zjbtv.com",
            "sybtv.com",
            "txnews.com.cn",
            "qz123.com",
            "zjxcw.com",
            "yysee.net",
            "cncico.com"
        );
        if (in_array($root_domain, $domains)) {
            header('content-type:application:json;charset=utf8');
            header('Access-Control-Allow-Origin:' . $host);
            header('Access-Control-Allow-Methods:POST,GET,PUT');
            header("Access-Control-Allow-Credentials: true");
            header('Access-Control-Allow-Headers:x-requested-with,content-type');
        }

    }


    /**
     * 取得根域名
     * @param type $domain 域名
     * @return string 返回根域名
     */
    protected function getUrlToDomain($domain)
    {
        $re_domain = '';
        $domain_postfix_cn_array = array("com", "net", "org", "gov", "edu", "com.cn", "cn");
        $array_domain = explode(".", $domain);
        $array_num = count($array_domain) - 1;
		if(!$array_num){
            return "";
        }
        if ($array_domain[$array_num] == 'cn') {
            if (in_array($array_domain[$array_num - 1], $domain_postfix_cn_array)) {
                $re_domain = $array_domain[$array_num - 2] . "." . $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
            } else {
                $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
            }
        } else {
            $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        }
        return $re_domain;
    }

    /**
     * 设置IE缓存
     */
    protected function lastModifiedCaChe()
    {
        $controllerName = $this->dispatcher->getControllerName();
        $actionName = $this->dispatcher->getActionName();
        //缓存控制器白名单
        $controllerName_array = array('media','dept');
        if (F::checkLastModified() && in_array($controllerName,$controllerName_array)) {
            header('HTTP/1.1 304 Not Modified');
            exit;
        } else {
            $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            if(false === stripos($url, 'jQuery')) {
                $key = md5($url);
                F::setLastModified($key);
                //存入管理列表
                $action = $controllerName . "/" . $actionName;
                $this->_saveCache($action, $key);
            }
        }

    }


    /**
     * 缓存管理
     * @desc存入缓存管理列表
     */
    protected function _saveCache($action, $key) {
        $data_id = Request::getQuery('data_id', 'int');
        $this->channel_id = Request::getQuery('channel_id', 'int', 0);
        switch ($action) {
            //新闻详情
            case "dept/getdept":
                RedisIO::zAdd("z/dept/getdept:" . $this->channel_id, 0, $key);
                break;
            //新闻详情
            case "media/news":
                RedisIO::zAdd("z/media/news:" . $data_id . ":" . $this->channel_id, 0, $key);
                break;
            //视频详情
            case "media/video":
                RedisIO::zAdd("z/media/video:" . $data_id . ":" . $this->channel_id, 0, $key);
                break;
            //相册详情
            case "media/album":
                RedisIO::zAdd("z/media/album:" . $data_id . ":" . $this->channel_id, 0, $key);
                break;
            //专题详情
            case "media/special":
                RedisIO::zAdd("z/media/special:" . $data_id . ":" . $this->channel_id, 0, $key);
                break;
            //专题栏目接口列表
            case "media/latestInIds":
                $special_category_id = Request::getQuery('special_category_id', 'int');
                RedisIO::zAdd("z/media/latestInIds:" . $special_category_id . ":" . $this->channel_id, 0, $key);
                break;
            //栏目列表
            case "media/latest":
                $special_category_id = Request::getQuery('special_category_id', 'int');
                if($special_category_id){
                    RedisIO::zAdd("z/media/latest:special:" . $special_category_id . ":" . $this->channel_id, 0, $key);
                }
                else {
                    $categoryId = Request::getQuery('category_id', 'string');
                    RedisIO::zAdd("z/media/latest:" . $categoryId . ":" . $this->channel_id, 0, $key);
                }
                break;
            default:
                //RedisIO::zAdd($action . ":" . $this->channel_id, 0, $key);

        }
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
}