<?php

if (!function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd() {
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());
        die;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * @return string
     */
    function csrf_token() {
        return Session::getCsrfToken();
    }
}

if (!function_exists('camel_case')) {
    /**
     * Convert a string to camel case.
     *
     * @param  string $str
     * @return string
     */
    function camel_case($str) {
        return str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $str)));
    }
}

if (!function_exists('get')) {
    /**
     * @param array $container
     * @param string|int $key
     * @param null $default
     * @return null
     */
    function get(array $container, $key, $default = null) {
        if (isset($container[$key])) {
            return $container[$key];
        }
        return $default;
    }
}

/**
 * add slashes on query
 * @param $v
 * @return string
 */
function q($v) {
    if (!get_magic_quotes_gpc()) {
        $v = "'" . addslashes($v) . "'";
    }
    return $v;
}

/**
 * alias of function htmlspecialchars
 *
 * @param string $str
 * @param int $flags
 * @param string $encoding
 * @param bool $double_encode
 * @return string
 * @see htmlspecialchars()
 */
function h($str, $flags = ENT_COMPAT, $encoding = 'UTF-8', $double_encode = true) {
    return htmlspecialchars($str, $flags, $encoding, $double_encode);
}

/**
 * 直接跳转
 */
function redirect($href, $code = 302) {
    if ($code == 301) {
        header("HTTP/1.1 301 Moved Permanently");
    } else {
        header("HTTP/1.1 302 Moved Temporarily");
    }
    header("Location: $href");
    exit;
}

/**
 * 随机字符串
 * @param $length
 * @return string
 */
