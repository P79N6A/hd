<?php

/**
 * 周年庆活动第三方授权的控制器svn
 * Created by PhpStorm.
 * User: fang
 * Date: 2016/8/6
 * Time: 10:12
 */
class YearController extends WeiXinBaseController
{
    public function uptokenAction()
    {
        var_dump($this->updateToKen());
    }

    /**
     * 授权
     */
    public function oAuthAction()
    {
        $id = Request::getQuery("id", "string", 0);
        $redirect_url = $this->callback . "/Year/user?id={$id}";
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->app_id . '&redirect_uri=' . urlencode($redirect_url) . '&response_type=code&scope=snsapi_userinfo&state=' . time() . '#wechat_redirect';
        header('Location:' . $url);
        exit;

    }

    /**
     * 处理用户信息
     *
     */
    public function userAction()
    {
        $id = Request::getQuery("id", "string", 0);
        $userInfo = $this->getUserInfo();

        if ($userInfo) {
            $data = array(
                'openid' => $userInfo['openid'],
                'nickname' => $userInfo['nickname'],
                'headimgurl' => $userInfo['headimgurl']
            );
            $params = json_encode($data);


            //对称加密算法
            $keyStr = '533536EC9AC8665B';
            $aes = new CryptAES();
            $aes->set_key($keyStr);
            $aes->require_pkcs5();
            $params = $aes->encrypt($params);


            $url = "http://zjstv.znq.weijuju.com/zjwsznqController/index?id={$id}&code=" . urlencode($params);

            header('Location:' . $url);
            exit;
        } else {
            $this->_json([], 4009, "userinfo error!");
        }

    }


    /**
     * 判断是否关注用户
     */
    public function getIsSubsByOpenIdAction($openid)
    {
        $res = $this->isSubscribe($openid);
        if($res){
            $this->_json($res);
        }else{
            $this->_json([], 4008, "get issub fail");
        }
    }

    /**
     * 获取用户信息
     */
    protected function getUserInfo()
    {
        //获取用户openid
        $authInfo = $this->getOpenId();
        $openid = $authInfo['openid'];
        $accessToken = $authInfo['access_token'];
        if ($openid && $accessToken) {
            //TODO 统一外网服务接口
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $accessToken . "&openid=" . $openid ."&lang=zh_CN";
            $userInfo = F::curlRequest($url);
            $userInfo = json_decode($userInfo, true);
            return $userInfo;
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @param int $code
     * @param string $msg
     */
    protected function _json($data, $code = 200, $msg = "success")
    {
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }


    /**
     * 获取openID
     * @return bool
     */
    protected function getOpenId()
    {
        $code = Request::getQuery('code', 'string');
        if ($code) {
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->app_id . '&secret=' . $this->app_secret . '&code=' . $code . '&grant_type=authorization_code';
            //TODO 统一外网服务接口
            $userInfo = F::curlRequest($url);
            $userInfo = json_decode($userInfo, true); //转为数组
            return $userInfo;
        } else {
            return false;
        }
    }


}