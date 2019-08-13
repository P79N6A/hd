<?php

class SobeyBaseController extends BaseController {

    public function initialize() {
        parent::initialize();
    }

    protected function _json($data, $code = 200, $msg = "success") {
        if (Request::get('debug')) {
            echo "<pre>";
            print_r($data);
            exit;
        }
        header('Content-type: application/json');
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }

}
