<?php

/**
 * 接凯哥活动使用配置：
 * 页面渲染控制器一个，H5页面一个，页面请求接口一个，对应摇奖接口一个
 *
 * @RoutePrefix("/getkaige")
 */
class GetKaiGeController extends \PublishBaseController {

    private $uid_cache = "kg::index::status:";//登入状态
    private $uid_get = "kg::lottery::status:";//中奖状态
    private $uid_num = "kg::kaige::number:";//接住凯哥的数量
    /**
     * 活动首页
     * @Get('/index')
     */
    public function indexAction() {
        //简单验证是不是APP端
        $input = Request::getQuery();
        $keeps = ['client_id', 'timestamp', 'type', 'signature'];
        if(!issets($input, $keeps)) {
            echo "提示：非中国蓝TV客户端进入";
            exit;
        }
        
        //获取client_id
        $client_id = Request::getQuery('client_id', 'string');
        if(!$client_id){
            echo '无用户ID';
            exit;
        }
        
        $status = RedisIO::get($this->uid_cache.$client_id);
        if($status) {//已玩过直接进入结果页面
            View::setVar('bingo',1);
            $status = RedisIO::get($this->uid_get.$client_id);
            $kaigenum = RedisIO::get($this->uid_num.$client_id);
            View::setVar('kaigenum',$kaigenum);
            if($status){
                View::setVar('statuscode',1);
            }else{
                View::setVar('statuscode',0);
            }
        }else {//进入欢迎页面
            View::setVar('bingo',0);
            View::setVar('statuscode',0);
        }
    }
    
    /**
     * 删除redis
     */
    public function delAction(){
        
        $client_id = $client_id = Request::getQuery('client_id', 'string');
        $res = RedisIO::delete($this->uid_cache.$client_id);       
        if($res){
            echo "清除缓存成功！";
        }else{
            echo "清除缓存失败！";
        }
    }
    
    /**
     * 域名查询
     * 
     */
    protected function defaultDomainCheck($host) {
        $this->domain_id =6;
        $this->channel_id = 1;
        return true;
    }

}