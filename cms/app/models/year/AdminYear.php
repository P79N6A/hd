<?php

/**
 *  员工管理
 *  model admin
 *  @author     Haiquan Zhang
 *  @created    2015-9-11
 *
 *  status 0:删除/1:正常/2:未激活
 */
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AdminYear extends Model {

    const ALL = -1;
    const DELETE = 0;
    const ACCEPT = 1;
    const REJECT = 2;

    const PAGE_SIZE = 50;

    use GenialCloud\Support\RememberToken\InRedis;

    public function getSource() {
        return 'admin';
    }

    public function onConstruct() {
        //使用年会数据库链接
        $this->setConnectionService('db_year');
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'is_admin', 'mobile', 'name', 'password', 'salt', 'last_time' , 'avatar', 'remember_token', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'is_admin', 'mobile', 'name', 'password', 'salt', 'last_time' , 'avatar', 'remember_token', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'is_admin', 'mobile', 'name', 'password', 'salt', 'last_time' ,],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'is_admin' => Column::TYPE_INTEGER,
                'mobile' => Column::TYPE_VARCHAR,
                'name' => Column::TYPE_VARCHAR,
                'password' => Column::TYPE_VARCHAR,
                'salt' => Column::TYPE_VARCHAR,
                'last_time' => Column::TYPE_INTEGER,
                'avatar' => Column::TYPE_VARCHAR,
                'remember_token' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'is_admin','last_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'is_admin' => Column::BIND_PARAM_INT,
                'mobile' => Column::BIND_PARAM_STR,
                'name' => Column::BIND_PARAM_STR,
                'password' => Column::BIND_PARAM_STR,
                'salt' => Column::BIND_PARAM_STR,
                'last_time' => Column::BIND_PARAM_INT,
                'avatar' => Column::BIND_PARAM_STR,
                'remember_token' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'is_admin' => '0','last_time' => '0',
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll() {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query()
                ->columns(array('AdminExtYear.*', 'AdminYear.*', 'DepartmentYear.*', 'DutyYear.*'))
                ->leftJoin("AdminExtYear", "AdminExtYear.admin_id=AdminYear.id")
                ->leftJoin("DepartmentYear", "DepartmentYear.id=AdminExtYear.department")
                ->leftJoin("DutyYear", "DutyYear.id=AdminExtYear.duty")
                ->andWhere("AdminYear.channel_id={$channel_id}");
        return $query
                ->orderBy('FIELD(AdminYear.status, 1, 2, 0), AdminExtYear.sort desc, AdminYear.id')
                ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function findMaster(){
        return self::query()->andCondition('is_admin',1)->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function search($search,$condition) {
        $keyword = $search['keyword'];
        $status = $condition['status']?:'';
        $pinyin = $condition['pinyin']?:'';
        $department = $condition['department']?:'';
        $condition = "";
        $query = self::query()
            ->columns(array('AdminExtYear.*', 'AdminYear.*', 'DepartmentYear.*', 'DutyYear.*'))
            ->leftJoin("AdminExtYear", "AdminExtYear.admin_id=AdminYear.id")
            ->leftJoin("DepartmentYear", "DepartmentYear.id=AdminExtYear.department")
            ->leftJoin("DutyYear", "DutyYear.id=AdminExtYear.duty");
        if($keyword) {
            $condition="(AdminExtYear.pinyin like '%$keyword%' or AdminYear.name like '%$keyword%' or DepartmentYear.name like '%$keyword%' or DutyYear.name like '%$keyword%' or AdminYear.mobile like '%$keyword%') and ";
        }
        if ($status!=="") {
            $query = $query->andWhere('AdminYear.status=' . $status);
        }
        if ($pinyin!=="") {
            $pinyin=strtolower($pinyin);
            $query = $query->andWhere(" AdminExtYear.pinyin like '{$pinyin}%'");
        }
        if ($department!=="") {
            $query = $query->andWhere('DepartmentYear.id=' . $department);
        }
        return $query->andWhere($condition . "AdminYear.channel_id=" . Session::get('user')->channel_id)
            ->orderBy(' FIELD(AdminYear.status, 1, 2, 0) , AdminExtYear.sort desc, AdminYear.id')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function getAdminArr() {
        //if(!$channel_id) $channel_id = Session::get("user")->channel_id;
        $admins = self::find();
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
        return AdminExtYear::findFirst($parameters);
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

    public static function makeValidator($input,$excluded_id=0) {
        $channel_id = Session::get("user")->channel_id;
        return Validator::make(
                        $input, [
                    'name' => 'required|min:2|max:99',
                    'password' => 'min:6|max:20',
                    'mobile' => "required|size:11|unique:admin,mobile,{$excluded_id},id,channel_id,{$channel_id}",
                        ], [
                    'name.required' => '昵称必填',
                    'name.min' => '昵称最短2字符',
                    'name.max' => '昵称最长99字符',
                    'password.min' => '密码最短6字符',
                    'password.max' => '密码最长20字符',
                    'mobile.required' => '手机号必填',
                    'mobile.unique' => '手机号已存在'
                        ]
        );
    }

    public static function masterValidator($input,$excluded_id=0){
        list($validator,$msg) = self::commonValidator();
        $validator['mobile'] = "required|size:11|unique:admin,mobile,{$excluded_id},id,channel_id,{$input['channel_id']}";
        $msg['mobile.required'] = '手机号码必填';
        $msg['mobile.unique'] = '手机号已经存在';
        return Validator::make(
                        $input, $validator,$msg
        );
    }

    public static function commonValidator() {
        return [
            [
                'name' => 'required|min:2|max:99',
                'password' => 'min:6',
                'channel_id'=>"required",
            ],
            ['name.required' => '昵称必填',
                'name.min' => '昵称最短2字符',
                'name.max' => '昵称最长99字符',
                'password.min' => '密码最短6字符',
            ]
        ];
    }

    public function getChannel() {
        $channel = ChannelYear::getOneChannel($this->channel_id);
        if ($this->channel_id == 0) {
            return "System";
        } else {
            return $channel->name;
        }
    }

    public function createAdmin($data) {
        $this->assign($data);
        $channel_id = Session::get('user')->channel_id;
        $this->channel_id = isset($data['channel_id']) && $channel_id == 0? $data['channel_id']: $channel_id;
        $this->salt = str_random();
        $this->status = isset($data['status'])? $data['status']: 1;
        $this->avatar = isset($data['avatar'])? $data['avatar']: null;
        $messages = [];
        if(isset($data['password']) && $data['password']) {
            $this->password = Hash::encrypt($data['password'], $this->salt);
        }
        // 创建频道为0的员工，默认就是is_admin =1
        if($this->channel_id == 0) {
            $this->is_admin = 1;
        }
        if($this->save()) {
            $this->setAdminExt($data);
            // 暂时取消个人简历等功能 by 张海盼
//            $vitae = Vitae::getOneByAdmin($this->id);
//            $vitae->modifyVitae($data);
            $messages[] = Lang::_('success');
        } else {
            foreach($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public function updateAdmin($model,array $data,array $fields){
        $filterData = [];
        if(!empty($fields)){
            foreach ($fields as $k=>$v){
                $filterData[$v] = $data[$v];
                if($v=='password'){
                    if(!empty($data[$v])){
                        $data[$v] = Hash::encrypt($data['password'], $model->salt);
                    }else{
                         unset($filterData[$v]);
                         unset($fields[$k]);
                    }
                }
            }
        }
        return $model->update($filterData,$fields);
    }


    public function deleteAdmin() {
        $this->status = 0;
        $this->save();
    }

    public function activeAdmin() {
        $this->status = 2;
        $this->save();
    }

    public function modifyAdmin($data) {
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
        $adminext->department = (isset($data['dept_id']) && $data['dept_id']) ? $data['dept_id'] : $adminext->department;
        $adminext->duty = (isset($data['duty_id']) && $data['duty_id']) ? $data['duty_id'] : $adminext->duty;
        $adminext->pinyin = Cutf8py::encode($data['name']);
        $adminext->sort = isset($data['sort']) ? $data['sort'] : 0;
        $adminext->save();
    }

    /**
     * 频道过滤
     */
    public function selectChannel($parameters, $channel_id) {
        if ($channel_id) {
            $parameters['conditions'] .=" and channel_id = " . $channel_id;
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

    public static function changeValidator($input) {
        $validator = Validator::make(
                        $input, [
                    'old_password' => "required",
                    'new_password' => 'required|min:6',
                    'confirm_password' => "required|same:new_password",
                        ], [
                    'old_password.required' => '旧密码必填',
                    'new_password.required' => '新密码必填',
                    'new_password.min' => '新密码过短',
                    'confirm_password.required' => '确认密码必填',
                    'confirm_password.same' => '两次密码输入不同',
                        ]
        );
        return $validator;
    }

    public static function findByMobile($mobile, $channel_id = 0) {
        $query = self::query()
            ->andCondition('mobile', $mobile);
        if($channel_id) {
            $query->andCondition('channel_id', $channel_id);
        }
        return $query->first();
    }

    public static function setLastTime($id,$last_time){
        $admin = self::getOne($id);
        $admin->last_time = $last_time;
        return ($admin->update()) ? true : false;
    }

    /**
     * 查找一个手机号的所有频道
     * @author Zhangyichi
     * @param $mobile
     * @return array
     */
    public static function findAllByMobile($mobile) {
        return  self::query()
            ->andCondition('mobile',$mobile)
            ->execute();
    }

    public static function findAllByChannel($channel) {
        $admin = self::query()
            ->where("channel_id = '{$channel}'")->execute()->toarray();
        return $admin;
    }

    public function activation($new_password) {
        $this->password = $new_password;
        $this->status = 1;
        return $this->update();
    }

    public static function resetValidator($input) {
        $validator = Validator::make(
                        $input, [
                    'mobile' => 'required|digits:11',
                    'verifycode' => 'required|min:4|max:4',
                    'new_password' => 'required|min:6|max:20',
                    'confirm_password' => "required|same:new_password",
                        ], [
                    'mobile.required' => '新密码必填',
                    'mobile.digits' => '手机号码格式不正确',
                    'verifycode.required' => '验证码必填',
                    'verifycode.min' => '验证码格式不正确',
                    'verifycode.max' => '验证码格式不正确',
                    'new_password.required' => '新密码必填',
                    'new_password.min' => '新密码格式不正确',
                    'new_password.max' => '新密码格式不正确',
                    'confirm_password.required' => '确认密码必填',
                    'confirm_password.same' => '两次密码输入不同',
                        ]
        );
        return $validator;
    }

    public static function loginTimes($limit=3){
        $key = 'login_fail';
        $times = (int)Session::get($key);
        return $times >= $limit;
    }

    public static function addLoginTimes(){
        $key = 'login_fail';
        $times = Session::get($key) ? : 0;
        Session::set($key,(int)$times+1);
    }

    public function getCheckedRole() {
        $assignrole = AuthAssignYear::find(array('conditions' => 'type = 1 and user_id=' . $this->id . ' and channel_id=' . $this->channel_id));
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
                $authassign = new AuthAssignYear();
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
                $assign = AuthAssignYear::findFirst(array('conditions' => 'element_id=' . $v . ' and type = 1 and user_id=' . $this->id . ' and channel_id=' . $this->channel_id));
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
                $authassign = new AuthAssignYear();
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
                $assign = AuthAssignYear::findFirst(array('conditions' => 'element_id=' . $v . ' and type = 0 and user_id=' . $this->id . ' and channel_id=' . $this->channel_id));
                if($assign) $assign->delete();
            }
        }
        //删除角色已含的权限
        foreach ($checked_element as $v) {
            if ($v && in_array($v, $fixed_element)) {
                $assign = AuthAssignYear::findFirst(array('conditions' => 'element_id=' . $v . ' and type = 0 and user_id=' . $this->id . ' and channel_id=' . $this->channel_id));
                if($assign) $assign->delete();
            }
        }
    }

    public static function allName() {
        return self::query()->execute()->toarray();
    }

}
