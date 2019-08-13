<?php

/**
 * Created by PhpStorm.
 * User: sylar
 * Date: 2015/9/24
 * Time: 9:31
 */
class DepartmentTree {

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
    public function getDepartmentChildren($id) {
        return $this->nodes[$id]->children;
    }

    function setNode($department) {
        $this->data[$department['id']] = $department;
        $this->parent_ids[$department['id']] = $department['father_id'];
        $this->children[$department['father_id']][] = $department;
    }

    public static function getTree($channel_id) {
        $tree = new DepartmentTree();
        $departments = Department::listDepartment($channel_id);
        if (!empty($departments)) {
            foreach ($departments as $department) {
                $tree->setNode($department);
            }
        }
        return $tree;
    }

   

    public function getDepartmentTreeJson($id = 0, $choice_self = false) {
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
            array_push($jsonarr, DepartmentTree::setJsonNode($root, $select_id, $id, $choice_self));
        }
        return $jsonarr;
    }
    
    public function setJsonNode($department, $select_id = 0, $id, $choice_self) {
    	$tmp = [];
    	$tmp['id'] = $department['id'];
    	$tmp['text'] = $department['name'] . '(' . $department['id'] . ')';
    	if (intval($department['id']) == intval($select_id)) {
    		$tmp['state'] = array(
    				'opened' => true,
    				'selected' => true
    		);
    	}
    	if (isset($this->children[$department['id']])) {
    		$children = $this->children[$department['id']];
    		$tmp['children'] = [];
    		array_merge($tmp, $tmp['state'] = array('opened' => 'true'));
    		foreach ($children as $d) {
    			if ($d['id'] == intval($id) && !$choice_self) {
    				continue;
    			}
    			$children = DepartmentTree::setJsonNode($d, $select_id, $id, $choice_self);
    			if (!empty($children)) {
    				array_push($tmp['children'], $children);
    			}
    		}
    	}
    	return $tmp;
    }
    
    public function getDepartmentTreeJson2($id = 0, $select_ids, $choice_self = false) {
    	$jsonarr = array();
    	if (!empty($this->children)) {
    		$roots = $this->children[0];
    		foreach ($roots as $root) {
    			if ($root['id'] == intval($id)) {
    				continue;
    			}
    			array_push($jsonarr, DepartmentTree::setJsonNode2($root, $select_ids, $id, $choice_self));
    		}
    	}
    	return $jsonarr;
    }
    
    public function setJsonNode2($department, $select_ids = 0, $id, $choice_self) {
    	$tmp = [];
    	$tmp['id'] = $department['id'];
    	$tmp['text'] = $department['name'] . '(' . $department['id'] . ')';
    	if (in_array(intval($department['id']), $select_ids)) {
    		$tmp['state'] = array(
    				'opened' => true,
    				'selected' => true
    		);
    	}
    	if (isset($this->children[$department['id']])) {
    		$children = $this->children[$department['id']];
    		$tmp['children'] = [];
    		foreach ($children as $d) {
    			if ($d['id'] == intval($id) && !$choice_self) {
    				continue;
    			}
    			$children = DepartmentTree::setJsonNode2($d, $select_ids, $id, $choice_self);
    			if (!empty($children)) {
    				array_push($tmp['children'], $children);
    			}
    		}
    	}
    	return $tmp;
    }
}