<?php
/**
 * @name XmasEncrypt 伪随机加密
 * @author king
 */

namespace GenialCloud\Helper;

class XmasEncrypt {

    /**
     * 默认密匙
     */
    public $cryptKey = 'XmasEncrypt';

    /**
     * 活动密匙长度
     */
    public $activeKeyLength = 6;

    public function init() {}

    /**
     * 加密字符串
     * @param string str加密字符
     * @param key str 加密密匙
     */
    public function encode($string, $key=null) {
        $result = $this->createResult($string, $this->createKey($string, $key, 'encode'));
        return $this->randTime.str_replace('=', '', base64_encode($result));
    }

    /**
     * 解密字符
     * @param string str 解密字符
     * @param key str 解密密匙
     */
    public function decode($string, $key=null) {
        return $this->createResult($string, $this->createKey($string, $key, 'decode'));
    }

    /**
     * 创建真实静态密匙
     */
    public function createKey($string, $key, $option) {
        if($key === null) {
            $key = $this->cryptKey;
        }
        $this->option = $option;
        /**
         * 生成不同的活动密码
         */
        $this->randTime = substr(md5(microtime()), -$this->activeKeyLength);
        if($this->option=='decode'){
            $this->randTime = substr($string, 0, $this->activeKeyLength);
        }
        return $this->randTime.md5($this->randTime.$key.$this->randTime);
    }

    /**
     * 创建加密明文
     */
    public function createResult($string,$randKey) {
        $string = $this->option =='decode' ? base64_decode(substr($string, $this->activeKeyLength)) : $string;
        $boxKey = array();
        /**
         * 密码盒子
         */
        $box = range(0, 255);
        for($i = 0; $i <= 255; $i++) {
            $boxKey[$i] = ord($randKey[$i % strlen($randKey)]);
        }

        /**
         * 搅乱盒子
         */
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $boxKey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        /**
         * 生成加密明文
         */
        $result = '';
        // 核心加解密部分
        for($a = $j = $i = 0; $i < strlen($string); $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        return $result;
    }
}
