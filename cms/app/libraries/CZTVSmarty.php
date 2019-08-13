<?php

require_once APP_PATH . 'libraries/Smarty/Smarty.class.php';

class CZTVSmarty extends Smarty {

    protected $domainId;
    protected $channelId;
    protected $testError = '';

    public function __construct($domain_id, $channel_id, array $data_functions, $page, $templates) {
        parent::__construct();
        $this->domainId = $domain_id;
        $this->channelId = $channel_id;
        $dir = '/tmp/smarty_compiles/' . $domain_id . '/';
        if (!file_exists($dir)) {
            mkdir($dir, 0774, true);
        }
        $this->setCompileDir($dir);
        SmartyData::init($channel_id, $domain_id);
        SmartyData::initTemplates($templates);
        $security = new Smarty_Security($this);
        $security->static_classes = null;
        $security->trusted_static_methods = $data_functions;
        $security->php_functions = [
            //出错
            'abort',
            //格式类
            'time', 'date', 'intval', 'floatval', 'ceil', 'floor',
            'json_decode', 'json_encode',
            'base64_decode', 'base64_encode',
            'unserialize', 'serialize',
            //条件类
            'isset', 'is_null', 'is_array', 'is_string',
            //字符串类
            'strlen', 'mb_strlen', 'str_replace', 'str_limit', 'str_random', 'stripos', 'explode',
            //数组类
            'count', 'in_array', 'array_unique', 'array_intersect', 'array_merge',
            //调试类
            'print_r', 'var_dump', 'dd',
            //静态文件输出
            'cdn_url',
        ];
        $this->enableSecurity($security);
        $this->registerResource('mem', new MemcacheSmartyResource($this->domainId));
        $this->assign(compact('domain_id', 'channel_id', 'page'));
    }

    public function test($tpl) {
        $data_id = 0;
        $data_type = '';
        $category_id = 0;
        $this->assign(compact('data_id', 'data_type', 'category_id'));
        try {
            $this->fetch('mem:' . $tpl);
            return true;
        } catch (Exception $e) {
            $this->testError = $e->getMessage();
            return false;
        }
    }

    public function getTestError() {
        return $this->testError;
    }

}