<?php
/**
 *  部门管理
 *  model department
 *  @author     Haiquan Zhang
 *  @created    2015-9-24
 *  
 */

class DepartmentController extends \BackendBaseController {
    
    public function indexAction() {
        $channel_id = Session::get('user')->channel_id;
        $data = Department::findPagination($channel_id);
        $departments = array();
        $tree = DepartmentTree::getTree($channel_id);
        foreach($data->models as $model) {
            //$model->level = 0;
            $object =  json_decode( json_encode($model),true);
            $object['has_child'] = !empty($tree->getChildren($object['id']));
            $object['level'] = 0;
            array_push($departments, $object);
            Department::findDepthChildren($tree, $object, $departments, 0);
        }
        View::setVars(compact('data', 'departments'));
    }
    
    /**
     * 添加部门
     */
    public function addAction() {

        if (Request::isPost()) {
            $messages = [];
            $data = Request::getPost();
            $data['channel_id'] = Session::get('user')->channel_id;
            $validator = Department::makeValidator($data);
            if (!$validator->fails()) {
                $department = new Department();
                $return = $department->createDepartment($data);
                if($return){
                    $messages[]=Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }
            else {
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
            View::setVars(compact('messages'));
        }

        View::setMainView('layouts/add');
    }
    
    /**
     * 编辑部门
     */
    public function editAction($dept_id=0) {
        $dept_id = Request::get("id", "int");
        if(!$dept_id) {
            redirect(Url::get("department/add"));
        }
        $department = Department::findById($dept_id);
        if (Request::isPost()) {
            $messages = [];
            $data = Request::getPost();
            $validator = Department::makeValidator($data);
            if (!$validator->fails()) {
                $return = $department->modifyDepartment($data);
                if($return){
                    $messages[]=Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }
            else {
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
            View::setVars(compact('messages'));
        }
        View::setMainView('layouts/add');
        View::setVars(compact('department'));
    }

    /**
     * json数据
     */
    public function jsonAction() {
        header("Content-Type: application/json");
        $id = Request::get('id', 'int');
        $choice_self = Request::get('cs', 'int');
        $id = isset($id) ? $id : 0;
        $channel_id = Session::get('user')->channel_id;
        $tree = DepartmentTree::getTree($channel_id);
        $arrDeptId = array();
        if($id != 0) {
            $deptIds = AdminExt::ext($id);
            if($deptIds)
                $arrDeptId = explode(',', str_replace("_", "", $deptIds->department));
        }

        $temp = $tree->getDepartmentTreeJson2($id,$arrDeptId,$choice_self);
        echo json_encode($temp);
        exit;
    }

    /**
     * json数据
     */
    public function fatherjsonAction() {
        header("Content-Type: application/json");
        $id = Request::get('id', 'int');
        $choice_self = Request::get('cs', 'int');
        $id = isset($id) ? $id : 0;
        $channel_id = Session::get('user')->channel_id;
        $tree = DepartmentTree::getTree($channel_id);
        $arrDeptId = array();
        if($id != 0) {
            $dept = Department::findById($id);
            $arrDeptId = array($dept->father_id);
        }
        $temp = $tree->getDepartmentTreeJson2($id, $arrDeptId, $choice_self);
        echo json_encode($temp);
        exit;
    }

    /**
     * 删除部门
     *  @author     zhangyichi
     *  @created    2015-11-30
     * 
     */
    public function deleteAction(){
   		$bStatic = true;
        $department_id = Request::get('id','string');
        $arrData = array();
        $arr = array();
        $department = Department::findById($department_id);
        $admin = Session::get('user');
        // 判读部门占用
        if($admin->channel_id==$department->channel_id && $this->checkDepartmentStatus($department_id)){
        	// 删选中部门
        	$bStatic = $department->deleteDepartment();
	        // 查子部门
        	Department::findIdByFartherId($department_id,$arrData);
	        DB::begin();
	      	if($bStatic && null != $arrData && count($arrData) > 0) {
	      		foreach ($arrData as $v) {
	      			$department = Department::findById($v);
	      			// 判读子部门占用
	      			if($admin->channel_id==$department->channel_id && $this->checkDepartmentStatus($v)){
	      				// 删子部门
	      				if(!$department->deleteDepartment()) {
	      					$bStatic = false;
	      					break;
	      				}
	      			}else {
	      			    $arr=array('msg'=>Lang::_('department_failed'));
			        	echo json_encode($arr);
			        	exit;
	        		}
	      		}
	      	}
	      	
	      	if($bStatic) {
	      		DB::commit();
	      		$arr=array('code'=>200);
	      	}else {
	      		DB::rollback();
	      		$arr=array('msg'=>Lang::_('failed'));
	      	}
	      	echo json_encode($arr);
	      	exit;
        }else{
        	$arr=array('msg'=>Lang::_('department_failed'));
        	echo json_encode($arr);
        	exit;
        }
         
    }

    private function checkDepartmentStatus($department_id){
        $data=AdminExt::findByDept($department_id);
        if(empty($data)){
            $arr=array_keys(Department::getTree(Session::get('user')->channel_id)->cateArray,$department_id);           
            if(empty($arr)){
                return true;
            }else{
                $flag=false;
                foreach($arr as $k=>$v){
                    if($this->checkDepartmentStatus($v)){
                        $flag=true;
                    }else{
                        return false;
                    }
                }
                return $flag;
            }
   	 	}else {
   	 		return false;
   	 	}
    }

}
