<?php

/**
 * @class:   阿里云oss，policy获取
 * @author:  汤荷
 * @version: 1.0
 * @date:    2016/12/27
 */

/**
 * @RoutePrefix("/osspolicy")
 */
class OsspolicyController extends ApiBaseController {

    private $ossPolicy;

    public function initialize(){
        parent::initialize();
        $ossConf =F::getConfig('oss_conf',Request::get("channel_id","int"));
        if(count($ossConf)==0){
            $this->_json([],404,"please add oss conf");
        }
        $this->ossPolicy = new OssPolicy($ossConf["oss_bucket"],$ossConf["oss_host"],$ossConf["oss_id"],$ossConf["oss_key"],BASE_PATH."/".$ossConf["oss_log"],intval($ossConf["oss_expire"]));

    }


    //上传结束后的回调
    public function statusAction() {
        $termianl = Request::getPost("terminal");
        $policy = Request::getPost("policy");
        $flag = Request::getPost("flag");
        $dir = Request::getPost("dir");
        $size = Request::getPost("size");
        $this->ossPolicy->writeLog([$termianl,$policy,$flag,$dir,$size]);
    }

    /**
     * @Get("/")
     * @return json
     */
    public function policyAction(){
        $policyArr = $this->ossPolicy->getPolicy();
        $this->ossPolicy->writeLog(["backend",$policyArr["policy"]]);
        $this->_json($policyArr);

    }
}