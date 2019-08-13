<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AdminExt extends Model {

    public function getSource() {
        return 'admin_ext';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'admin_id', 'pinyin', 'department', 'duty', 'sort', 'ugc_group_id', 'nick', 'sex', 'is_anchor', 'rtmpurl', 'playurl',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['admin_id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['pinyin', 'department', 'duty', 'sort', 'ugc_group_id', 'nick', 'sex', 'is_anchor', 'rtmpurl', 'playurl',],
            MetaData::MODELS_NOT_NULL => ['admin_id'],
            MetaData::MODELS_DATA_TYPES => [
                'admin_id' => Column::TYPE_INTEGER,
                'pinyin' => Column::TYPE_VARCHAR,
                'department' => Column::TYPE_VARCHAR,
                'duty' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
                'ugc_group_id' => Column::TYPE_INTEGER,
                'nick' => Column::TYPE_VARCHAR,
                'sex' => Column::TYPE_INTEGER,
                'is_anchor' => Column::TYPE_INTEGER,
                'rtmpurl' => Column::TYPE_VARCHAR,
                'playurl' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'admin_id', 'duty', 'sort', 'ugc_group_id', 'sex', 'is_anchor',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'admin_id' => Column::BIND_PARAM_INT,
                'pinyin' => Column::BIND_PARAM_STR,
                'department' => Column::BIND_PARAM_STR,
                'duty' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
                'ugc_group_id' => Column::BIND_PARAM_INT,
                'nick' => Column::BIND_PARAM_STR,
                'sex' => Column::BIND_PARAM_INT,
                'is_anchor' => Column::BIND_PARAM_INT,
                'rtmpurl' => Column::BIND_PARAM_STR,
                'playurl' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'sex' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function ext($admin_id) {
        return self::findFirst($admin_id);
    }

    public static function findByDuty($duty_id) {
        return AdminExt::query()
            ->andCondition('duty', $duty_id)
            ->execute()->toArray();
    }

    public static function findByDept($department_id) {
        return AdminExt::query()
            ->andWhere("department like '%\_$department_id\_%'")
            ->execute()->toArray();
    }

    public static function resetExt($admin, $input) {
        $ext = self::query()->andCondition('admin_id', $admin->id)->first();
        if (!$ext) {
            $adminExt = new self;
            $adminExt->save([
                'admin_id' => $admin->id,
            ]);
            $ext = self::query()->andCondition('admin_id', $admin->id)->first();
        }
        $temp = "";
        if(isset($input['dept_id']) && $input['dept_id']) {
        	$deptIdArr = explode(',', $input['dept_id']);
        	$temp = "";
        	foreach ($deptIdArr as $k => $v) {
        		if($k == count($deptIdArr)-1) {
        			$temp .= "_".$v."_";
        		}else {
        			$temp .= "_".$v."_,";
        		}
        	}
        	$input['dept_id'] = $temp;
        }else {
        	$input['dept_id'] = $adminext->department;
        }
        return $ext->update([
            'department' => $input['dept_id'],
            'duty' => isset($input['duty_id']) && $input['duty_id'] ? $input['duty_id'] : $ext->duty,
            'pinyin' => Cutf8py::encode($admin->name),
            'sort' => isset($input['sort']) ? $input['sort'] : 0,
            'nick' => isset($input['nick']) ? $input['nick'] : '',
            'sex' => isset($input['sex']) ? $input['sex'] : 1,
            'rtmpurl' => isset($input['rtmpurl']) ? $input['rtmpurl'] : '',
            'playurl' => isset($input['playurl']) ? $input['playurl'] : '',
        ]);
    }

    public function modifyExt($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    /*
     *
     * @param Array 员工组ID
     * @return Array 返回一个员工ID列表
     *
     * */
    public static function getAdminIdsByUgcGroup($group_ids) {
        $ret = [];
        $data = self::query()
            ->inWhere('ugc_group_id', $group_ids)
            ->columns('admin_id')
            ->execute()->toArray();
        if ($data) {
            foreach ($data as $v)
                $ret[] = $v['admin_id'];
        }
        return $ret;
    }

    /*
     * @desc
     *
     * */
    public static function findAnchor($conditions = []) {

        $channel_id = Session::get('user')->channel_id;
        $criteria = AdminExt::query();
        $criteria->Join('Admin', 'Admin.id = AdminExt.admin_id')
            ->Join('AdminGroup', 'AdminExt.ugc_group_id = AdminGroup.id')
            ->Join('UgcStream', 'UgcStream.admin_id = Admin.id')
            ->columns(array('Admin.*', 'AdminExt.*', 'AdminGroup.*', 'UgcStream.*'))
            ->where("AdminExt.ugc_group_id is not null")
            ->andWhere("Admin.channel_id = $channel_id");
        if(!empty($conditions))
        {
            foreach($conditions as $key=>$val)
            {
                $criteria->andWhere($val);
            }
        }
        return $criteria->order("AdminExt.admin_id DESC")->paginate(Admin::PAGE_SIZE, 'Pagination');
    }

    public static function findUnAnchor() {
        $channel_id = Session::get('user')->channel_id;
        $criteria = AdminExt::query();
        $criteria->join("Admin", "Admin.id = AdminExt.admin_id")
            ->where("AdminExt.ugc_group_id = 0")
            ->andWhere("Admin.channel_id = $channel_id")
            ->andWhere("Admin.status = '1'")
            ->columns(array("Admin.id", "Admin.name", "Admin.mobile"));
        return $criteria->execute();
    }
    
    public function getOneById($adminId) {
    	return self::query()
    	->andCondition('admin_id', $adminId)
    	->first();
    }

    public function updateData($data) {
    	return ($data->update()) ? true : false;
    }
    
    public function getMaxSortValue() {
    	return self::query()
    	->order("AdminExt.sort DESC")
    	->first();
    }
    
    /**
	 * 获取admin_ext表department字段所有数据
     */
    public function getDepartment() {
    	$query = self::query()
    	->columns(array('AdminExt.*'))
    	->execute();
    	return $query;
    }
    
    public function saveDepartment($data) {
    	$ext = self::query()->andCondition('admin_id', $data->admin_id)->first();
    	if($ext) {
	    	return $ext->update([
	    			'department' => $data->department,
	    			
	    			]);
    	}
    }
}