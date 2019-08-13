<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;
use GenialCloud\Helper\Tree;


class Department extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'department';
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
    
    public static function apiGetDepartment($fatherId, $channelId){
    	$data = array();
    	$reData = self::apiGetDeptDatas($fatherId, $channelId);
    	if(isset($reData) && !empty($reData)) {
    		$deptData = self::setDatas($reData);
    		$data["total"] = count($deptData);
    		$data["list"] = $deptData;
    	}
    	return $data;
    }
    
    /**
     * 组装api请求返回的数据
     */
    public static function setDatas($data) {
    	$reData = array();
    	if(count($data) > 0) {
	    	foreach ($data as $k => $v) {
	    		$departmentArr = $v->department->toArray();
	    		$arr =  Admin::showDeptById($departmentArr['id']);
	    		$channel = $v->channel->toArray();
	    		$adminData = array();
	    		$adminData['branch_code'] = $departmentArr['id'];
	    		$adminData['branch_all_name'] = $arr[0] ? str_replace("-","/",$arr[0]) : "";
	    		$adminData['branch_name'] = $departmentArr['name'] ?: "";
	    		$adminData['channel_name'] = $channel['name'] ?: "";
	    		$reData[$k] = $adminData;
	    	}
    	}
    	return $reData;
    }
    
    /**
     * 
     * @param unknown $channelId
     * @param unknown $page
     * @param unknown $size
     * @return multitype:|unknown
     */
    public static function apiGetDeptDatas($fatherId, $channelId) {
    	$channel_id = $channelId;
    	$query = self::query()
    	->columns(array('Department.*','Channel.*'))
    	->leftJoin("Channel", "Department.channel_id = Channel.id")
    	->andWhere("Department.channel_id = {$channel_id}");
    	if($fatherId > -1) {
    		$query = $query->andWhere('father_id = '.$fatherId);
    	}
    	$query = $query->orderBy('Department.sort desc');
    	$query = $query->execute()->toArray();
    	return $query;
    }
    

    public static function findPagination($channel_id) {
        return Department::query()->where('channel_id=' . $channel_id . ' and father_id = 0')->order('sort desc')->paginate(Department::PAGE_SIZE, 'Pagination');
    }

    /**
     * 根据father_id查询部门信息
     * @param number $id
     * @return unknown
     */
    public static function findDept($id = 0) {
        $query = Department::query()
        			->where('channel_id=' . Session::get('user')->channel_id);
        if($id > -1) {
        	$query = $query->andWhere('father_id = '.$id);
        }
        $query = $query->orderBy('sort asc')->execute()->toArray();
        return $query;
    }
    
    public static function findById($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        return Department::findFirst($parameters);
    }

    public static function makeValidator($input) {
        $validator = Validator::make(
            $input, [
            'name' => "required",
            'father_id' => 'required'
        ], [
                'name.required' => '名称不能为空',
                'father_id.required' => '上级部门不能为空'
            ]
        );
        return $validator;
    }

    public static function getRootDepartments($channel_id) {
        $parameters = array();
        $parameters['conditions'] = " father_id = 0 and channel_id=" . $channel_id;
        return Department::find($parameters);
    }


    public static function setRootDepartment($channel_id) {
        $root = new Department();
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
        $this->sort = $data['sort'] ?: 0;
        if ($this->save()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteDepartment() {
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
        } else {
            return false;
        }
    }

    private function setParent($d, $parents) {
        if ($d->father_id) {
            $parents = $this->setParent(Department::findById($d->father_id), $parents);
        }
        array_push($parents, $d);
        return $parents;
    }

    private function isParent($parent_id, $child_id) {
        $parents = array();
        $d = Department::findById($parent_id);
        $illegal = false;
        $parents = $this->setParent($d, $parents);
        foreach ($parents as $value) {
            if ($value->id == $child_id) $illegal = true;
        }
        return $illegal;
    }

    public function getParents() {
        $parents = array();
        $parents = $this->setParent($this, $parents);
        return $parents;
    }

   /**
    * 查询father_id,id
    * @param unknown $id
    * @param unknown $query
    * @param unknown $queryId
    */ 
    public static function findFatherId($id,&$query,&$queryId) {
    	$data  = self::query()
    	->columns(array('id','father_id'))
    	->andWhere('id=' .$id)
    	->execute()
    	->toArray();
     	if(isset($data) && count($data) > 0) {
         	foreach ($data as $v) {
         		$fatherId = $v['father_id'];
         		//if($fatherId != '0') {
         			array_push($query, $fatherId);
         			array_push($queryId,$v['id']);
         			self::findFatherId($fatherId,$query,$queryId);
         		//}
         	}
         }
    }
    
    /**
     * 根据id 查询 farther_id
     * @param unknown $id 
     * @return multitype: 查询到的id数组
     */
    public static function findIdByFartherId($id,&$arrId) {
    	
    	$data  = Department::query()
    	->columns(array('id', 'father_id'))
    	->andWhere('father_id=' .$id)
    	->execute()
    	->toArray();
    	if(isset($data) && count($data) > 0) {
    		foreach ($data as $v) {
    			array_push($arrId, $v['id']);
    			self::findIdByFartherId($v['id'],$arrId);
    		}
    	}
    }
    
    /**
     * 根据本机部门id 查询上级所有部门名称
     * @param unknown $id
     * @param unknown $query
     * @param unknown $queryId
     */
    public static function findNameById($id,&$departmentName) {
    	$data  = self::query()
    	->columns(array('father_id','name'))
    	->andWhere('id=' .$id)
    	->execute()
    	->toArray();
    	if(isset($data) && count($data) > 0) {
    		foreach ($data as $v) {
    			$name = $v['name'];
    			//array_push($query, $name);
    			if(null == $departmentName) {
    				$departmentName = $name;
    			}else {
    				$departmentName = $name."-".$departmentName;
    			}
    			self::findNameById($v['father_id'],$departmentName);
    		}
    	}
    }
    
    /**
     * 返回部门名称
     * @param unknown $id
     * @return unknown
     */
    public static function findDeptName($id) {
    	$data = self::query()
	    	->columns(array('id','name'))
	    	->andWhere('id=' .$id)
	    	->first();
    	return $data;
    }
    
    // 部门列表
    public static function listDepartment($channel_id) {
        $data = self::query()
            ->andCondition('channel_id', $channel_id)
            ->orderBy('sort desc')
            ->execute()
            ->toArray();
        $return = [];
        if (!empty($data)) {
            $return = array_refine($data, 'id');
        }
        return $return;
    }

    // 部门树结构
    public static function getTree($channel_id) {
        $tree = new Tree();
        $data = self::listDepartment($channel_id);
        if (!empty($data)) {
            foreach ($data as $v) {
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
            Department::findDepthChildren($tree, $child, $data, $depth);
        }
    }
}