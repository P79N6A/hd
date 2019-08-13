<?php

namespace GenialCloud\Support;

class Task extends \Phalcon\Di\Injectable {

    /**
     * Task constructor
     */
    public final function __construct() {
        $this->beforeRun();
    }

    public final function __destruct() {
        $this->afterRun();
    }

    protected function beforeRun() {

    }

    protected function afterRun() {

    }

}