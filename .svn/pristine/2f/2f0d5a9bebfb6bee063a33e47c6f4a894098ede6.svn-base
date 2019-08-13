<?php
/**
 * 鹿晗测验
 * Created by PhpStorm.
 * User: fang
 * Date: 2016/7/19
 * Time: 10:12
 */

class LhceyanController extends WeiXinBaseController
{
  
    public function uptokenAction(){
       var_dump($this->updateToKen());
    }
    /**
     * 鹿晗测验首页
     */
    public function ceYanAction()
    {
        //回调地址
        $redirect_url = $this->callback . '/lhceyan/ceYan?';

        //获取用户open_id
        $openId = $this->_openId($redirect_url);

        //是否关注
        if ($this->isSubscribe($openId)) {
            $url = $this->lh_url;
        } else {
            $url = $this->lh_url_unsubscribe;

        }
        header('Location:' . $url);
        exit;
    }


    /**
     * 跑男工作证
     */
    public function paonanWorkAction()
    {
        //回调地址
        $redirect_url = $this->callback .'/lhceyan/paonanWork?';

        //获取用户open_id
        $openId = $this->_openId($redirect_url);

        //是否关注
        if ($this->isSubscribe($openId)) {
            $url = "http://dhudong.cztv.com/paonan?4free";
        } else {
            $url = "http://www.cztv.com/static/h5/201607/paonan/guide.html";

        }
        header('Location:' . $url);
        exit;
    }

    /**
     * 获取openId
     */
    private function _openId($redirect_url)
    {
        $code = Request::getQuery('code','string');
        //通过code获取openID
        if (!$code) {
            //$redirect_url = 'http://ssohudong.cztv.com/lhceyan/ceYan?';  //回调地址
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->app_id . '&redirect_uri=' . urlencode($redirect_url) . '&response_type=code&scope=snsapi_base&state=' . time() . '#wechat_redirect';
            header('Location:' . $url);
            exit;
        } else {
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->app_id . '&secret=' . $this->app_secret . '&code=' . $code . '&grant_type=authorization_code';
            //TODO 统一外网服务接口
            $userInfo = F::curlRequest($url);
            $userInfo = json_decode($userInfo, true); //转为数组
            return $userInfo['openid'];
        }
    }


}