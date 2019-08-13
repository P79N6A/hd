<?php

/**
 *  员工管理
 *  model admin
 *  @author     Haiquan Zhang
 *  @created    2015-9-11
 *
 *  status 0:删除/1:正常/2:未激活
 */
class AdminController extends \BackendBaseController {

    const PUSH_RTMP_HOST = 'rtmp://yf.push.cztv.com/live/';
    const PULL_RTMP_HOST = 'rtmp://yf.rtmp.cztv.com/live/';

    public $ignore = [
        'login', 'reset', 'forget', 'logout', 'verifycode','captcha','verifymobile'
    ];

	/**
	 * 查找选择行在当前页中的位置
	 * @param unknown $selectItemId 选择行admin_id
	 * @param unknown $arrId 当前页所有行admin_id
	 * @param unknown $keyTemp 选中行位置
	 * @param unknown $idTemp 选中行的前一行或后一行amdin_id
	 * @param unknown $type 上拖/下拖类型
	 * @return number
	 */
    private function getTempId($selectItemId, $arrId, &$keyTemp, &$idTemp,$type) {
    	if($selectItemId != -1) {
     		if(!empty($arrId) && count($arrId)>0 ) {
    			foreach ($arrId as $k => $v) {
    				if($selectItemId == $v) {
    					$keyTemp = $k;
    					if($type == 0) {
	    					$idTemp = $arrId[$k+1];
    					}else {
    						$idTemp = $arrId[$k-1];
    					}
    					return 0;
    				}
    			}
    		}
    	}
    	return -1;
    }

    /**
     * 往上拖动
     */
    private function upOrderAdminId($orderArr, $selectItemId) {
    	if($selectItemId != -1) {
			$beforeId = -1;
			$keyTemp = 0;
    		if($this->getTempId($selectItemId, $orderArr,$keyTemp,$beforeId,0) != -1) {
    			if($beforeId != -1) {
    				$result = false;
    				$admin = new AdminExt();
    				$beforeOrder = $admin->getOneById($beforeId)->sort;
    				$selectOrder = $admin->getOneById($selectItemId)->sort;
    				if($selectOrder <=  $beforeOrder) {
    					try {
	    					DB::begin();
	    					// 上移
	    					if($keyTemp == 0) {
	    						$keyTemp++;
	    					}
	    				    if($keyTemp > 0) {
		    					for($i = $keyTemp; $i < count($orderArr); $i++) {
			    					$setData = $admin->getOneById($orderArr[$i]);
			    					if($beforeOrder >= $setData->sort && $setData->sort > $selectOrder) {
				    					$setData->sort--;
				    					$result = $admin->updateData($setData);
				    					if(!$result) {
				    						$result = false;
				    						break;
			    						}
			    					}
		    					}
	    				    }
	    				    $selectedOrderId = $admin->getOneById($selectItemId); // 拖到到第一行
	    					$selectedOrderId->sort = $beforeOrder;
	    					if($result) {
	    						$result = $admin->updateData($selectedOrderId);
	    					}
	    					(!$result) ? DB::rollback() : DB::commit();
    					 } catch (DatabaseTransactionException $e) {
				            DB::rollback();
				            $_m = $e->getMessage();
				            $msgs = $$_m->getMessages();
				            foreach ($msgs as $msg) {
				                $messages[] = $msg->getMessage();
				            }
				            return false;
				        }
    				}else {
    					return  false;
    				}
    			}
    			return true;
    		}	
    	}
    	return false;
    }
    
