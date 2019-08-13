<?php
/**
 * Created by PhpStorm.
 * User: sylar
 * Date: 2015/9/24
 * Time: 9:31
 */
class TaskTree {

    public $data = array();
    public $parent_ids = array();
    public $children = array();

    public function getChildren($id) {
        return isset($this->children[$id]) ? $this->children[$id] : [] ;
    }

    /**
     * @param $id
     * @return return the Task of id direct children.
     */
    public function getTaskChildren($id) {
        return $this->nodes[$id]->children;
    }

    function setNode($task) {
        $this->data[$task['id']] = $task;
        $this->parent_ids[$task['id']] = $task['father_id'];
        $this->children[$task['father_id']][] = $task;
    }

    public static function getTree($channel_id) {
        $tree = new TaskTree();
        $tasks = Tasks::listTask($channel_id);
        if (!empty($tasks)) {
            foreach ($tasks as $t) {
                $tree->setNode($t);
            }
        }
        return $tree;
    }

    public function setJsonNode($task, $select_id = 0, $id, $choice_self) {
        $tmp = [];
        $tmp['id'] = $task['id'];
        $tmp['text'] = $task['name'] . '(' . $task['id'] . ')';
        if (intval($task['id']) == intval($select_id)) {
            $tmp['state'] = array(
                'opened' => true,
                'selected' => true
            );
        }
        if (isset($this->children[$task['id']])) {
            $children = $this->children[$task['id']];
            $tmp['children'] = [];
            array_merge($tmp,$tmp['state']=array('opened' => 'true'));
            foreach ($children as $d) {
                if ($d['id'] == intval($id) && !$choice_self) {
                    continue;
                }
                $children = TaskTree::setJsonNode($d, $select_id, $id, $choice_self);
                if (!empty($children)) {
                    array_push($tmp['children'], $children);
                }
            }
        }
        return $tmp;
    }

    public function getTaskTreeJson($id = 0, $choice_self = false) {
        $jsonarr = array();
        $select_id = isset($this->parent_ids[$id]) ? $this->parent_ids[$id] : 0;
        if ($select_id==0 || $choice_self) {
            $select_id = $id;
        }
        $roots = $this->children[0];
        foreach($roots as $root) {
            if ($root['id'] == intval($id) && !$choice_self) {
                continue;
            }
            array_push($jsonarr, TaskTree::setJsonNode($root, $select_id, $id, $choice_self));
        }
        return $jsonarr;
    }
}