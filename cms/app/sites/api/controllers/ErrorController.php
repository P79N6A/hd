<?php


class ErrorController {

    public function indexAction() {
        $messages = [];
        if(!empty($e) && ($e instanceof \GenialCloud\Exceptions\HttpException)) {
            $messages[] = $e->getMessage();
        }
        header('Content-type: application/json');
        echo json_encode([
            'code' => 500,
            'msg' => 'System Error',
            'data' => $messages,
        ]);
        exit;
    }
}