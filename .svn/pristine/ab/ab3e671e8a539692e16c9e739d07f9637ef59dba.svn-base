<?php

/**
 * 手机短信发送处理类
 * @author letv
 *
 */
class MessageModel {
    //目前的常量以后可以在后台管理中管理
    const SendMessageBy = 'sso::sendmessageby:';//Redis中存储的前缀
    const TianYiMessage = 1;//天翼短信平台
    const JianZhouMessage = 2;//建周短信平台

    /**
     * 获取短信模板
     * @param
     * action : 动作分类
     * info: 小心内容
     * lang : 语言分类
     */
    public function getMessagePlatment($action, $info = '', $lang = 'chs', $switch = 'letv') {
        $message = '';
        switch ($lang) {
            //中文短信
            case 'chs':
                switch ($action) {
                    case 'registok':
                        if ($switch == 'letv') {
                            $message = '您已成功注册乐视网帐号，登录密码为：' . $info . '。为保证帐户安全，请您牢记密码，及时删除包含密码的短信。';
                        } elseif ($switch == 'cibn') {
                            $message = '您已成功注册CIBN帐号，登录密码为：' . $info . '。为保证帐户安全，请您牢记密码，及时删除包含密码的短信。';
                        }

                        break;
                    case 'regcode':
                        if ($switch == 'letv') {
                            $message = '激活码：' . $info . '。您已经通过乐视网发送激活码，请尽快使用。';
                        } elseif ($switch == 'cibn') {
                            $message = '激活码：' . $info . '。您已经通过CIBN发送激活码，请尽快使用。';
                        }
                        break;
                    case 'shortreg':
                        if ($switch == 'letv') {
                            $message = '尊敬的用户，您的登录密码为：' . $info . ',发送“新密码”到10690228102921就可以修改密码';
                        } elseif ($switch == 'cibn') {
                            $message = '尊敬的用户，您的登录密码为：' . $info . ',发送“新密码”到106902512188就可以修改密码';
                        }
                        break;
                    case 'mobileshortregleyingke':
                        $message = '乐影客手机帐号可登录乐视网了，请牢记您的登录密码:' . $info . '。如果您忘记了密码，可以在乐视网使用您的手机找回。';
                        break;
                    case 'mobileshortreg':
                        $message = '尊敬的用户，您的登录密码为：' . $info . ',发送“PW新密码”到10690228102921就可以修改密码';
                        break;
                    case 'modifypwdcode':
                        $message = '亲爱的会员,您的激活验证码为:' . $info;
                        break;
                    case 'loginerror':
                        $message = '对不起，您的账号存在异常。';
                        break;
                    case 'modifypwdok':
                        $message = '您的密码已修改为：' . $info . '。请使用新密码登录。为保证帐户安全，请您牢记密码，及时删除包含密码的短信。';
                        break;
                    case 'msgregerror':
                        $message = '你的手机号' . $info . '没有与乐视账号绑定,故无法提供密码找回功能';
                        break;
                    case 'pwdformalerror':
                        $message = '密码格式不正确，格式规则为：6至16位英文、数字或其他符号的组合，英文区分大写小写，不支持使用中文、全角字符和空格。';
                        break;
                    case 'msgreghasreg':
                        $message = '您的手机号已经注册过乐视帐号，请使用手机号码直接登录。如果需要修改密码，请发送新密码到10690228102921';
                        break;
                    case 'regerror':
                        $message = '对不起，由于服务器故障，注册失败。';
                        break;
                    default:
                        break;
                }
                break;
            //英文模板
            case 'en':
                switch ($action) {
                    case 'regcode':
                        $message = $info . ' is your LETV activation code.';
                        break;
                    case 'modifypwdcode':
                        $message = $info . ' is your LETV confirmation code.';
                        break;
                    default:
                        break;
                }
                break;
            //德语法语俄语阿拉伯语南斯拉夫语北斯拉夫语……返回空
            default:
                break;
        }
        return $message;
    }


    /**
     * @desc 手机发送短信 使用新的
     * @version 2015-06-02
     * @param string $mobile
     * @param string $info
     * @param number $period
     * @return boolean
     */
    public function mobileSend($mobile, $info = '', $period = 10) {
        if (empty($mobile)) {
            return false;
        }
        //if(preg_match('/^1[3|4|5|7|8]\d{9}$/', $mobile)) {
        if (empty($info)) {
            return false;
        }
//        $send_to = RedisIO::get($this::SendMessageBy);
        $send_to = "common";
        if ($send_to){
            error_log(date("Y-M-d H:i:s") . ' | ' . $mobile . ' | processing' . "\r\n", 3, "/tmp/code/{$mobile}.log");//TODO新增短信日志
//            $flag = $this->sendmessage($mobile, $info, $period, $send_to);
            $flag = Plugin_Util::mobileSendByCommon($mobile, $info ,'TX' , $period);
            if ($flag == false) {
                $flag = Plugin_Util::mobileSendByCommon($mobile, $info ,'TY' , $period);
                if ($flag == false) {
                    $flag = Plugin_Util::mobileSendByCommon($mobile, $info ,'JZ' , $period);
                }
            }
        }else{
            $flag = false;
        }
        //}
        //记录日志
        if ($flag) {
            error_log(date("Y-M-d H:i:s") . ' | ' . $mobile . ' | return' . "\r\n", 3, "/tmp/code/{$mobile}.log");//TODO新增短信日志
            $userModel = new User();
            $userModel->signSysLog('sso', 'mobileSendTotal', 'model', 'mobileSend', array($mobile));
        }
        return $flag;
    }

    /**
     * 发送短信方法，具体厂家修改在此方法内修改
     * @param $mobile
     * @param $info
     * @param $period
     * @param $send_to
     * @return bool|int
     */
    private function sendmessage($mobile, $info, $period, $send_to) {
        $send_arr = explode(',' , $send_to);
        foreach ($send_arr as $key => $value) {
            switch ($value) {
                case $this::TianYiMessage:
                    $flag = Plugin_Util::mobileSend($mobile, $info, $period);
                    break;
                case $this::JianZhouMessage:
                    $flag = Plugin_Util::mobileSendByJianZhou($mobile, $info, $period);
                    break;
                default:
                    $flag = false;
            }
            if ($flag){
                return true;
            }
        }
        return false;
    }

}
