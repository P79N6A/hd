<?php

class SMSBaseController extends BaseController {
    public function initialize() {
        parent::initialize();
        View::disable();
    }


}