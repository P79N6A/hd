<?php

class SendWxmcnTask extends Task {
    const WX_MCN_LAST_NUMBER = 'wx::mcn::last::number::';
    const APP_ID = '200681064';
    const APP_SECRET = 'A8yP6XAeT9F8Mmvr';
    const CACHR_KEY = 'qqKanDianAccessToken';
    const UIN = '3420289125';

    //从推广后台获取QQ看点，并推送
    public function qqkandianAction(){
		$json_data = F::curlProxyCli('http://10.30.138.33/data/zgltvseoqqkandian.json');
		$array_data = json_decode($json_data, true);
		if ($array_data['code'] == 0) {
            $access_token = self::getAccessToKen();
            if ($access_token == '') {
                echo 'access_token fail task over';exit;
            }

            $last_id = RedisIO::get(self::WX_MCN_LAST_NUMBER);
            for ($i = count($array_data['data']['videos'])-1; $i >= 0; $i--) {
                $last_id = $last_id?:0;
                $video = $array_data['data']['videos'][$i];
                $video['tag'] = $video['tags'];
                $video['puin'] = self::UIN;

                if ($video['vid'] <= $last_id) {
                    continue;
                }

                $post_msg = array(
                    'appid' => self::APP_ID,
                    'data' => $video
                );
                $callback_json = F::curlProxyCli('https://kdapi.mp.qq.com/video/sync?access_token='.$access_token, 'post', json_encode($post_msg));
                $callback_array = json_decode($callback_json, true);
                if ($callback_array['errcode'] != 0) {//发生错误情况
                    file_put_contents('qqkandian_fail.txt', $video['vid']." is error!errorcode is {$callback_array['errcode']}/n",FILE_APPEND);
                }else {
                    $last_id = $video['vid'];
                }
            }
            RedisIO::set(self::WX_MCN_LAST_NUMBER, $last_id);

        }
        echo 'qqKanDian task over';exit;
    }

    /**
     * 获取微信ToKen
     * @return String
     */
    protected function getAccessToKen()
    {
        $accessToken = RedisIO::get(self::CACHR_KEY.self::APP_ID);
        if ($accessToken == false) {
            //TODO 统一外网服务接口
            $urlAccessToken = 'https://api.mp.qq.com/cgi-bin/token?appid=' . self::APP_ID . '&secret=' . self::APP_SECRET;
            $jsonAccessToken = F::curlProxyCli($urlAccessToken);
            $dataAccessToken = json_decode($jsonAccessToken, true);
            if (isset($dataAccessToken['access_token']) && $dataAccessToken['access_token']) {
                $accessToken = $dataAccessToken['access_token'];
                RedisIO::set(self::CACHR_KEY.self::APP_ID, $accessToken, 7000);
            } else {
                $accessToken = '';
            }
        }
        return $accessToken;
    }


}



?>