    /**
     * 往下拖动
     */
    private function downOrderAdminId($orderArr, $selectItemId) {
    	if($selectItemId != -1) {
    		$afterId = -1;
    		$keyTemp = 0;
    		if($this->getTempId($selectItemId, $orderArr, $keyTemp, $afterId, 1) != -1) {
    			try {
    				DB::begin();
	    			if($afterId != -1) {
	    				$admin = new AdminExt();
	    				$orderSelectValue = $admin->getOneById($selectItemId)->sort;
	    				$afterTemp = $orderAfterValue = $admin->getOneById($afterId)->sort;
	    				if($orderAfterValue <=  $orderSelectValue) {
		    				if($keyTemp == count($orderArr)-1) {
		    					$keyTemp--;
		    				}
		    				if($keyTemp < count($orderArr)-1) {
		    					
		    					for($i = $keyTemp; $i > -1; $i--) {
			    					$setData = $admin->getOneById($orderArr[$i]);
			    					if($setData->sort >= $orderAfterValue && $setData->sort <= $orderSelectValue) {
				     					++$setData->sort;
				     					$result = $admin->updateData($setData);
				    					if(!$result) {
				    						$result = false;
				    						break;
				    					}
			    					}
			    				}
		    				}
		    				$selectData = $admin->getOneById($selectItemId);
		    				$selectData->sort = $afterTemp;
		    				if($result) {
		    					$result = $admin->updateData($selectData);
		    				}
		    				(!$result) ? DB::rollback() : DB::commit();
	    				}else {
	    					return false;
	    				}
	    			}
	    		} catch (DatabaseTransactionException $e) {
	    			DB::rollback();
	    			$_m = $e->getMessage();
	    			$msgs = $$_m->getMessages();
	    			foreach ($msgs as $msg) {
	    				$messages[] = $msg->getMessage();
	    			}
	    			return false;
	    		}	
    			return true;
    		}
    	}
    	return  false;
    }
    
    /**
     * 拖动排序
     */
    public function setContactsAction() {
    	$admin_id =(int)Session::get("user")->id;      // 登录 admin_id
    	$order = Request::getPost('order','string');   // 拖到结束时当前页所有admin_id
    	$selectItemId = Request::getPost('item','int');  // 拖动行的amdin_id
    	$orderArr = explode('|', $order);

		$result = $this->upOrderAdminId($orderArr, $selectItemId);
		if(!$result) {
			$result =$this->downOrderAdminId($orderArr, $selectItemId);
		}
    	if($result) {
    		echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
    	}else {
    		echo json_encode(['code' => '202', 'msg' => Lang::_('failed')]);
    	}

    	exit;
    }
  
    /**
	 * 排序置顶
     */
    public function setFirstLineAction() {
    	
    	$adminExt = new AdminExt();
    	
    	$maxValue = $adminExt->getMaxSortValue();
    	$adminId = (int)Request::get('id');
    	
    	$data = $adminExt->getOneById($adminId);
    	
    	$data->sort = $maxValue->sort + 1;
    	$result = $adminExt->updateData($data);
    	
		if($result) {
    		echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
    	}else {
    		echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not set top')]);
    	}
    	
    	exit;
    }
    
    public function indexAction() {
        $data = Admin::findAll();
        $dept = Department::findDept();
        $arrDeptName = Admin::showDepartMenent($data);			// 部门列 显示方式  “A部门/a-1部门/a-1-1部门”
        $admin_id = (int)Session::get("user")->id;
        $contactData = AdminContact::findById($admin_id);
        $contact = array();
  		foreach ($contactData as $k => $v) {
  			array_push($contact, $v['contact_id']);
  		}
  		$contact = array_unique($contact);
        View::setVars(compact('data','dept','arrDeptName','sortData','contact'));
    }

    /**
	 * 设置常用联系人
     */
	public function setContactAction() {
		$result = true;
		$adminId =(int)Session::get("user")->id;
    	$contactId = (int)Request::get('id');
    	$data = AdminContact::isExistData($adminId, $contactId);
    	if(!empty($data) && $data == true) {
    		// 存在 更新数据
    		$data->contact_static == 0 ? $data->contact_static = 1 : $data->contact_static =0;
    		$result = AdminContact::updateContactData($data);
    	}else {
    		// 不存在 保存
    		$contactValue = array(
    			"admin_id" => $adminId,
    			"contact_id" => $contactId,
    			"contact_static" => 1,
    		);
    		$adminContact = new AdminContact();
    		$result = $adminContact->saveContactData($contactValue);
    	}
    	
    	// 返回结果
    	if($result) {
    		echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
    	}else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }
		