function str_random($length = 16) {
    if (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes($length * 2);
        if ($bytes === false) {
            throw new \RuntimeException('Unable to generate random string.');
        }
        return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
    } else {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}


/**
 * 限制字符串长度
 *
 * @param  string $value
 * @param  int $limit
 * @param  string $end
 * @return string
 */
function str_limit($value, $limit = 100, $end = '...') {
    if (mb_strlen($value) <= $limit)
        return $value;
    return rtrim(mb_substr($value, 0, $limit, 'UTF-8')) . $end;
}


/**
 * 随机字符串
 * @param $length
 * @return string
 */
function num_random($length = 6) {
    $pool = '0123456789';
    return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
}

/**
 * Get site config by domain
 * @return mixed
 * @throws Exception
 */
function app_site() {
    global $config;
    static $site;
    if (!$site) {
        $host = get($_SERVER, 'HTTP_HOST', '');
        if (isset($config->sites->$host)) {
            $site = $config->sites->$host;
        } elseif (isset($config->sites->default)) {
            if (isset($config->sites->default->is_default) && $config->sites->default->is_default) {
                $site = $config->sites->default;
            }
        }
        if (!$site) {
            throw new \Phalcon\Exception('Invalid site config.', 404);
        }
    }
    return $site;
}

if (!function_exists('static_url')) {
    /**
     * @param $path
     * @return string
     * @throws Exception
     */
    function static_url($path) {
        static $url, $ver;
        if (!$url) {
            $url = '/';
            if (isset(app_site()->static_url)) {
                $url = app_site()->static_url;
            }
        }
        if (is_null($ver)) {
            $ver = '';
            if (isset(app_site()->static_version)) {
                $ver = '?v=' . app_site()->static_version;
            }
        }
        return $url . $path . $ver;
    }
}

if (!function_exists('cdn_url')) {
    /**
     * @param $path
     * @return string
     * @throws Exception
     */
    function cdn_url($key, $path = '') {
        static $urls;
        if (!isset($urls[$key])) {
            $urls[$key] = '/';
            if (isset(app_site()->cdn_url[$key])) {
                $urls[$key] = app_site()->cdn_url[$key];
            }
        }
        return $urls[$key] . $path;
    }
}

if (!function_exists('open_cache')) {
    /**
     * @param $path
     * @return string
     * @throws Exception
     */
    function open_cache() {
        if (isset(app_site()->open_cache)) {
            return app_site()->open_cache;
        }
        return false;
    }
}

/**
 * Get path for site
 * @param $path
 * @return string
 * @throws Exception
 */
function site_path($path) {
    return APP_PATH . 'sites/' . app_site()->id . '/' . $path;
}

/**
 * Get autoload paths for site
 * @return array
 * @throws Exception
 */
function site_autoload() {
    global $config;
    $site = app_site();
    $base_path = APP_PATH . 'sites/' . $site->id . '/';
    $paths = array_merge(array_map(function ($p) {
        return $p;
    }, (array)$config->autoload), array_map(function ($path) use ($base_path) {
        return $base_path . $path . '/';
    }, (array)$site->paths));
    return $paths;
}

/**
 * Get view path for site
 * @return string
 * @throws Exception
 */
function site_view() {
    return site_path('views/');
}

/**
 * Get routes path for site
 * @return string
 */
function site_route() {
    return site_path('routes.php');
}

function not_empty($array, $key) {
    return isset($array[$key]) && !empty($array[$key]);
}

function issets(array $params, array $fields) {
    foreach ($fields as $v) {
        if (!isset($params[$v])) {
            return false;
        }
    }
    return true;
}

function array_refine(array $array, $key, $value = "") {
    $data = [];
    foreach ($array as $v) {
        $data[$v[$key]] = $value ? $v[$value] : $v;
    }
    return $data;
}

function obj_refine($array, $key, $value = "") {
    $data = [];
    foreach ($array as $v) {
        $key ? $data[$v->$key] : $data[] = $value ? $v->$value : $v;
    }
    return $data;
}

/**
 * ID 字符串转换成数组
 *
 * @param $str
 * @param string $delimiter
 * @return array
 */
function ids($str, $delimiter = ',') {
    $r = [];
    if ($str) {
        $r = array_unique(
            array_map(function ($id) {
                return (int)trim($id);
            }, explode($delimiter, $str))
        );
    }
    return $r;
}

function abort($code, $message = '', $previous = null) {
    throw new \GenialCloud\Exceptions\HttpException($code, $message, $previous);
}

function lang($msg) {
    Lang::_($msg);
}

/**
 * 调试 SQL 的函数
 *
 * @param $t
 */
function debug_sql(&$t) {
    $profiles = $t->getDI()->get('profiler')->getProfiles();
    if (count($profiles)) {
        echo '<section style="margin:auto;width:90%;border:#ccc 1px solid;white-space: pre-wrap;padding: 20px;margin-top: 20px;margin-bottom: 20px;background-color: #FFF;">';
        /**
         * @var $profile Phalcon\Db\Profiler\Item
         */
        foreach ($profiles as $profile) {
            echo '<div style="border-bottom: 1px #CCC solid;">';
            echo '<b>QUERY</b>', "\t\t", str_replace(array(' ? ', ' ?'), ' <span style="color:#CD3F3F;">?</span> ', $profile->getSqlStatement()), "\n";
            //        echo '<b>BINDINGS</b>', "\t", '<span style="color:#CD3F3F;">', $profile->getSqlBindTypes(), "</span>\n";
            echo '<b>TIME</b>', "\t\t", '<span style="color:#060;;">', $profile->getTotalElapsedSeconds(), '</span>', "\n";
            echo '</div>';
        }
        echo '</section>';
    }
}

/**
 * HTTP 错误
 *
 * @param $code
 */
function http_error($code, $message = '') {
    return GenialCloud\Exceptions\HttpException::processError($code, $message);
}

/**
 * 过滤 JS 输出
 *
 * @param $str
 * @param string $quoter
 * @return mixed
 */
function js_output($str, $quoter = '"') {
    $str = str_replace(["\r", "\n", $quoter], [' ', ' ', '\\' . $quoter], $str);
    $str = preg_replace('(\s+)', ' ', $str);
    $str = trim($str);
    return $str;
}

/**
 * @param $url
 * @param string $file
 * @param int $timeout
 * @return bool|mixed|string
 */
function httpcopy($url, $file = "", $timeout = 60, $proxy=false) {
    $file = empty($file) ? pathinfo($url, PATHINFO_BASENAME) : $file;
    $dir = pathinfo($file, PATHINFO_DIRNAME);
    !is_dir($dir) && @mkdir($dir, 0755, true);
    $url = str_replace(" ", "%20", $url);

    if (function_exists('curl_init')) {
        if($proxy) {
            $temp = F::curlProxy($url);
        }
        else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (CZTV_PROXY_ST == 1) {
            curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
            curl_setopt($ch, CURLOPT_PROXY, CZTV_PROXY_IP); //代理服务器地址
            curl_setopt($ch, CURLOPT_PROXYPORT, CZTV_PROXY_PORT); //代理服务器端口
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);//使用了SOCKS5代理
        }

        $temp = curl_exec($ch);
        }
        if (file_put_contents($file, $temp)) {
            return $file;
        } else {
            return false;
        }
    } else {
        $opts = array(
            "http" => array(
                "method" => "GET",
                "header" => "",
                "timeout" => $timeout)
        );
        $context = stream_context_create($opts);
        if (@copy($url, $file, $context)) {
            //$http_response_header
            return $file;
        } else {
            return false;
        }
    }
}



