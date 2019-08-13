<?php

class SnsTask extends Task {
    
    public function updatesnsAction(){
        $http1 = 'https://sns.cztv.com';
        $http2 = 'http://10.30.10.28:8080';
// 模拟登陆获取token
        $params = array(
            'login' => '1',
            'password' => 'root',
        );
        $url = $http1."/api/v2/iblue/auth/login";
        $request = F::curlRequest($url, 'post', $params);
        $data = json_decode($request, true);
// 1.清理圈子虚拟值 2.产生榜单
        $token = 'Bearer '.$data['access_token'];
      //  requests::set_header('Authorization', $token);
     //   $url = $http1."/admin/iblue/ranks/clear";
    //    $request = requests::get($url);
// 请求接口，刷新新的榜期

        $url = $http1."/admin/iblue/ranks/1?related=all";
        $ch = curl_init();
        curl_setopt ($ch,CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Authorization:'.$token));
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt ($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
        curl_setopt ($ch, CURLOPT_PROXY, CZTV_PROXY_IP); //代理服务器地址
        curl_setopt ($ch, CURLOPT_PROXYPORT, CZTV_PROXY_PORT); //代理服务器端口
        curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output, true);
        var_dump($output);
        echo date("Y-m-d H:i:s");
    }

}