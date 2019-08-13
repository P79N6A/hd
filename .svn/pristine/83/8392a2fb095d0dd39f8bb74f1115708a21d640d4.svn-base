<?php

/**
 * Created by PhpStorm.
 * User: sylar
 * Date: 2015/9/24
 * Time: 9:31
 */
class StaticFilesTree {

    public $data = array();
    public $parent_ids = array();
    public $children = array();

    public function getChildren($id) {
        return isset($this->children[$id]) ? $this->children[$id] : [];
    }

    /**
     * @param $id
     * @return return the Department of id direct children.
     */
    public function getFileChildren($id) {
        return $this->nodes[$id]->children;
    }

    function setNode($file) {
        $this->data[$file['id']] = $file;
        $this->parent_ids[$file['id']] = $file['father'];
        $this->children[$file['father']][] = $file;
    }

    public static function getTree($data_id) {
        $tree = new StaticFilesTree();
        $files = StaticFiles::getFilesByData($data_id);
        if (!empty($files)) {
            foreach ($files as $file) {
                $tree->setNode($file);
            }
        }
        return $tree;
    }

    public function getFileTreeJson($id = 0, $choice_self = false) {
        $jsonarr = array();
        $select_id = isset($this->parent_ids[$id]) ? $this->parent_ids[$id] : 0;
        if ($select_id == 0 || $choice_self) {
            $select_id = $id;
        }
        $roots = $this->children[0];
        foreach ($roots as $root) {
            if ($root['id'] == intval($id) && !$choice_self) {
                continue;
            }
            array_push($jsonarr, StaticFilesTree::setJsonNode($root, $select_id, $id, $choice_self));
        }
        return $jsonarr;
    }
    
    public function setJsonNode($file, $select_id = 0, $id, $choice_self) {
    	$tmp = [];
    	$tmp['id'] = $file['id'];
    	$tmp['text'] = $file['name'] ;
    	if (intval($file['id']) == intval($select_id)) {
    		$tmp['state'] = array(
    				'opened' => true,
    				'selected' => true
    		);
    	}
    	if (isset($this->children[$file['id']])) {
    		$children = $this->children[$file['id']];
    		$tmp['children'] = [];
    		array_merge($tmp, $tmp['state'] = array('opened' => 'true'));
    		foreach ($children as $d) {
    			if ($d['id'] == intval($id) && !$choice_self) {
    				continue;
    			}
    			$children = StaticFilesTree::setJsonNode($d, $select_id, $id, $choice_self);
    			if (!empty($children)) {
    				array_push($tmp['children'], $children);
    			}
    		}
    	}
    	return $tmp;
    }
}