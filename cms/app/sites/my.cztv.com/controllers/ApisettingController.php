<?php

/**
 * 用户中心-用户设置接口
 */
class ApisettingController extends ApiBaseController {
    private $userModel = null;
    private $currentUserInfo = array();
    protected static $lang = 'zh';

    //重写initialize方法
    public function initialize() {
        $this->userModel = new User();
        $this->currentUserInfo = $this->__getCurrentUserinfo();
    }

    /**
     * @desc 获取用户信息
     */
    public function getuserinfoAction() {
        $currentUserInfo = $this->__getCurrentUserinfo();
        if (empty($currentUserInfo)) {
            $this->__output(404, '当前用户信息为空');
        }
        $this->__output(200, $currentUserInfo);
    }

    /**
     * @desc 获取头像
     * @version 2015-06-30
     */
    public function geticonAction() {
        $userInfo = $this->currentUserInfo;
        $icons = array(
            'http://i1.letvimg.com/img/201207/30/tx298.png',
            'http://i0.letvimg.com/img/201207/30/tx145.png',
            'http://i3.letvimg.com/img/201207/30/tx40.png'
        );
        if ($userInfo['picture']) {
            $icons = explode(',', $userInfo['picture']);
        }
        if (empty($userInfo)) {
            $this->__output(404, '当前用户信息为空');
        }
        $this->__output(200, $icons);
    }


