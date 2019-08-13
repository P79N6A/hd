<?php

namespace GenialCloud\Auth;

class Signature {

    public static function MD5SimpleCheck($input, $data) {
        $timestamp = (string) $input['timestamp'];
        $input['key'] = substr($data['app_secret'],$timestamp[strlen($timestamp) - 1]);
        if(isset($input['client_id'])){
            $input['client_id'] = urlencode($input['client_id']);
        }
        $sign = $input['signature'];
        unset($input['signature']);
        ksort($input);
        $signature = self::buildQuery($input);
        return  $sign == sha1(base64_encode($signature));
    }

    public static function output($code, $msg) {
        return json_encode([
            'data' => [],
            'msg' => $code,
            'code' => $msg
        ]);
    }

    /**
     * http_build_query
     * @param array $input
     * @return string
     */
    public static function buildQuery(array $input) {
        $query = [];
        if(!empty($input)) {
            foreach($input as $k => $v) {
                $query[] = $k."=".$v;
            }
        }
        return implode("&", $query);
    }

}