		exit;
	}
    

    public function searchAction() {
        $queryData=Request::getQuery();
		
        if(Request::getQuery('pinyin','string')||Request::getQuery('status','string')||Request::getQuery('department','string')){
            $condition['pinyin']=Request::getQuery('pinyin','string')?:'';
            $condition['status']=Request::getQuery('status','string')?:'';
            $condition['department']=Request::getQuery('department','string')?:'';
            $getDeptFatherId = Request::getQuery('deptFatherId','int')?:'';
        }else{
            $condition=isset($queryData['condition'])?json_decode($queryData['condition'],true):array('pinyin'=>'','status'=>'','department'=>'');
        }
        if(Request::getQuery('keyword','string')){
            $search['keyword']=Request::getQuery('keyword','string');
        }else{
            $search=array('keyword'=>'');
        }
      
        $allDept = Department::findDept(-1);
        $deptFatherId = array();
        $deptId = array();
        if('' ==  $condition['department']) {
        	$dept = Department::findDept(0);
        }else {
        	if($getDeptFatherId != ''){
        		// $dept = Department::findDept($getDeptId);
        		$condition['department'] = $getDeptFatherId;
        	}
        	Department::findFatherId($condition['department'],$deptFatherId,$deptId);
        	$dept = Department::findDept($condition['department']);
        }
        
        $condition['contacts'] = Request::getQuery('frequentContacts','string');
        $admin_id = (int)Session::get("user")->id;
        $contactData = AdminContact::findById($admin_id);
        $contact = array();
        foreach ($contactData as $k => $v) {
        	array_push($contact, $v['contact_id']);
        }
        $contact = array_unique($contact);
        $data = Admin::search($search,$condition,$contact,Session::get('user')->channel_id);
 
        $arrDeptName = Admin::showDepartMenent($data);			// 部门列 显示方式  “A部门/a-1部门/a-1-1部门”
       
       
        View::pick('admin/index');
        View::setVars(compact('data','condition','search','dept','allDept','deptId','deptFatherId','arrDeptName','contact'));
    }

    // 检查入口频道
    private function checkChannel(){
        if(!Request::has('id')){
            abort(403, Lang::_('invalid channel'));
        }
        $tag = Request::get('id');
        $channel = Channel::getOneByTag($tag);
        if($tag!='system' &&  !$channel){
            abort(403, Lang::_('invalid channel'));
        }
        Cookie::set('channelTag', $channel ? $channel->tag : 'system', time() + 86400*365);
        Cookie::send();
        View::setVars(compact('channel'));
        return $channel;
    }


    // 登录
    public function loginAction() {
        $channel = $this->checkChannel();
        View::setMainView('layouts/login');
        $message = [];
        $channel_id = $channel? $channel->id: 0;
        $mobile = Request::getPost('mobile');
        $limit = Admin::loginTimes(3);
        if(Request::isPost()) {
			$_POST['password'] = $_POST['password1'];
            // 3次失败，启用验证码
            if($limit && !$this->checkCaptcha()) {
                $message[] = Lang::_('captcha error');
            } else {
                Auth::setAuthModel("Admin");
                if(!empty($msg = $this->checkMessage())) {
                    $message = $msg;
                } else if(Auth::attempt(['mobile' => Request::getPost('mobile'), 'password' => Request::getPost('password'), 'channel_id' => $channel_id])) {
                    if(1 == intval(Auth::user()->status)) {
                        Session::set('channel', $channel);
                        Admin::setLastTime(Auth::user()->id, time());
                        redirect(Url::get(''));
                    }
                    $message[] = Lang::_('account frozen');
                } else {
                    Admin::addLoginTimes($mobile);
                    $message[] = Lang::_('login faild');
                    if(Admin::loginTimes(3)){
//                        redirect(Url::get(''));
                    }
                }
            }
            
        }
        View::setVars(compact('message', 'limit'));
    }

    private function checkMessage() {
        $messages = [];
        $open = D::getSetting('is.login.message');
        if($open == 1) {
            if(!VerifyCode::validate(Request::getPost('mobile'), Request::getPost('verifycode'))) {
                $messages[] = Lang::_('VericodeFailed');
            }
        }
        return $messages;
    }

    private function checkCaptcha() {
        $captchaCode = Request::getPost('captcha');
        if($captchaCode===null){
            return true;
        }
        $captcha = new XmasCaptcha();
        return $captcha->check($captchaCode);
    }

    /**
     * 验证码
     */
    public function captchaAction() {
        $captcha = new XmasCaptcha();
        $captcha->generate();
        exit;
    }

    public function logoutAction() {
        Auth::logout();
        $id = Session::get('channel') ? Session::get('channel')->tag : 'system';
        redirect(Url::get('admin/login', ['id' => $id]));
    }

    /**
     * 重置密码
     */
    public function resetAction() {
        $channel = $this->checkChannel();
        $channel_id = $channel? $channel->id: 0;
        $messages = [];
        $resetFlag = false;
        $isPost = false;
        if($inputs = Request::getPost()) {
            $isPost = true;
			$inputs['new_password'] = $inputs['password1'];
            $inputs['channel_id'] = $channel_id;
            $validator = Admin::resetValidator('reset',$inputs);

            if ($validator->passes()) {
                if (VerifyCode::validate($inputs['mobile'], $inputs['verifycode']) && $admin = Admin::findByMobile($inputs['mobile'], $channel_id)) {
                    $inputs['new_password'] = Hash::encrypt($inputs['new_password'], $admin->salt);
                    $update = $admin->update([
                        'password' => $inputs['new_password'],
                        'status' => 1
                    ]);
                    if ($update) {
                        VerifyCode::remove($inputs['mobile']);
                        $messages[] = Lang::_('reset success');
                        $resetFlag = true;
                    } else {
                        $messages[] = Lang::_('error');
                    }
                } else {
                    $messages[] = Lang::_('VericodeFailed');
                }
            } else {
                foreach ($validator->messages()->all() as $msg) {
                    $messages[] = $msg;
                }
            }

        }

        View::setMainView('layouts/login');
        View::setVars(compact('messages','resetFlag','isPost'));

    }

    /**
     * 短信验证
     */
    public function verifycodeAction() {
        $mobile = Request::getPost('mobile');
        $channel_id = Request::getPost('channel_id', 'int', 0);
        $admin = Admin::findByMobile($mobile, $channel_id);
        $return = 400;
        
        if ($admin && VerifyCode::send($mobile)) {
            $return = 200;
        }
        //手机不存在
        if (!$admin){
            $return = 401;
        }
        /*
        $model = AppPush::pushMobile($mobile);
        if($admin) {
            if(VerifyCode::send($mobile)) {
                $return = 200;
                AppPush::approve($model->id,1);
            }
        }
        if($return == 400) {
            AppPush::approve($model->id,2);
        }*/
        echo $return;
        exit;
    }

    /**
     * 查看员工
     */
    public function detailAction() {
        $adminId = (int) Request::get('id');
        $channel_id = Session::get('user')->channel_id;
        $admin = Admin::getOne($adminId);
        if (!$admin) {
            abort(404);
        }
        if ($admin->channel_id != $channel_id) {
            $this->accessDenied();
        }
        $adminExt = AdminExt::ext($adminId);
        list($assignElement, $assignRoleElement) = AuthAssign::getAuth($admin);
        $roleids = AuthAssign::getRoleId($admin);
        $role = [];
        $listRoles = AuthRole::roleList();
        if (!empty($roleids)) {
            foreach ($roleids as $id) {
                $role[] = $listRoles[$id];
            }
        }
        $parents = array();
        if ($adminExt) {
            if ($adminExt->department) {
                $arrDept = explode(',', str_replace("_", "", $adminExt->department));
                foreach ($arrDept as $k => $v) {
	            	$department = Department::findById($v);
	                if ($department) {
	                	$name = "";
	                	if (count($department->getParents()) > 0) {
	                		$i = 0;
	                		foreach ($department->getParents() as $v) {
	                			if($i == count($department->getParents()) -1 ) {
	                				$name .= $v->name;
	                			}else {
	                				$name .= $v->name.'-';
	                			}
	                			$i++;
	                		}
	                		if($k != count($arrDept)-1){
	                			$name = $name."， ";
	                		}
	                		array_push($parents, $name);
	                	}else {
	                		array_push($parents, "");
	                	}
	                    //$parents = $department->getParents();
	                }
                }
            }
            if ($adminExt->duty) {
                $duty = Duty::getOne($adminExt->duty);
            }else{
            	$duty = "";
            }
        }

        
        View::setMainView('layouts/add');
        View::setVars(compact('admin', 'role', 'duty', 'parents', 'assignElement','assignRoleElement'));
    }

    /**
     * 添加员工
     */
    public function addAction() {
        if (Request::isPost()) {
            $messages = [];
            $input = Request::getPost();
            $input['channel_id'] = Session::get('user')->channel_id;
            if (Request::getUploadedFiles()[0]->getError() == 0) {
                if ($this->validateAndUpload($messages)) {
                    $input['avatar'] = $this->validateAndUpload($messages);
                }
            }
            $validator = Admin::makeValidator($input);
            if (!$validator->fails()) {
                $admin = new Admin();
                $messages = $admin->createAdmin($input);
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $duty = Duty::dutyList();
        View::setMainView('layouts/add');
        View::setVars(compact('duty','messages'));
    }
    
    /**
     * 编辑员工
     */
    public function editAction() {
        $admin_id = Request::get('id', 'int');
        $channel_id = Session::get('user')->channel_id;
        $is_admin = Session::get('user')->is_admin;
        $admin = Admin::getOne($admin_id);
        if(!$admin) {
            abort(404);
        }
        // 禁止非频道管理员修改权限
        if($is_admin != 1) {
            $this->accessDenied();
        }
        if($admin->channel_id != $channel_id) {
            $this->accessDenied();
        }
        if (Request::isPost()) {
            DB::begin();
            try {
                $messages = [];
                $input = Request::getPost();
                $input['channel_id']=$channel_id;
                if (Request::getUploadedFiles()[0]->getError() == 0) {
                    if ($this->validateAndUpload($messages)) {
                        $input['avatar'] = $this->validateAndUpload($messages);
                    }
                }
                $validator = Admin::makeValidator($input, $admin->id);
                if (!$validator->fails()) {
                    $update = [
                        'mobile'=>$input['mobile'],
                        'job_name' => $input['job_name'],
                        'name' => $input['name'],
                        'avatar' => $input['avatar'],
                        'updated_at' => Admin::setUpdateTime(),
                    ];
                    // 创建频道为0的员工，默认就是is_admin =1
                    if($channel_id == 0) {
                        $update['is_admin'] = 1;
                    }
                    if (!empty($input['password'])) {
                        $update['password'] = Hash::encrypt($input['password'], $admin->salt);
                    }

                    if ($admin->update($update)) {
                        $elements_need_inserted = array();
                        $element_id_hidden = array();
                        $assign_role = AuthAssign::getAssignRole($admin->id);
                        $base_element_ids = $input['element_id'];

                        if(isset($input['roleid'])) {
                            if($assign_role&&$assign_role->element_id!=$input['roleid']) {
                                $old_roles_element = AuthRole::getRoleElement((int)$assign_role->element_id);
                                $base_element_ids_tmp = [];
                                foreach($base_element_ids as $e2) {
                                    if(!in_array($e2, $old_roles_element)) {
                                        array_push($base_element_ids_tmp, $e2);
                                    }
                                }
                                $base_element_ids = $base_element_ids_tmp;

                            }
                            AuthAssign::resetRole($admin, (int)$input['roleid']);
                        }
                        if(isset($input['roleid'])) {
                            $roles_element = AuthRole::getRoleElement((int)$input['roleid']);
                        }
                        else {$roles_element = [];}

                        if(!empty($base_element_ids)) {
                            foreach($base_element_ids as $eid) {
                                foreach(AuthElement::getDependenceElement($eid) as $e) {
                                    array_push($element_id_hidden, $e['id']);
                                }
                            }
                            foreach(AuthElement::getAuthHiddenElement() as $e) {
                                array_push($element_id_hidden, $e['id']);
                            }

                            $base_element_ids = array_merge ($base_element_ids, $element_id_hidden);
                            foreach($base_element_ids as $e3) {
                                if(!in_array($e3, $roles_element)) {
                                    array_push($elements_need_inserted, $e3);
                                }
                            }
                            AuthAssign::resetElement($admin, $elements_need_inserted);
                        }
                        else {
                            AuthAssign::resetElement($admin, []);
                        }
                        AdminExt::resetExt($admin, $input);
                        $terminalNames = ["web","app","wap","wechat"];
                        foreach ($terminalNames as $terminalName){
                            $redisKey = "category_user_json";
                            RedisIO::hDel($redisKey,"$channel_id:$terminalName:".$admin->id);
                        }
                        $greedy_mode_web = (isset($input['greedy_mode_web'])&&$input['greedy_mode_web'])?true:false;
                        CategoryAuth::setCateAuth($admin->id, (''==trim($input['category_id']))?[]:explode(',', $input['category_id']), "web", $channel_id, $greedy_mode_web);
                        $greedy_mode_wap = (isset($input['greedy_mode_wap'])&&$input['greedy_mode_wap'])?true:false;
                        CategoryAuth::setCateAuth($admin->id, (''==trim($input['category_id_app']))?[]:explode(',', $input['category_id_app']), "app", $channel_id, $greedy_mode_wap);
                        $greedy_mode_app = (isset($input['greedy_mode_app'])&&$input['greedy_mode_app'])?true:false;
                        CategoryAuth::setCateAuth($admin->id, (''==trim($input['category_id_wap']))?[]:explode(',', $input['category_id_wap']), "wap", $channel_id, $greedy_mode_app);

                        RedisIO::del(CategoryAuth::categoryAuthRedisKey());
                        DB::commit();
                        $messages[] = Lang::_('success');
                    } else {
                        DB::rollback();
                        $messages[] = Lang::_('error');
                    }
                } else {
                    $messages = $validator->messages()->all();
                    DB::rollback();
                }
            } catch (\Exception $e) {
                DB::rollback();
                $messages[] = Lang::_('error');
            }
        }
        $adminExt = AdminExt::ext($admin_id);
        list($assignElement, $assignRoleElement) = AuthAssign::getAuth($admin);
        $roleids = AuthAssign::getRoleId($admin);
        $role = AuthRole::roleList();
        $duty = Duty::dutyList();
        $group = AdminGroup::getAll();
		
        
        $parents = array();
        if ($adminExt) {
            if ($adminExt->department) {
            	$arrIds = explode(',',$adminExt->department);
            	foreach ($arrIds as $departmentId) {
            		$departmentId = str_replace("_","",$departmentId);
	                $department = Department::findById($departmentId);
	                if ($department) {
	                    $parents = $department->getParents();
	                }
            	}
            
            }
        }
        $adminExt->department = str_replace("_", "", $adminExt->department);

       
        
        View::setMainView('layouts/add');
        View::setVars(compact('admin','messages', 'role', 'roleids','duty','group', 'parents',
            'adminExt','assignElement','assignRoleElement'));
    }

    public function deleteAction() {
        $adminId = (int)Request::get('id');
        $data = Admin::getOne($adminId);
        if (!empty($data)
            && $data->id !== Session::get("user")->id
            && $data->channel_id==Session::get("user")->channel_id
            ) {
            if($data->status!=0){
                $data->deleteAdmin();
            }else{
                $data->activeAdmin();
            }
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }
        exit;
    }

    public function anchorAction() {
        $messages = [];
        $admin_id = Request::get('id', 'int');
        $anchor = AdminExt::ext($admin_id);
        if(empty($anchor->rtmpurl) || empty($anchor->playurl))
        {
            $url = $this->GenerateRtmpUrl($admin_id);
            $rtmpUrl = $url['rtmpUrl'];
            $playUrl = $url['playUrl'];
        }else{
            $rtmpUrl = $anchor->rtmpurl;
            $playUrl = $anchor->playurl;
        }
        $drtmpUrl = $anchor->rtmpurl;
        $dplayUrl = $anchor->playurl;
        if (Request::isPost()) {
            $rtmpUrl = Request::getPost('rtmpurl');
            $playUrl = Request::getPost('playurl');
            $input = Request::getPost();
            $return = $anchor->modifyExt($input);
            if($return) {
                RedisIO::set('ugc::admin::anchor::'.$input['id'],json_encode($input));
                $messages[] = Lang::_('success');
            }else{
                $messages[] = Lang::_('failed');
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('anchor','messages','rtmpUrl','playUrl','drtmpUrl','dplayUrl'));
    }
  


    protected function GenerateRtmpUrl($admin_id)
    {
        $rand = rand(1000, 9999);
        $rtmpUrl = self::PUSH_RTMP_HOST . $admin_id . "_" . $rand;
        $playUrl = self::PULL_RTMP_HOST . $admin_id . "_" . $rand;
        return array('rtmpUrl'=>$rtmpUrl,'playUrl'=>$playUrl);
    }


    protected function validateAndUpload(&$messages) {
        $path = '';
        if (Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if (!$error) {
                $ext = $file->getExtension();
                if (in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload($ext, $file->getTempName(), Auth::user()->channel_id.'/logos');
                    $attachmodel = new AttachmentCommon();
                    $attachid = $attachmodel->createAttach(array(
                        'origin_name' => $file->getName(),
                        'name' => $file->getName(),
                        'type' => 2, //1:视频 2:图片 0:未知
                        'path' => $path,
                        'ext' => $file->getType(),
                    ));
                } else {
                    $messages[] = Lang::_('please upload valid header image');
                }
            } elseif ($error == 4) {
                $path = Request::getPost('thumb', null, '');
                if (!$path) {
                    $messages[] = Lang::_('please choose upload header image');
                }
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload header image');
        }
        return $path;
    }

     /*
     * 批量生成用户扩展数据
     */
    public function adminextAction() {
        $testadmins = Admin::find();
        foreach ($testadmins as $aaa) {
                $aaa->modifyAdmin(array(
                    'name' => $aaa->name,
                    'dept_id' => 0,
                    'duty_id' => 0,
                    ));
        }
        exit;
    }


    /**
     * json数据
     */
    public function categoryjsonAction() {
        header("Content-Type: application/json");
        $id = Request::get('id', 'int');
        $terminal = Request::get("terminal", "trim");  
        $category_id = 0;
        $channel_id = Session::get('user')->channel_id;
        $tree = CategoryTree::getCategoryTree($channel_id, $terminal);
        $temp = $tree->getCategoryTreeJson2($category_id, CategoryAuth::getCateAuth($id, $terminal));
        echo json_encode($temp);
        exit;
    }

    /**
	 * 第一次使用，重置admin_ext表的department字段
     */
	public function resetAdminExtDeptAction() {
		$adminExt = new AdminExt();
		$data = $adminExt->getDepartment();
		
		if(isset($data) && !empty($data)) {
			foreach ($data as $v) {
				if($v->department != null) {
					$v->department = str_replace("_", "", $v->department);
					$deptArr = explode(",", $v->department);	
					if(count($deptArr) > 0) {
						$deptTemp = "";
						foreach ($deptArr as $k => $dept) {
							$deptTemp .= "_".$dept."_";
							if($k != count($deptArr)-1) $deptTemp .= ",";
						}
					}else {
						$deptTemp = "_".$dept."_";
					}
					$v->department = $deptTemp;
					// update data
					AdminExt::saveDepartment($v);
				}
			}
		}	
	}

	//手机验证码是否正确 401（包括401）以上都要显示图片验证码
    public function verifymobileAction() {
         $mobile = Request::getPost("mobile");
         $code = Request::getPost("verifycode");

         $retryCnt = Session::get(__FUNCTION__,0);
         $result = 400; //手机号不存在

         if( $retryCnt < 3 ){
             if(VerifyCode::validate($mobile,$code)){
                 $result = 200;
             }else{
                $retryCnt++;
                if ($retryCnt == 3){
                    $result = 401; //开启验证码
                }
                Session::set(__FUNCTION__,$retryCnt);
             }
         }else{

             if( $this->checkCaptcha() == 1 ){
                 if (VerifyCode::validate($mobile,$code)){
                     $result = 200;
                 }else{
                     $result = 403; //短信验证码错误
                 }
             } else {
                 $result = 402; //图片验证码错误
             }
         }

         echo $result;
         exit();
	}

}
