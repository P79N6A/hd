<?php

use Phalcon\Mvc\Controller;
use GenialCloud\Exceptions\DatabaseTransactionException;

class BaseController extends Controller {

    /**
     * 主机地址
     * @var string
     */
    protected $host;

    /**
     * 域名信息
     * @var mixed
     */
    protected $domain;

    public function beforeExecuteRoute($dispatcher) {
        $host = $_SERVER['HTTP_HOST'];
        $site_config = app_site();
        if (isset($site_config->is_default) && $site_config->is_default && !$this->defaultDomainCheck($host)) {
            throw new \GenialCloud\Exceptions\GenialCloudException('Loading default site config without domain check.');
        }
        $t = $this;
        Request::setCore(function () use ($t) {
            return $t->request;
        });
        DB::setCore(function () use ($t) {
            return $t->db;
        });
        Session::setCore(function () use ($t) {
            return $t->session;
        });
        Cookie::setCore(function () use ($t) {
            $t->cookies->useEncryption(false);
            return $t->cookies;
        });
        Url::setCore(function () use ($t) {
            return $t->url;
        });
        View::setCore(function () use ($t) {
            return $t->view;
        });
        Crypt::setCore(function () use ($t) {
            return $t->crypt;
        });
        $components = Config::get('components', []);
        foreach ($components as $key => $component) {
            if (isset($component->alias)) {
                $alias = $component->alias;
                $alias::setCore(function () use ($t, $key) {
                    return $t->$key;
                });
            }
        }
    }

    public function initialize() {

    }

    public function throwDbE($msg, $code = 0) {
        throw new DatabaseTransactionException($msg, $code);
    }

    /**
     * 默认配置下必须实现域名检测
     *
     * @param string $host
     * @return bool
     */
    protected function defaultDomainCheck($host) {
        $this->host = $host;
        return false;
    }

    /**
     * Jsonp 输出
     *
     * @param array $rs
     */
    protected function jsonp(array $rs) {
        $resp = json_encode($rs);
        if ($callback = Request::get('callback')) {
            echo htmlspecialchars($callback) . "({$resp});";
        } else {
            echo $resp;
        }
        exit;
    }


    /**
     * 20160704饶佳修改
     * 参数提取BUG
     * @desc 获取pathinfo格式的参数
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getVar($key, $default = null) {
        $path = $this->request->getURI();
        $path = chop($path, '/');

        $path = explode('?', $path);
        $path = explode('/', $path[0]);

        array_shift($path);
        array_shift($path);
        array_shift($path);

        $count = count($path);
        $newpath = array();
        for ($i = 0; $i < $count; $i++) {
            $newpath[$path[$i]] = $path[$i + 1];
            $i++;
        }
        if (!isset($newpath[$key]) && $default !== null) {
            $newpath[$key] = $default;
        }
        return $newpath[$key];
    }


    /**
     * 获取安全字符串
     * @desc str_filter.
     * @return string 过滤后的字符串
     */
    protected function __str_filter($str) {
        // 判断magic_quotes_gpc是否打开
        if (!get_magic_quotes_gpc()) {
            $str = addslashes($str); // 进行过滤
        }
        //$str = str_replace("_", "\\_", $str); // 把 '_'过滤掉
        //$str = str_replace("%", "\\%", $str); // 把' % '过滤掉
        $str = nl2br($str); // 回车转换
        $str = strip_tags($str);
        $str = htmlspecialchars(trim($str)); // html标记转换

        return $str;
    }


    //=======================xss_filter begin=====================================

    //过滤相关参数
    private $allow_http_value = false;
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


    
    /**
     * @param $in
     * @return string
     */
    public function filter_it($in) {
        $this->input = html_entity_decode($in, ENT_NOQUOTES, 'UTF-8');
        $this->normal_replace();
        $this->do_grep();
        return $this->input;
    }

    private function normal_replace() {
        $this->input = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $this->input);
        if ($this->allow_http_value == false) {
            $this->input = str_replace(array('&', '%', 'script', 'http', 'localhost'), array('', '', '', '', ''), $this->input);
        } else {
            $this->input = str_replace(array('&', '%', 'script', 'localhost'), array('', '', '', ''), $this->input);
        }
        if ($this->allow_htmlspecialchars == true) {
            foreach ($this->normal_patterns as $pattern => $replacement) {
                $this->input = str_replace($pattern, $replacement, $this->input);
            }
        }
    }

    private function do_grep() {
        foreach ($this->preg_patterns as $pattern => $replacement) {
            $this->input = preg_replace($pattern, $replacement, $this->input);
        }
    }

//=======================xss_filter end=====================================

}