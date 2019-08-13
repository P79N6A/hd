<?php

/**
 * @RoutePrefix("/curl")
 */
class CurlController extends ApiBaseController {

    /**
     * @Put("/")
     */
    public function indexAction() {
        $json = file_get_contents("php://input");
        $params = json_decode($json, true);
        $url = isset($params['url'])?$params['url']:'';

        if(!$url) {
            echo json_encode(array('error'=>'地址出错'));
            exit;
        }
        $method = isset($params['method'])?$params['method']:'GET';
        $args = isset($params['args'])?$params['args']:'';
        echo F::curlRequest($url, $method, $args);
        exit;
    }

}