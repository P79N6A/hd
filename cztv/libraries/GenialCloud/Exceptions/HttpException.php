<?php namespace GenialCloud\Exceptions;

class HttpException extends \Phalcon\Exception {

    protected static $headerErrors = [
        404 => 'Not Found',
        403 => 'Forbidden',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        421 => 'There are too many connections from your internet address',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        600 => 'Unparseable Response Headers',
    ];

    public function __construct($code, $message='', $previous=null) {
        $message = self::processError($code, $message);
        parent::__construct($message, $code, $previous);
    }

    public static function processError($code, $message) {
        $messages = self::$headerErrors;
        if(isset($messages[$code])) {
            $msg = $messages[$code];
        } else {
            $msg = 'Unexpected error.';
        }
        if(!$message) {
            $message = $msg;
        }
        header('HTTP/1.0 '.$code.' '.$msg);
        return $message;
    }

    public static function getHeaderErrors() {
        return self::$headerErrors;
    }

}