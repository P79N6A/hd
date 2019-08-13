<?php

class ErrorController extends \BackendBaseController {

    public $ignore = [
        'index'
    ];

    public function indexAction(\Exception $e = null) {
        $messages = [];
        if(!empty($e) && ($e instanceof \GenialCloud\Exceptions\HttpException)){
            $messages[] = $e->getMessage();
        }
        View::setVars(compact('messages'));
    }

}