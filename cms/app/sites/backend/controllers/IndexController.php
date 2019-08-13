<?php

class IndexController extends \BackendBaseController {

    public function indexAction() {
        View::setMainView('layouts/iframe');
    }

    public function errorAction() {
    }

}