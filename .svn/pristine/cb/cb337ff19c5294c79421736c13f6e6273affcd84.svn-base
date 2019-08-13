<?php

class UgcController extends \PublishBaseController
{

    /*
     * @desc UGC鉴权
     *
     * */
    public function authUgcAction()
    {
        $access_key = "44a8b3a2c6488031e937e4a4ace312af0829e2871f58ebaadc80ff9e7b093839";
        $secret_key = "cce61d0876c1612c1e5dd67ea2a19554456761c73e1f590c6459224d33c02dfd";
        $url = "/api/ban_ip_rtmp_url/get?host=send.yfcloud.cc\n";
        $signed_data = base64_encode((hash_hmac('sha1', $url, $secret_key)));
        $accessToken = join(':', array($access_key, $signed_data));
        $handle = curl_init();

        $headers = [
            "accessToken:$accessToken",
            "Connection:keep-alive",
            "Cache-Control:max-age=0"
        ];
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        ob_start();
        $response = curl_exec($handle);
        ob_end_clean();
        curl_close($handle);
        var_dump($response);
        return $response;
    }

    public function ugcUpLoadFileAction()
    {
        $url = 'http://upload.fileinject.yunfancdn.com/file?token=aa973d7828a55c19592b2d97e62d8s6f1&key=cztv/vod/2016/01/29/CB8E22D8FE38435cA72C47DE94C87E0F/&hash=CB8E22D8FE38435cA72C47DE94C87E0F';
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_POST, 1);
        ob_start();
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($handle);
        ob_end_clean();
        curl_close($handle);
        $this->jsonp(json_decode($response, true));
    }

    /*
     * @desc 点播文件上传之后云帆回调处理
     * @author 冯固
     * @date 2016-6-6
     * */
    public function orderFeedAction()
    {
        header('Content-type: application/json');
        $data_stream = file_get_contents('php://input', 'r');
        $data = json_decode($data_stream, true);
        if (!$data && $data['result'][0])
        {
            $ret = array('result'=>'retry'); //和云帆协商返回结果
            echo json_encode($ret);
            exit;
        }
        $url = $data['result'][0]['publish_url'];
        $user_token = $data['result'][0]['user_token'];
        $status = $data['result'][0]['status'];

        //ugc点播文件回传
        $token = D::redisKey('mobtoken', $user_token);
        $arr_data = json_decode(RedisIO::get($token), true);
        $arr_data['ex_video_url'] = $url;
        $arr_data['ugc_status'] = $status;
        RedisIO::set($token,json_encode($arr_data));
        $signupid = 0;
        if(array_key_exists('signupid',$arr_data))
            $signupid = $arr_data['signupid'];

        if ($signupid && $status == '1') {
            ActivitySignup::UpdateSignupData($signupid, '0', array('ex_vediourl' => $url));
        }
        echo json_encode(array('result'=>'success')); //返回处理结果
        die();
    }
    
    protected function _json($data, $code = 200, $msg = "success")
    {
        header('Content-type: application/json');
        $resp = json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        if($callback = Request::get('callback')){
            echo htmlspecialchars($callback) . "({$resp});";
        }else{
            echo $resp;
        }
        exit;
    }
 
  




}