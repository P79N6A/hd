<?php
/**
 * @class:   SecretVerify 口令检测
 * @author:  汤荷
 * @version: 1.0
 * @date:    2016/11/17
 */

use GenialCloud\Auth\Signature;
class SecretVerify{

    const SECRET_KEY_LEN = 60;

    const SECRET_KEY_NOT_SET = 10000; //没有口令
    const SECRET_KEY_OK = 10001; //口令验证通过

    const SECRET_KEY_LEN_ERROR = 10002;
    const SECRET_KEY_ERROR = 10003;
    const SECRET_KEY_INPUT_EMPTY = 10004;

    protected static $SECRET_ERROR_MSG=[
        self::SECRET_KEY_LEN_ERROR => "口令错误,请重新输入", //长度错误
        self::SECRET_KEY_ERROR => "口令错误，请重新输入",
        self::SECRET_KEY_INPUT_EMPTY => "口令错误，请重新输入" //没有输入口令
    ];



    public static function getErrMsg($errorCode){
        if ( isset(self::$SECRET_ERROR_MSG[$errorCode]) ){
            return self::$SECRET_ERROR_MSG[$errorCode];
        }else{
            return "错误的状态码";
        }
    }

}