    /**
     * @desc 上传原始头像，并转换成300*300的jpeg保存
     * @version 2015-06-09
     * @param
     *
     */
    public function uploadiconAction() {
        $maxWidth = $maxHeight = 250;
        $maxIconSize = 1024 * 1024 * 5;
        $allowType = array(
            'image/png',
            'image/gif',
            'image/jpeg'
        );
        $_FILES['image']['name'] ? '' : $this->__output(400, '图片名称为空');
        ($_FILES['image']['size'] <= $maxIconSize) ? '' : $this->__output(400, '图片太大了，请选择小于5M的图片');

        $img = $_FILES['image']['tmp_name'];
        $ext = substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], '.')+1);

        if (CZTV_PROXY_ST == 1) {
            $vpc_domain = app_site()->vpc_domain;
            $img = str_ireplace(cdn_url('image'), $vpc_domain, $img);
        }
        $imgInfo = getimagesize($img);

        if (!in_array($imgInfo['mime'], $allowType)) {
            $this->__output(400, '请选择符合类型的图片');
        }
        try {
            $scale = ($imgInfo[0] > $imgInfo[1]) ? ($maxWidth / $imgInfo[0]) : ($maxHeight / $imgInfo[1]);
            Img::resizeImage($img, $imgInfo[0], $imgInfo[1], $scale, $maxWidth, $maxHeight);
            $result = Img::upload($img, $ext);
            if (!empty($result['file'])) {
                $oriImg = cdn_url('image', $result['file']);
            }
            unlink($img);
        } catch (Exception $e) {
            $this->__output(500, '服务器繁忙');
        }
        if (intval($result['state']) != 1 || empty($oriImg)) {
            $this->__output(500, '服务器繁忙');
        }

        $this->__output(200, array('oriImg' => $oriImg));
    }

    /**
     * @desc 裁剪，更像用户头像
     * @version 2015-06-09
     * @param
     */
    public function updateiconAction() {
        header("Cache-Control: max-age=0");
        $img = filter_var(Request::getPost("oriImg"), FILTER_VALIDATE_URL);
        if (!$img) {
            $this->__output(400);
        }

        $cropx = intval(Request::getPost("x")); // 开始裁剪的横坐标（图片左上角为原点）
        $cropy = intval(Request::getPost("y")); // 开始裁剪的纵坐标
        $cropw = intval(Request::getPost("w")); // 裁剪的宽度
        $croph = intval(Request::getPost("h")); // 裁剪的高度
        if (!($cropw && $croph)) { // x,y可以是0
            $this->__output(400);
        }

        try {
            $filename = $this->currentUserInfo['uid'] . time();
            $imgL = "/tmp/{$filename}_145_145.jpg";
            $imgS = "/tmp/{$filename}_40_40.jpg";

            $cropedImgL = 145;
            $cropedImg = array(
                array(
                    'file' => $imgL,
                    'width' => $cropedImgL,
                    'height' => $cropedImgL
                )
            );
            Img::cropImgBatch($img, $cropx, $cropy, $cropw, $croph, $cropedImg);
            $resultL = Img::upload($imgL);
            unlink($imgL);

            $cropedImgS = 40;
            $cropedImg = array(
                array(
                    'file' => $imgS,
                    'width' => $cropedImgS,
                    'height' => $cropedImgS
                )
            );
            Img::cropImgBatch($img, $cropx, $cropy, $cropw, $croph, $cropedImg);
            $resultS = Img::upload($imgS);
            unlink($imgS);
            /*
            $imgL = str_replace('bak', '145_145', $resultL['file']);
            //$imgM = str_replace('bak', '70_70', $resultL['file']);
            $imgS = str_replace('bak', '40_40', $resultL['file']);
            */
            $imgL = cdn_url('image', $resultL['file']);
            $imgS = cdn_url('image', $resultS['file']);
            $icons = array(
                $img,
                $imgL,
                //$imgM,
                $imgS
            );
            $response = $this->userModel->updateUserIcon($_COOKIE['sso_tk'], $icons);
            $this->__checkError($response);

            setcookie("sso_picture", $imgS, time() + 3600 * 24 * 365, '/', '.cztv.com');
            setcookie("sso_icon", implode(',', $icons), time() + 3600 * 24 * 365, '/', '.cztv.com');

        } catch (Exception $e) {
            $this->userModel->signSysLog('my', 'apisetting', 'updateicon', 'setting', array(date('Y-m-d H:i:s'), $e->getFile(), $e->getLine(), $e->getMessage()));

            $this->__output(500, '服务器繁忙');
        }
        //$response['message'] = '成功';
        $this->__checkResponse($response);
    }

    /**
     * @desc 设置用户信息
     * @version 2015-06-09
     * @param
     * gender
     * birthday
     * province
     * city
     * nickname
     *
     */
    public function setinfoAction() {
        $userInfoRule = array(
            'gender' => array(
                'filter' => FILTER_VALIDATE_INT,
                'options' => array(
                    'min_range' => 0,
                    'max_range' => 2
                )
            ),
            'birthday' => FILTER_SANITIZE_STRING,
            'province' => FILTER_SANITIZE_STRING,
            'city' => FILTER_SANITIZE_STRING,
            'nickname' => FILTER_SANITIZE_STRING,
        );

        if (static::$lang == 'zh-hk') {
            $userinfoRule['name'] = FILTER_SANITIZE_STRING;
        }
        if (isset($_COOKIE['language'])) {
            $languageCookie = $_COOKIE['language'];
        }
        if (!empty($languageCookie)) {
            if ('zh-HK' == $languageCookie || 'en-US' == $languageCookie) {
                $userinfoRule['name'] = FILTER_SANITIZE_STRING;
            }
        }
        $userInfo = filter_input_array(INPUT_POST, $userInfoRule);
        $userInfo['birthday'] = date('Y-m-d', strtotime($userInfo['birthday']));
        if ($userInfo['birthday'] == '1970-01-01') {
            $userInfo['birthday'] = '';
        }

        if (!$this->userModel->checkNickname($userInfo['nickname'])) {
            $this->__output(400, '昵称不符合要求，请修改');
        }

        $userInfo['uid'] = $this->currentUserInfo['uid'];
        try {
            $userInfo['mobile_email'] = 1;
            $response = $this->userModel->updateUserinfo($userInfo);
            $this->__checkError($response);
            if (isset($_COOKIE['u']) && $_COOKIE['u']) {
                $u = json_decode(base64_decode($_COOKIE['u'], true), true);
                $u['nickname'] = $userInfo['nickname'];
                $u = base64_encode(json_encode($u));
                setcookie('u', $u, time() + 86400, '/', '.cztv.com');
            }
            setcookie('sso_nickname', $userInfo['nickname'], time() + 86400, '/', '.cztv.com');
        } catch (Exception $e) {
            $this->__output(500, '服务器繁忙');
        }
        //$response['message'] = '修改成功';
        $this->__checkResponse($response);
    }

    /**
     * @desc 修改密码
     * @version 2015-06-09
     * @param
     * pwd
     * oldpwd
     */
    public function updatepasswordAction() {
        $pwdRule = array(
            'pwd' => array(
                'filter' => FILTER_CALLBACK,
                'options' => array(
                    $this->userModel,
                    'checkPwd'
                )
            ),
            'oldpwd' => array(
                'filter' => FILTER_CALLBACK,
                'options' => array(
                    $this->userModel,
                    'checkPwd'
                )
            )
        );
        $pwd = filter_input_array(INPUT_POST, $pwdRule);
        ($pwd['pwd'] && $pwd['oldpwd']) ? '' : $this->__output(400, '密码格式不正确');

        try {
            if ($this->currentUserInfo['mobile'] || $this->currentUserInfo['email']) {
                $_COOKIE['sso_tk'] = $_COOKIE['sso_tk'] ? $_COOKIE['sso_tk'] : $_GET['sso_tk'];//兼容测试
                $oldpwd = $this->request->getPost('oldpwd');
                $newpwd = $this->request->getPost('pwd');
                $response = $this->userModel->updateUserPassword($_COOKIE['sso_tk'], $newpwd, $oldpwd);
            } else { // 如果用户没有邮箱和手机信息，则修改密码没有意义，需要同时设置邮箱/手机和密码
                $this->__output(400, '请先绑定手机或邮箱');
            }
        } catch (Exception $e) {
            $this->__output(500, '服务器繁忙');
        }
        //$response['message'] = '密码修改成功';
        $this->__checkResponse($response);
    }

    /**
     * @desc 统一对http的response的检查
     * @version 2015-06-09
     * @param array $response
     */
    private function __checkError(array $response) {
        if (isset($response['errorCode']) && $response['errorCode']) {
            $this->__output($response['errorCode'], $response['message']);
        }
    }

    /**
     * 统一对http的response的检查
     *
     * @param array $response
     */
    private function __checkResponse(array $response) {
        if (isset($response['errorCode']) && $response['errorCode']) {
            $this->__output($response ['errorCode'], $response ['message']);
        } else if (isset($response['bean']['result']) && (intval($response['bean']['result']) == 1)) {
            $this->__output(200, $response ['message']);
        }
        $this->__output();
    }

    /**
     * 获取登录用户的信息
     *
     * @return array
     */
    private function __getCurrentUserinfo() {
        try {
            $userInfo = My::CurrentUserBaseInfo();
            if (empty ($userInfo)) {
                $this->__needLogin();
            }
        } catch (Exception $e) {
            $this->userModel->signSysLog('my', 'apisetting', 'setting', 'setting api', array(date('Y-m-d H:i:s'), $e->getFile(), $e->getLine(), $e->getMessage()));

            $this->__needLogin();
        }

        return $userInfo;
    }

    /**
     * 返回需要登录的状态码
     *
     * 这里重用http的403状态码
     */
    private function __needLogin() {
        $this->__output(403, '请登录');
    }

    /**
     * @desc 统一处理api的输出
     * @version 2015-06-09
     * @param number $code 典型值复用了一些http的状态码
     *                      200 ok 正常完成
     *                      400 bad request 参数错误
     *                      403 forbidden 未登录
     *                      500 internal error 内部错误
     * @param string $data
     */
    protected function __output($code = 200, $data = '') {
        if (($code == 400) && empty($data)) {
            $data = '请求无效';
        }
        $output = array(
            'code' => $code,
            'data' => $data
        );

        $get_domain = Request::get('domain');
        if (isset($get_domain) && $get_domain) {
            exit ('<script>document.domain="cztv.com";</script>' . json_encode($output));
        }

        $get_callback = Request::get('callback');
        $post_callback = Request::getPost('callback');
        if (isset($post_callback) && $post_callback && preg_match('/^\w+$/', $post_callback)) {
            exit("<script type=\"text/javascript\">try{document.domain='cztv.com';window.parent." . $post_callback . "(" . json_encode($output) . ")}catch(e){}</script>");
        } else if (isset($get_callback) && $get_callback) {
            exit($get_callback . "(" . json_encode($output) . ")");
        } else {
            exit(json_encode($output));
        }
    }
}