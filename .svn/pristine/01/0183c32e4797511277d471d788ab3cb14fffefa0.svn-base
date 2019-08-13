<?php

/**
 * Created by PhpStorm.
 * User: haiquan
 * Date: 2016/4/11
 * Time: 9:31
 */
class PrivateCategoryTree {
    public $data = array();
    public $parent_ids = array();
    public $children = array();

    private function __construct() {

    }

    public function setJsonNode($category, $select_id = 0, $id) {
        $tmp = [];
        $tmp['id'] = $category['id'];
        $tmp['text'] = $category['name'] . '(' . $category['id'] . ')';
        if (intval($category['id']) == intval($select_id)) {
            $tmp['state'] = array(
                'opened' => true,
                'selected' => true
            );
        }
        if (isset($this->children[$category['id']])) {
            $children = $this->children[$category['id']];
            $tmp['children'] = [];
            foreach ($children as $d) {
                if ($d['id'] == intval($id)) {
                    continue;
                }
                $children = PrivateCategoryTree::setJsonNode($d, $select_id, $id);
                if (!empty($children)) {
                    array_push($tmp['children'], $children);
                }
            }
        }
        return $tmp;
    }


    private function setNode($category) {
        $this->data[$category['id']] = $category;
        $this->parent_ids[$category['id']] = $category['father_id'];
        $this->children[$category['father_id']][] = $category;
    }

    public static function getCategoryTree($channel_id, $media_type) {
        $tree = new PrivateCategoryTree();
        $categories = PrivateCategory::listCategory($media_type, false, $channel_id);
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $tree->setNode($category);
            }
        }
        return $tree;
    }

    public function getChildren($id) {
        return isset($this->children[$id]) ? $this->children[$id] : [];
    }

    public function getCategoryTreeJson($id = 0) {
        $jsonarr = array();
        $select_id = isset($this->parent_ids[$id]) ? $this->parent_ids[$id] : 0;
        if ($select_id == 0) {
            $select_id = $id;
        }
        $roots = $this->children[0];
        foreach ($roots as $root) {
            if ($root['id'] == intval($id)) {
                continue;
            }
            array_push($jsonarr, PrivateCategoryTree::setJsonNode($root, $select_id, $id));
        }
        return $jsonarr;
    }

    public function getCategoryTreeJson2($id = 0, $select_ids) {
        $jsonarr = array();
        if (!empty($this->children)) {
            $roots = $this->children[0];
            foreach ($roots as $root) {
                if ($root['id'] == intval($id)) {
                    continue;
                }
                array_push($jsonarr, PrivateCategoryTree::setJsonNode2($root, $select_ids, $id));
            }
        }
        return $jsonarr;
    }

    public function setJsonNode2($category, $select_ids = 0, $id) {
        $tmp = [];
        $tmp['id'] = $category['id'];
        $tmp['text'] = $category['name'] . '(' . $category['id'] . ')';
        if (in_array(intval($category['id']), $select_ids)) {
            $tmp['state'] = array(
                'opened' => true,
                'selected' => true
            );
        }
        if (isset($this->children[$category['id']])) {
            $children = $this->children[$category['id']];
            $tmp['children'] = [];
            foreach ($children as $d) {
                if ($d['id'] == intval($id)) {
                    continue;
                }
                $children = PrivateCategoryTree::setJsonNode2($d, $select_ids, $id);
                if (!empty($children)) {
                    array_push($tmp['children'], $children);
                }
            }
        }
        return $tmp;
    }

}