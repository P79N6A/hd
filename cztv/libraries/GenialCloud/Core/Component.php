<?php

namespace GenialCloud\Core;


trait Component {

    public function __construct($config = array()) {
        $this->parseConfig($config);
        $this->init();
    }

    protected function parseConfig($config) {
        if (!empty($config)) {
            foreach ($config as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    protected function init() {
    }

}