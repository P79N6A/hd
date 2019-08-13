<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;
use GenialCloud\Helper\Tree;


class DepartmentYear extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'department';
    }

    public function onConstruct() {
        //使用年会数据库链接
        $this->setConnectionService('db_year');
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'father_id', 'depth', 'sort',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'father_id', 'depth', 'sort',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'father_id', 'depth', 'sort',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'father_id' => Column::TYPE_INTEGER,
                'depth' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'father_id', 'depth', 'sort',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'father_id' => Column::BIND_PARAM_INT,
                'depth' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [

            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findPagination($channel_id) {
        return  self::query()->where('channel_id=' . $channel_id . ' and father_id = 0')->paginate(Department::PAGE_SIZE, 'Pagination');
    }

    public static function findDept() {
        return self::query()->where('channel_id=' . Session::get('user')->channel_id)->orderBy('sort desc')->execute()->toArray();
    }

    public static function findById($id) {
        $parameters = array();
        $parameters['conditions'] = "id=".$id;
        return self::findFirst($parameters);
    }
    
    public static function makeValidator($input) {
        $validator=Validator::make(
            $input, [
            'name' => "required",
            'father_id' =>'required'
        ], [
                'name.required' => '名称不能为空',
                'father_id.required' => '上级部门不能为空'
            ]
        );
        return $validator;
    }

    public static function getRootDepartments($channel_id) {
        $parameters = array();
        $parameters['conditions'] = " father_id = 0 and channel_id=".$channel_id;
        return  self::find($parameters);
    }


    public static function setRootDepartment($channel_id) {
        $root = new self();
        $root->channel_id = $channel_id;
        $root->name = $channel_id;
        $root->father_id = 0;
        $root->sort = 1;
        $root->save();
        return $root;
    }

    public function createDepartment($data) {
        $this->channel_id = $data['channel_id'];
        $this->name = $data['name'];
        $this->father_id = $data['father_id'];
        $this->depth = '1';
        $this->sort = $data['sort']?:0;
        if ($this->save()) {
            return true;
        }
        else {
            return false;
        }
    }

    public function deleteDepartment(){
        return $this->delete();
    }

    public function modifyDepartment($data) {
        if (isset($data['name']))
            $this->name = $data['name'];
        if (isset($data['father_id']))
            $this->father_id = $data['father_id'];
        if (isset($data['sort']))
            $this->sort = $data['sort'];
        if ($this->update()) {
            return true;
        }
        else {
            return false;
        }
    }

    private function setParent($d, $parents) {
        if($d->father_id) {
            $parents = $this->setParent(self::findById($d->father_id), $parents);
        }
        array_push($parents, $d);
        return $parents;
    }

    private function isParent($parent_id, $child_id) {
        $parents = array();
        $d = self::findById($parent_id);
        $illegal = false;
        $parents = $this->setParent($d, $parents);
        foreach ($parents as $value) {
            if($value->id == $child_id) $illegal = true;
        }
        return $illegal;
    }

    public function getParents() {
        $parents = array();
        $parents = $this->setParent($this, $parents);
        return $parents;
    }
    
    // 部门列表
    public static function listDepartment($channel_id) {
        $data = self::query()
                ->andCondition('channel_id', $channel_id)
                ->orderBy('sort desc')
                ->execute()
                ->toArray();
        $return = [];
        if(!empty($data)){
            $return = array_refine($data, 'id');
        }
        return $return;
    }

    // 部门树结构
    public static function getTree($channel_id) {
        $tree = new Tree();
        $data = self::listDepartment($channel_id);
        if(!empty($data)){
            foreach ($data as $v){
                $tree->setNode($v['id'], $v['father_id'], $v['name']);
            }
        }
        return $tree;
    }

    public static function findDepthChildren($tree, $model, &$data, $depth) {
        $children = $tree->getChildren($model['id']);
        $depth++;
        foreach ($children as $child) {
            $child['level'] = $depth;
            $child['has_child'] = !empty($tree->getChildren($child['id']));
            array_push($data, $child);
            self::findDepthChildren($tree, $child, $data, $depth);
        }
    }
}