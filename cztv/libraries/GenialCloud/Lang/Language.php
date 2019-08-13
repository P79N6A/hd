<?php

namespace GenialCloud\Lang;

class Language {

    public $defaultPath;
    public $defaultLanguage;

    static $language;
    static $lang;
    static $ext = '.php';
    static $defaultFile = 'cms';

    /**
     * 加载配置
     * @param array $config
     */
    public function __construct(array $config) {
        if(!empty($config)) {
            foreach($config as $k => $v) {
                $this->$k = $v;
            }
        }
        self::$language = \Request::getBestLanguage();
    }

    /**
     * 获取语言包路径
     * @param $file
     * @param bool|true $default
     * @return string
     */
    protected function getLangFile($file, $default = true) {
        $path = self::$language;
        if($default) {
            $path = $this->defaultLanguage;
        }
        return rtrim($this->defaultPath, "/").'/'.$path.'/'.$file.self::$ext;
    }

    /**
     * 初始化语言包
     * @param string $file
     * @throws \Exception
     */
    protected function initLang($file) {
        $langFile = $this->getLangFile($file, false);
        $defaultFile = $this->getLangFile($file);
        if(!file_exists($langFile) && !file_exists($defaultFile)) {
            throw new \Exception("Can not Find Language File ! ", 400);
        }
        self::$lang = require file_exists($langFile)? $langFile: $defaultFile;
    }

    /**
     * 调用语言
     * @param $index
     * @param string $file
     * @return mixed
     * @throws \Exception
     */
    public function _($index, $file = '') {
        $file = $file?: self::$defaultFile;
        if(is_null(self::$lang)) {
            $this->initLang($file);
        }
        return isset(self::$lang[$index])? self::$lang[$index]: $index;
    }

}