/**
 * @param $url
 * @param string $file
 * @param int $timeout
 * @return bool|mixed|string
 */
function httpcopyproxy($url, $file = "", $timeout = 60) {
    $file = empty($file) ? pathinfo($url, PATHINFO_BASENAME) : $file;
    $dir = pathinfo($file, PATHINFO_DIRNAME);
    !is_dir($dir) && @mkdir($dir, 0755, true);
    $url = str_replace(" ", "%20", $url);

    if (function_exists('curl_init')) {
        $temp = F::curlProxy($url);
        echo $temp;exit;
        if (@file_put_contents($file, $temp)) {
            return $file;
        } else {
            return false;
        }
    } else {
        $opts = array(
            "http" => array(
                "method" => "GET",
                "header" => "",
                "timeout" => $timeout)
        );
        $context = stream_context_create($opts);
        if (@copy($url, $file, $context)) {
            //$http_response_header
            return $file;
        } else {
            return false;
        }
    }
}

/**
 * curl模拟请求
 * @param string $url
 * @param string $type get|post
 * @param array $param
 * @param int $timeout
 * @return string
 */
function curl_request($url, $type = 'get', $param = array(), $timeout = 5, $proxy_forbidden = false) {
    $str = '';
    $curl = curl_init();
    if ('post' == $type) {
        curl_setopt($curl, CURLOPT_POST, $type);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
    }
    if (!$proxy_forbidden && CZTV_PROXY_ST == 1) {
        curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
        curl_setopt($curl, CURLOPT_PROXY, CZTV_PROXY_IP); //代理服务器地址
        curl_setopt($curl, CURLOPT_PROXYPORT, CZTV_PROXY_PORT); //代理服务器端口
        curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
    }
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); #默认5s超时时间
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //返回输出文本流
    $str = curl_exec($curl);
    curl_close($curl);
    return $str;
}

/**
 * Forces the user's browser not to cache the results of the current request.
 *
 * @return void
 * @access protected
 * @link http://book.cakephp.org/view/431/disableCache
 */
function disableBrowserCache() {
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

/**
 * Return current url
 *
 * @return string
 */
function getCurrentUrl() {
    $url = 'http';

    if ('on' == $_SERVER["HTTPS"]) $url .= 's';

    $url .= "://" . $_SERVER["SERVER_NAME"];

    $port = $_SERVER["SERVER_PORT"];
    if (80 != $port) $url .= ":{$port}";

    return $url . $_SERVER["REQUEST_URI"];
}