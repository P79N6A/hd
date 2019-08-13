<?php
/**
 * Parcel 用于复杂的数据传递
 */

namespace GenialCloud\Support;


class Parcel {

    private $_container_ = [];

    private final function __construct() {
    }

    /**
     * @param array $data
     * @return Parcel
     */
    public final static function init(array $data) {
        $parcel = new self;
        $parcel->_container_ = $data;
        return $parcel;
    }

    public function __get($name) {
        if(isset($this->_container_[$name])) {
            return $this->_container_[$name];
        }
    }

    public function __set($name, $value) {
        $this->_container_[$name] = $value;
    }

    public function __isset($name) {
        return isset($this->_container_[$name]);
    }

    public function __unset($name) {
        unset($this->_container_[$name]);
    }

}