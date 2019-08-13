<?php

namespace GenialCloud\Helper;

class Tree {

    public $data = array();
    public $cateArray = array();
    public $meta = array();
    public $tree = array();

    function Tree() {
        
    }

    function setNode($id, $parent, $value) {
        $parent = $parent ? $parent : 0;
        $this->data[$id] = $value;
        $this->cateArray[$id] = $parent;
        $this->tree[$parent][] = $id;
    }

    function setMeta($id, $value) {
        $this->meta[$id] = $value;
    }

    function getMeta($id) {
        return $this->meta[$id];
    }

    function getChildsTree($id = 0) {
        $childs = array();
        foreach ($this->cateArray as $child => $parent) {
            if ($parent == $id) {
                $childs[$child] = $this->getChildsTree($child);
            }
        }
        return $childs;
    }

    function getChilds($id = 0) {
        $childArray = array();
        $childs = $this->getChild($id);
        foreach ($childs as $child) {
            $childArray[] = $child;
            $childArray = array_merge($childArray, $this->getChilds($child));
        }
        return $childArray;
    }

    function getChild($id) {
        return isset($this->tree[$id]) ? $this->tree[$id] : [] ;
    }

    function getNodeLever($id) {
        $parents = array();
        if (key_exists($this->cateArray[$id], $this->cateArray)) {
            $parents[] = $this->cateArray[$id];
            $parents = array_merge($parents, $this->getNodeLever($this->cateArray[$id]));
        }
        return $parents;
    }

    function getLayer($id, $preStr = '+') {
        return str_repeat($preStr, count($this->getNodeLever($id)));
    }

    function getValue($id) {
        return isset($this->data[$id]) ? $this->data[$id]: '';
    }

    public function getJsonTree($id = 0,$k_value='k',$k_name='n',$k_child='s') {
        $childs = array();
        foreach ($this->cateArray as $child => $parent) {
            if ($parent == $id) {
                $childs[] = array(
                    $k_value => $child,
                    $k_name => $this->getValue($child),
                    $k_child => $this->getJsonTree($child,$k_value,$k_name,$k_child)
                );
            }
        }
        return $childs;
    }

}