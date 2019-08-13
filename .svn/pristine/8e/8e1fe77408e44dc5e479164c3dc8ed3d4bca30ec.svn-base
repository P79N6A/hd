<?php

use GenialCloud\Core\Facade;

/**
 * Class Request
 *
 * @method static mixed get($name = null, $filters = null, $defaultValue = null)
 * @method static mixed getPost($name = null, $filters = null, $defaultValue = null)
 * @method static mixed getQuery($name = null, $filters = null, $defaultValue = null)
 * @method static mixed getServer($name)
 * @method static bool has($name)
 * @method static bool hasPost($name)
 * @method static bool hasPut($name)
 * @method static bool hasQuery($name)
 * @method static bool hasServer($name)
 * @method static string getHeader($header)
 * @method static string getScheme()
 * @method static bool isAjax()
 * @method static bool isSoapRequested()
 * @method static bool isSecureRequest()
 * @method static string getRawBody()
 * @method static string getServerAddress()
 * @method static string getServerName()
 * @method static string getHttpHost()
 * @method static string getClientAddress($trustForwardedHeader = false)
 * @method static string getMethod()
 * @method static string getUserAgent()
 * @method static bool isMethod($methods, $strict = false)
 * @method static bool isPost()
 * @method static bool isGet()
 * @method static bool isPut()
 * @method static bool isHead()
 * @method static bool isDelete()
 * @method static bool isOptions()
 * @method static bool hasFiles($onlySuccessful = false)
 * @method static \Phalcon\Http\Request\FileInterface getUploadedFiles($onlySuccessful = false)
 * @method static string getHTTPReferer()
 * @method static array getAcceptableContent()
 * @method static string getBestAccept()
 * @method static array getClientCharsets()
 * @method static string getBestCharset()
 * @method static array getLanguages()
 * @method static string getBestLanguage()
 * @method static array getBasicAuth()
 * @method static array getDigestAuth()
 */
class Request {
    use Facade;

    public static function getParams() {
        $data = self::get();
        unset($data['_url']);
        return $data;
    }

    public static function getParamsStr() {
        return http_build_query(self::getParams());
    }

    public static function raw($len=4096) {
        $fp = fopen('php://input', 'r');
        if(!$fp) {
            throw new HttpRequestException('Open raw post handle failed.');
        }
        $raw = fread($fp, $len);
        fclose($fp);
        return $raw;
    }
}