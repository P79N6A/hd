<?php

/**
 *  员工管理
 *  model admin
 * @author     Haiquan Zhang
 * @created    2015-9-11
 *
 *  status 0:删除/1:正常/2:未激活
 */
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Admin extends Model {

	const UPDATETIME_REDIS_KEY = "admin::updatetime::channel_id=";
    const ALL = -1;
    const DELETE = 0;
    const ACCEPT = 1;
    const REJECT = 2;

    const PAGE_SIZE = 50;

    use GenialCloud\Support\RememberToken\InRedis;

    public function getSource() {
        return 'admin';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'is_admin', 'mobile', 'name', 'job_name', 'password', 'salt', 'logincount', 'last_time', 'avatar', 'remember_token', 'status', 'updated_at','created_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'is_admin', 'mobile', 'name', 'job_name', 'password', 'salt', 'logincount', 'last_time', 'avatar', 'remember_token', 'status','updated_at','created_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'is_admin', 'mobile', 'name', 'password', 'salt', 'logincount', 'last_time',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'is_admin' => Column::TYPE_INTEGER,
                'mobile' => Column::TYPE_VARCHAR,
                'name' => Column::TYPE_VARCHAR,
                'job_name' => Column::TYPE_VARCHAR,
                'password' => Column::TYPE_VARCHAR,
                'salt' => Column::TYPE_VARCHAR,
                'logincount' => Column::TYPE_INTEGER,
                'last_time' => Column::TYPE_INTEGER,
                'avatar' => Column::TYPE_VARCHAR,
                'remember_token' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'is_admin', 'logincount', 'last_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'is_admin' => Column::BIND_PARAM_INT,
                'mobile' => Column::BIND_PARAM_STR,
                'name' => Column::BIND_PARAM_STR,
                'job_name' => Column::BIND_PARAM_STR,
                'password' => Column::BIND_PARAM_STR,
                'salt' => Column::BIND_PARAM_STR,
                'logincount' => Column::BIND_PARAM_INT,
                'last_time' => Column::BIND_PARAM_INT,
                'avatar' => Column::BIND_PARAM_STR,
                'remember_token' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'is_admin' => '0', 'logincount' => '0', 'last_time' => '0',
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    
    public static function apiGetAdminDatas($channelId, $page, $size,$categoryId=null) {
    	$channel_id = $channelId;
    	$page = (int)$page;
    	$size = (int)$size;
    	if ($page < 1 || $size < 1) {
    		return [];
    	}
    	
    	$query = self::query()
            ->columns(array('AdminExt.*', 'Admin.*', 'Duty.*'))
            ->leftJoin("AdminExt", "AdminExt.admin_id=Admin.id")
            ->leftJoin("Duty", "Duty.id=AdminExt.duty")
            ->andWhere("Admin.channel_id={$channel_id}")
		    ->orderBy('AdminExt.sort desc, FIELD(Admin.status, 1, 2, 0), Admin.id');
    	if($categoryId){
    	    $query = $query->leftJoin("CategoryAuth","CategoryAuth.user_id = Admin.id")
                ->andWhere("CategoryAuth.category_id = {$categoryId}");
        }
    	$query = $query->paginate($size, 'SmartyPagination', $page);
    	return $query;
    } 
    
    public static function getAdminbyChannelid($channel_id,$categoryId=null) {
    	$query = self::query()
	    	->columns(array('AdminExt.*'))
	    	->leftJoin("AdminExt", "AdminExt.admin_id=Admin.id")
	    	->leftJoin("Duty", "Duty.id=AdminExt.duty")
	    	->andWhere("Admin.channel_id={$channel_id}");
    	if($categoryId){
            $query = $query->leftJoin("CategoryAuth","CategoryAuth.user_id = Admin.id")
                ->andWhere("CategoryAuth.category_id = {$categoryId}");
        }
    	$query = $query
    		->execute()
    		->toArray();
    	return $query;
    }
    
    public static function apiGetAdmin($channelId, $page, $size = 20){
    	$data = array();
    	$reData = self::apiGetAdminDatas($channelId,$page,$size);
	    if(isset($reData) && !empty($reData)) {
	    	$data["total"] = count(self::getAdminbyChannelid($channelId));
	    	$data["list"] = self::setDatas($reData);
	    }
	    return $data;
    }

    public static function apiSearchMembers($search, $conditionData, $channelId, $contact, $size, $page) {
    	$searchData = array();
    	if($size >0 && $page > 0) {
	    	$reData = self::search($search, $conditionData, $contact, $channelId, true, $size, $page);
	    	if(isset($reData) && !empty($reData)) {
	    		$searchData["total"] = count(self::search($search, $conditionData, $contact, $channelId, true, 0, 0));
	    		$searchData["list"] = self::setDatas($reData);
	    	}
    	}
    	return $searchData;
    }
    
    /**
	 * 组装api请求返回的数据
     */
    public static function setDatas($data) {
    	$arrDeptName = self::showDepartMenent($data);			// 部门列 显示方式  “A部门/a-1部门/a-1-1部门”
    	$reData = array();
    	foreach ($data->models as $k => $v) {
    		$adminExtArr = $v->adminExt->toArray();
    		$adminArr = $v->admin->toArray();
    		$deptIds = $v->adminExt->department;
    		$name = "";
    		$departmentId = 0;
    		$arrDeptIds = explode(',', $deptIds);
    		if(count($arrDeptIds) > 0 ) {
	    		$deptId = str_replace("_","",$arrDeptIds[0]);
	    		if($deptId != "") {
	    			$name = Department::findDeptName($deptId)->name;
	    			$departmentId = Department::findDeptName($deptId)->id;
	    		}
    		}
    		$arrDeptName[$k] = str_replace("，",",",$arrDeptName[$k]);
    		$arrDeptNames = explode(',',$arrDeptName[$k]);
    		$adminData = array();
    		$adminData['id'] = (int)$adminArr['id'];
    		$adminData['duty_name'] = $adminArr['job_name'] ?: "";
    		$adminData['name'] = $adminArr['name'] ?: "";
    		$adminData['mobile'] = $adminArr['mobile'] ?: "";
    		$adminData['branch_all_name'] = $arrDeptNames[0] ? str_replace("-","/",$arrDeptNames[0]) : "";
    		$adminData['branch_name'] = $name ?: "";
    		$adminData['branch_code'] = $departmentId ?: "";
    		$adminData['admin_status'] = (int)$adminArr['status'];
    		$adminData['admin_sort'] = (int)$adminExtArr['sort'];
    		$adminData['avatar'] = cdn_url('image',$adminArr['avatar']) ?: "";
    		$reData[$k] = $adminData;
    		
    	}
    	return $reData;
    }
    
    /**
	 * 根据部门id 显示方式  “A部门/a-1部门/a-1-1部门”
     */
    public static function showDeptById($deptIds) {
    	$arrDeptName = array();
    	if($deptIds != null && !empty($deptIds)) {
    		$arrDeptIds = explode(',', $deptIds);
    		if(count($arrDeptIds) > 0 ) {
    			$departmentName = null;
    			// 组合所有部门
    			for ($i =0; $i < count($arrDeptIds); $i++) {
    				$name = "";
    				$arrDeptIds[$i] = str_replace("_","",$arrDeptIds[$i]);
    				Department::findNameById($arrDeptIds[$i], $name);
    				if($i == count($arrDeptIds) -1) {
    					$departmentName .= $name;
    				}else {
    					$departmentName .= $name."，";
    				}
    			}
    			array_push($arrDeptName, $departmentName);
    		}else {
    			array_push($arrDeptName, "");
    		}
    	}else {
    		array_push($arrDeptName, "");
    	}
    	return $arrDeptName;
    }
    
    /**
     *	部门列 显示方式  “A部门/a-1部门/a-1-1部门”
     */
    public static function showDepartMenent($data) {
    	 
    	$arrDeptName = array();
    	if($data&&isset($data->models));
    	$models = $data->models;
    	if(!empty($models)) {
    		foreach ($models as $k => $v) {
    			//$departmentId = $v->department->id;
    			$deptIds = $v->adminExt->department;
    			//$deptIds = $v['department'];
	    		if($deptIds != null && !empty($deptIds)) {
		    		$arrDeptIds = explode(',', $deptIds);
		    		if(count($arrDeptIds) > 0 ) {
		    			$departmentName = null;
		    			// 组合所有部门
		    			for ($i =0; $i < count($arrDeptIds); $i++) {
		    				$name = "";
		    				$arrDeptIds[$i] = str_replace("_","",$arrDeptIds[$i]);
		    				Department::findNameById($arrDeptIds[$i], $name);
		    				if($i == count($arrDeptIds) -1) {
		    					$departmentName .= $name;
		    				}else {
		    					$departmentName .= $name."，";
		    				}
		    			}
		    			array_push($arrDeptName, $departmentName);
		    		}else {
		    			array_push($arrDeptName, "");
		    		}
		    	}else {
		    		array_push($arrDeptName, "");
		    	}
    		}
    	}
    	return $arrDeptName;
    }
    
    public static function findAll() {
        $channel_id = Session::get('user')->channel_id;
        $admin_id = (int)Session::get("user")->id;
        $query = Admin::query()
            ->columns(array('AdminExt.*', 'Admin.*', 'Department.*', 'Duty.*'))
            ->leftJoin("AdminExt", "AdminExt.admin_id=Admin.id")
            ->leftJoin("Department", "Department.id=AdminExt.department")
            ->leftJoin("Duty", "Duty.id=AdminExt.duty")
          	// ->leftJoin("AdminContact","AdminContact.contact_id = Admin.id")
            ->andWhere("Admin.channel_id={$channel_id}");
        	//->orWhere("AdminContact.admin_id = {$admin_id}");
        return $query
            ->orderBy('AdminExt.sort desc, Department.sort desc, FIELD(Admin.status, 1, 2, 0), Admin.id')
            ->paginate(Admin::PAGE_SIZE, 'Pagination');
    }

    public static function getAllAdminIds() {
        $channel_id = Session::get('user')->channel_id;
        $admins = Admin::query()->columns(array('id'))->andWhere("Admin.channel_id={$channel_id}")->execute()->toArray();
        $adminstr = "";
        foreach($admins as $a) {
            if($adminstr != "") $adminstr .= ",";
            $adminstr .= $a['id'];
        }
        return ($adminstr=="")?'0':$adminstr;
    }

    public static function findMaster() {
        return self::query()->andCondition('is_admin', 1)->paginate(Admin::PAGE_SIZE, 'Pagination');
    }

    public static function search($search, $conditionData, $contact, $channel_id , $isApi = false, $size = 0, $page = 0) {
    	$keyword = $search['keyword'];
        $status = $conditionData['status'] ?: '';
        $pinyin = $conditionData['pinyin'] ?: '';
        $department = $conditionData['department'] ?: '';
        $contactData = $conditionData['contacts'] ?: '';
        $contactStr = !empty($contact) ? implode(",", $contact) : "-1";
       
        $condition = "";
        $query = Admin::query()
            ->columns(array('AdminExt.*', 'Admin.*', 'Department.*', 'Duty.*'))
            ->leftJoin("AdminExt", "AdminExt.admin_id=Admin.id")
            ->leftJoin("Department", "Department.id=AdminExt.department")
            ->leftJoin("Duty", "Duty.id=AdminExt.duty");
        if ($keyword) {
            $condition = "(AdminExt.pinyin like '%$keyword%' or Admin.name like '%$keyword%' or Department.name like '%$keyword%' or Duty.name like '%$keyword%' or Admin.mobile like '%$keyword%') and ";
        }
        if ($status !== "") {
            $query = $query->andWhere('Admin.status=' . $status);
        }
        if ($pinyin !== "") {
            $pinyin = strtolower($pinyin);
            $query = $query->andWhere(" AdminExt.pinyin like '{$pinyin}%'");
        }
        if ($department !== "" ) {
            $query = $query->andWhere("AdminExt.department like '%\_{$department}\_%'");
            self::findByFartherID($department,$query);
        }
       	$query = $query->andWhere($condition . "Admin.channel_id=" . $channel_id);
        if($contactData == "1") {
        	$query = $query->andWhere("Admin.id in ( {$contactStr} )");
        }else if($contactData == "2") {
        	$query = $query->andWhere("Admin.id NOT in ( {$contactStr} )");
        }
        if($isApi) {
        	if($size == 0 && $page == 0) {
        		return $query->execute()->toArray();
        	}
        	return $query->orderBy('AdminExt.sort desc, Department.sort desc, FIELD(Admin.status, 1, 2, 0), Admin.id')
            	->paginate($size, 'SmartyPagination', $page);
        }else {
        	return $query->orderBy('AdminExt.sort desc, Department.sort desc, FIELD(Admin.status, 1, 2, 0), Admin.id')
            	->paginate(Admin::PAGE_SIZE, 'Pagination');
        }
    }

	/**
	 *	根据department表中的fartherId查找id
	 */
    public static function findByFartherID($id,$query) {

    	$data  = Department::query()
    	->columns(array('id', 'father_id'))
    	->andWhere('father_id=' .$id)
    	->execute()
    	->toArray();
    	if(isset($data) && count($data) > 0) {
    		foreach ($data as $v) {
    			$query = $query->orWhere("AdminExt.department like '%\_{$v['id']}\_%'");
    			self::findByFartherID($v['id'],$query);
    		}
    	}
    }
    
    public static function getAdminArr() {
        //if(!$channel_id) $channel_id = Session::get("user")->channel_id;
        $admins = Admin::find();
        $adminarr = [];
        foreach ($admins as $a) {
            $adminarr[$a->id] = $a;
        }
        return $adminarr;
    }

    public static function getOne($adminid) {
        return self::query()
            ->andCondition('channel_id', Session::get('user')->channel_id)
            ->andCondition('id', $adminid)
            ->first();
    }

    public function getExtinfo() {
        $parameters = array();
        $parameters['conditions'] = "admin_id=" . $this->id;
        return AdminExt::findFirst($parameters);
    }

    public static function simpleValidator($input) {//编辑时校验
        return Validator::make(
            $input, [
            'name' => 'required|min:2|max:99',
            'mobile' => "required|size:11",
        ], [
                'name.required' => '昵称必填',
                'name.min' => '昵称最短2字符',
                'name.max' => '昵称最长99字符',
                'mobile.required' => '手机号必填'
            ]
        );
    }

    public static function makeValidator($input, $excluded_id = 0) {
        $channel_id = Session::get("user")->channel_id;
        Validator::extend('mobile', function ($attribute, $value, $parameters) {
            return \GenialCloud\Helper\Validator::cnMobile($attribute, $value, $parameters);
        });
        if (isset($input['new_password'])) {
            Validator::extend('is_security', function ($attribute, $value, $parameters) use ($input) {
                return self::isSecurity($input['new_password']);
            });
        } else {
            Validator::extend('is_security', function ($attribute, $value, $parameters) use ($input) {
                return self::isSecurity($input['password']);
            });
        }

        $rules = [
            'mobile' => 'required|mobile|unique:admin,mobile,' . $excluded_id . ',id,channel_id,' . $input['channel_id'],
            'password' => 'min:8|max:20',
            'name' => 'required',
            'dept_id' => 'required'

        ];
        return Validator::make($input, $rules, [
                'mobile.required' => Lang::_('手机号码必须填写'),
                'mobile.mobile' => Lang::_('必须是合法的手机号码'),
                'mobile.unique' => Lang::_('手机号码已经存在'),
                'password.min' => Lang::_('密码大于8位'),
                'password.max' => Lang::_('密码小于20位'),
                'name.required' => Lang::_('请填写名字'),
        		'dept_id.required' => Lang::_('请选择部门'),
            ]
        );
    }

    public static function masterValidator($input, $excluded_id = 0) {
        list($validator, $msg) = Admin::commonValidator();
        $validator['mobile'] = "required|size:11|unique:admin,mobile,{$excluded_id},id,channel_id,{$input['channel_id']}";
        $msg['mobile.required'] = '手机号码必填';
        $msg['mobile.unique'] = '手机号已经存在';
        return Validator::make(
            $input, $validator, $msg
        );
    }

    public static function commonValidator() {
        return [
            [
                'name' => 'required|min:2|max:99',
                'password' => 'min:6',
                'channel_id' => "required",
            ],
            ['name.required' => '昵称必填',
                'name.min' => '昵称最短2字符',
                'name.max' => '昵称最长99字符',
                'password.min' => '密码最短6字符',
            ]
        ];
    }

    public function getChannel() {
        $channel = Channel::getOneChannel($this->channel_id);
        if ($this->channel_id == 0) {
            return "System";
        } else {
            return $channel->name;
        }
    }

    public function createAdmin($data) {
        $this->assign($data);
        $channel_id = Session::get('user')->channel_id;
        $this->channel_id = isset($data['channel_id']) && $channel_id == 0 ? $data['channel_id'] : $channel_id;
        $this->salt = str_random();
        $this->status = isset($data['status']) ? $data['status'] : 1;
        $this->avatar = isset($data['avatar']) ? $data['avatar'] : null;
        $messages = [];
        if (isset($data['password']) && $data['password']) {
            $this->password = Hash::encrypt($data['password'], $this->salt);
        }
        // 创建频道为0的员工，默认就是is_admin =1
        if ($this->channel_id == 0) {
            $this->is_admin = 1;
        }
        $this->created_at = time();
		$this->updated_at = self::setUpdateTime();
        if ($this->save()) {
            $this->setAdminExt($data);
            // 暂时取消个人简历等功能 by 张海盼
//            $vitae = Vitae::getOneByAdmin($this->id);
//            $vitae->modifyVitae($data);
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }
    
    
   public static function setUpdateTime() {
    	$nowtime = time();
    	$channleId = Session::get('user')->channel_id ?: 0;
    	RedisIO::set(self::UPDATETIME_REDIS_KEY.$channleId, $nowtime);
    	return $nowtime;
    } 

    public function updateAdmin($model, array $data, array $fields) {
    	
        $filterData = [];
        if (!empty($fields)) {
            foreach ($fields as $k => $v) {
                $filterData[$v] = $data[$v];
                if ($v == 'password') {
                    if (!empty($data[$v])) {
                        $data[$v] = Hash::encrypt($data['password'], $model->salt);
                        $filterData[$v] = $data[$v];
                    } else {
                        unset($filterData[$v]);
                        unset($fields[$k]);
                    }
                }
            }
        }
        $filterData['updated_at'] = self::setUpdateTime();
        return $model->update($filterData, $fields);
    }

    public function deleteAdmin() {
    	$this->updated_at = self::setUpdateTime();
        $this->status = 0;
        $this->save();
    }

    public function activeAdmin() {
    	$this->updated_at = self::setUpdateTime();
        $this->status = 2;
        $this->save();
    }

    public function modifyAdmin($data) {
    	$this->updated_at = self::setUpdateTime();
        if (isset($data['name']))
            $this->name = $data['name'];
        if (isset($data['avatar']) && $data['avatar'])
            $this->avatar = $data['avatar'];
        if (isset($data['password']) && $data['password']) {
            if ('none' == $this->salt) {
                $this->salt = str_random();
            }
            $this->password = Hash::encrypt($data['password'], $this->salt);
        }
        if (isset($data['roleid']))
            $this->setRole(implode(',', $data['roleid']));
        if (isset($data['element_id']))
            $this->setAuthAssign(implode(',', $data['element_id']));

        if ($this->save()) {
            $this->setAdminExt($data);
            $msg = '成功';
        } else {
            foreach ($this->getMessages() as $m) {
                $msg = $m->getMessage();
            }
        }
        return $msg;
    }

    private function setAdminExt($data) {
    	
        $adminext = AdminExt::findFirst(array('conditions' => 'admin_id=' . $this->id));
        if (!$adminext) {
        	$adminext = new AdminExt();
            $adminext->admin_id = $this->id;
        }
        
        if(isset($data['dept_id']) && $data['dept_id']) {
        	$deptIdArr = explode(',', $data['dept_id']);
        	$temp = "";
        	foreach ($deptIdArr as $k => $v) {
        		if($k == count($deptIdArr)-1) {
        			$temp .= "_".$v."_";
        		}else {
        			$temp .= "_".$v."_,";
        		}
        	}
        	$data['dept_id'] = $temp;
        }else {
        	$data['dept_id'] = "";
        }
        $adminext->department = $data['dept_id'];
        $adminext->duty = (isset($data['duty_id']) && $data['duty_id']) ? $data['duty_id'] : $adminext->duty;
        $adminext->pinyin = Cutf8py::encode($data['name']);
        //$adminext->sort = isset($data['sort']) ? $data['sort'] : 0;
        $adminext->sort = $adminext->getMaxSortValue()->sort +1;
        $adminext->ugc_group_id = isset($data['ugc_group_id']) ? $data['ugc_group_id'] : 0;
        $adminext->save();
    }
    
    public function updateData($data) {
    	return ($data->update()) ? true : false;
    }

    /**
     * 频道过滤
     */
    public function selectChannel($parameters, $channel_id) {
        if ($channel_id) {
            $parameters['conditions'] .= " and channel_id = " . $channel_id;
        }
        return $parameters;
    }

    public function getAdmins() {
        $parameters = array();
        $parameters['conditions'] = "1=1 ";
        $parameters = $this->selectChannel($parameters, $this->curr_channel);
        $parameters['order'] = "id desc";
        $branchs = AuthRole::find($parameters);
        return $branchs;
    }

    /**
     * 单纯修改密码和头像,通过手机查找用户,修改上次登入时间
     * @author Zhangyichi
     * @param $data
     * @return bool
     */
    public function changePassword($data) {
        $this->password = $data['new_password'];
        return $this->update();
    }

    public function changeAvatar($data) {
        $this->avatar = $data['avatar'];
        return ($this->update()) ? true : false;
    }

    public static function isSecurity($value) {
        $v = 0;
        if (preg_match('/[a-zA-Z]/i', $value)) {
            $v++;
        }
        if (preg_match('/[0-9]/i', $value)) {
            $v++;
        }
        if (preg_match('/(.[^a-z0-9])/i', $value)) {
            $v++;
        }
        if ($v > 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function initSecurityValidator() {

        Validator::extend('is_security', function ($attribute, $value, $parameters) {
            if (self::isSecurity($value)) {
                return true;
            } else {
                return false;
            }
        });

    }

    public static function changeValidator($input) {
        self::initSecurityValidator();
        $rules = [
            'old_password' => "required",
            'new_password' => 'required|min:8|max:20|is_security',
            'confirm_password' => "required|same:new_password",
        ];
        return Validator::make($input, $rules, [
                'old_password.required' => Lang::_('旧密码必须填写'),
                'new_password.required' => Lang::_('新密码必须填写'),
                'new_password.min' => Lang::_('密码不能小于8位'),
                'new_password.max' => Lang::_('密码不能超过20位'),
                'new_password.is_security' => Lang::_('密码太简单，请由数字,字母或者符号组成'),
                'confirm_password.required' => Lang::_('请填写密码确认'),
                'confirm_password.same' => Lang::_('密码确认必须和密码相同'),

            ]
        );
    }

    public static function findByMobile($mobile, $channel_id = 0) {
        $query = Admin::query()
            ->andCondition('mobile', $mobile);
        if ($channel_id) {
            $query->andCondition('channel_id', $channel_id);
        }
        return $query->first();
    }

    public static function setLastTime($id, $last_time) {
        $admin = Admin::getOne($id);
        $admin->last_time = $last_time;
        $admin->logincount = $admin->logincount + 1;
        return ($admin->update()) ? true : false;
    }

    /**
     * 查找一个手机号的所有频道
     * @author Zhangyichi
     * @param $mobile
     * @return array
     */
    public static function findAllByMobile($mobile) {
        return Admin::query()
            ->andCondition('mobile', $mobile)
            ->execute();
    }

    public static function findAllByChannel($channel) {
        $admin = Admin::query()
            ->where("channel_id = '{$channel}'")->execute()->toarray();
        return $admin;
    }

    public function activation($new_password) {
        $this->password = $new_password;
        $this->status = 1;
        return $this->update();
    }

    public static function resetValidator($type, $data) {
        if (!in_array($type, ['reset'])) {
            throw new \GenialCloud\Exceptions\ValidationException('invalid validation type: ' . $type);
        }

        self::initSecurityValidator();
        Validator::extend('mobile', function ($attribute, $value, $parameters) {
            return \GenialCloud\Helper\Validator::cnMobile($attribute, $value, $parameters);
        });
        Validator::extend('mobile_exists', function ($attribute, $value, $parameters) use ($data) {
            if (self::findByMobile($value, $data['channel_id'])) {
                return true;
            } else {
                return false;
            }
        });

        $rules = [];
        switch ($type) {
            case 'reset':
                $rules = [
                    'mobile' => 'required|mobile|mobile_exists',
                    'new_password' => 'required|min:8',
                    'confirm_password' => 'same:new_password',
                    'verifycode' => 'required|min:4|max:4'
                ];
                break;
        }
        return Validator::make($data, $rules, [
            'mobile.required' => Lang::_('请输入手机号码'),
            'mobile.mobile' => Lang::_('请输入正确的手机号码'),
            'mobile.mobile_exists' => Lang::_('不存在这个手机号 联系管理员'),
            'new_password.required' => Lang::_('请填写密码'),
            //'new_password.is_security' => Lang::_('密码太简单，请由数字,字母或者符号组成'),
        	'new_password.min' => Lang::_('密码必须等于或大于8位'),
        	//'new_password.max' => Lang::_('密码必须小于或等于20位'),
            'confirm_password.same' => Lang::_('两次输入密码不相同'),
            'verifycode.required' => Lang::_('请填写验证码'),
            'verifycode.min' => Lang::_('验证码必须是4位'),
            'verifycode.max' => Lang::_('验证码必须是4位'),
        ]);
    }


//    public static function resetValidator($input) {
//        $validator = Validator::make(
//                        $input, [
//                    'mobile' => 'required|digits:11',
//                    'verifycode' => 'required|min:4|max:4',
//                    'new_password' => 'required|min:8|max:20',
//                    'confirm_password' => "required|same:new_password",
//                        ], [
//                    'mobile.required' => '新密码必填',
//                    'mobile.digits' => '手机号码格式不正确',
//                    'verifycode.required' => '验证码必填',
//                    'verifycode.min' => '验证码格式不正确',
//                    'verifycode.max' => '验证码格式不正确',
//                    'new_password.required' => '新密码必填',
//                    'new_password.min' => '新密码格式不正确',
//                    'new_password.max' => '新密码格式不正确',
//                    'confirm_password.required' => '确认密码必填',
//                    'confirm_password.same' => '两次密码输入不同',
//                        ]
//        );
//        return $validator;
//    }

    public static function loginTimes($limit = 3) {
        $key = 'login_fail';
        $times = (int)Session::get($key);
        return $times >= $limit;
    }

    public static function addLoginTimes() {
        $key = 'login_fail';
        $times = Session::get($key) ?: 0;
        Session::set($key, (int)$times + 1);
    }

    public function getCheckedRole() {
        $assignrole = AuthAssign::find(array('conditions' => 'type = 1 and user_id=' . $this->id . ' and channel_id=' . $this->channel_id));
        $checked_role = "";
        $fixed_element = "";
        foreach ($assignrole as $e) {
            $checked_role .= ("" == $checked_role) ? $e->element_id : ',' . $e->element_id;
        }
        return explode(',', $checked_role);
    }

    public function setRole($roles) {
        $checked_role = $this->getCheckedRole();
        $role_array = explode(',', $roles);
        //新增角色
        foreach ($role_array as $v) {
            if ($v && !in_array($v, $checked_role)) {
                $authassign = new AuthAssign();
                $authassign->element_id = $v;
                $authassign->type = 1;
                $authassign->user_id = $this->id;
                $authassign->channel_id = $this->channel_id;
                $authassign->save();
            }
        }
        //删除角色
        foreach ($checked_role as $v) {
            if ($v && !in_array($v, $role_array)) {
                $assign = AuthAssign::findFirst(array('conditions' => 'element_id=' . $v . ' and type = 1 and user_id=' . $this->id . ' and channel_id=' . $this->channel_id));
                $assign->delete();
            }
        }
    }

    public function setAuthAssign($element) {
        list($checked_element, $fixed_element) = AuthAssign::getAuth($this);

        $element_array = explode(',', $element);

        //新增权限
        foreach ($element_array as $v) {
            if ($v && !in_array($v, $checked_element)) {
                $authassign = new AuthAssign();
                $authassign->element_id = $v;
                $authassign->type = 0;
                $authassign->user_id = $this->id;
                $authassign->channel_id = $this->channel_id;
                $authassign->save();
            }
        }
        //删除权限
        foreach ($checked_element as $v) {
            if ($v && !in_array($v, $element_array)) {
                $assign = AuthAssign::findFirst(array('conditions' => 'element_id=' . $v . ' and type = 0 and user_id=' . $this->id . ' and channel_id=' . $this->channel_id));
                if ($assign) $assign->delete();
            }
        }
        //删除角色已含的权限
        foreach ($checked_element as $v) {
            if ($v && in_array($v, $fixed_element)) {
                $assign = AuthAssign::findFirst(array('conditions' => 'element_id=' . $v . ' and type = 0 and user_id=' . $this->id . ' and channel_id=' . $this->channel_id));
                if ($assign) $assign->delete();
            }
        }
    }

    public static function allName() {
    	$channel_id = Session::get('user')->channel_id;
        return self::query()
        ->where('channel_id='.$channel_id)
        ->execute()->toarray();
    }

    /*
    * @desc 返回未锁定的员工键值数组
    * @param adminids Array
    * @return Array 键值数组
    *
    * */
    public static function getUnLockAdminKVList($adminids) {
        $channel_id = Session::get('user')->channel_id;
        return self::query()
            ->andWhere('status = 1 and channel_id='.$channel_id)
            ->inWhere('id', $adminids)
            ->columns(array('id', 'name'))
            ->execute()
            ->toArray();
    }

    /**
     * @param $channel_id
     * @param $mobile
     * @return bool|array
     */
    public static function apiGetAdminByMobile($channel_id, $mobile) {
        $rs = self::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('mobile', $mobile)
            ->first();
        return $rs ? $rs->toArray() : false;
    }

    public static function getAdmin($admin_id) {
        return self::findFirst($admin_id);
    }

    /**
     * @param $id
     * @return array
     */
    public static function apiGetAdminInfo($id) {
        $user = self::findFirst($id)->toArray();
        return $user;
    }

    /*
     * @desc 用户手机获取频道信息
     * @param mob 用户手机号码
     * @author 冯固
     * @date 2016-6-14
     * */
    public static function apiGetAdminInfoByMob($mob) {
        $user = self::query()->andCondition('mobile', $mob)->first();
        return $user ? $user->toArray() : false;
    }


    public static function getAdminByMobile($mobile) {
        return self::query()
            ->where("mobile ='$mobile'")
            ->paginate(50, 'Pagination');
    }

    public static function getAnchorInfo($admin_id) {
        return $query = Admin::query()
            ->columns(array('AdminExt.*', 'Admin.*', 'UgcStream.*'))
            ->leftJoin("AdminExt", "AdminExt.admin_id=Admin.id")
            ->leftJoin("UgcStream", "UgcStream.admin_id=Admin.id")
            ->andWhere("Admin.id={$admin_id}")->first();
    }

    public static function adminGpsRedisKey($admin_id) {
        return __FUNCTION__."admin:gps:redis:admin_id:".$admin_id;
    }

}