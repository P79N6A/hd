<?php
/**
 * Created by 
 * Date: 2016/06/21
 */
class GovernmentDepartmentController extends \BackendBaseController {

    public $ignore = [
        'json', 'list'
    ];
    
    /**
     * json数据
     */
    public function jsonAction() {

        $root = GovernmentDepartment::getRootCategory();
        $data = array();
        foreach($root as $node) {
            $temp = RegionsTree::getCategoryJson($node->id);
            array_push($data, $temp);
        }
        echo json_encode($data);
        exit;
    }

    /**
     * 根据上级ID获取下级队列
     */
    public function listAction() {
        $father_id = Request::getPost('id','int');
        if(!$father_id){
            $father_id=0;
        }
        $data=GovernmentDepartment::findListByFather($father_id);
        echo json_encode($data);
        exit;
    }
    
    public function findAction(){
        $id = Request::getPost('id','int');
        $channel_id = Request::getPost('channel_id','int');
    	if($id == 0){
    		$data=GovernmentDepartment::findGovernment(0, $channel_id);
    		echo json_encode($data);
    		exit;
    	}
    }

    /**
     * 列表页管理
     */
    public function indexAction(){
        $data=GovernmentDepartment::findAll();
        View::setVars(compact('data'));
    }

    public function jsonGetGovermentDataAction(){

        $data_id = Request::getQuery("data_id","int",0);
        $msg = ["success"=>false];
        if($data_id && $g_data = GovernmentDepartmentData::fetchGovernmentDepartmentId($data_id))
        {
            $d = GovernmentDepartment::fetchById($g_data[0]['government_department_id']);
            $msg = array("success"=>true,"data"=>['id'=>$d->id,'name'=>$d->name]);
        }
        echo json_encode($msg);
        exit();
    }


    public function createAction(){
        $messages = [];
        if(Request::isPost()){
            $input = Request::getPost();
            if(isset($input['name'])){
                $government = new GovernmentDepartment();
                $government->name=$input['name'];
                $government->pinyin=Cutf8py::encode($input['name'], 'all');
                $government->pinyin_short=Cutf8py::encode($input['name']);
                $government->channel_id = Auth::user()->channel_id;
                if($input['one_id']){
                    if($input['two_id']){
                        if($input['three_id']){
                            if($input['four_id']){
                                if($input['five_id']){
                                    if($input['six_id']){
                                        $messages[] = Lang::_('cant low to department one');
                                    }else{//创建第六级
                                        $government->father_id=$input['five_id'];
                                        $government->level='department_six';
                                        $government->createGovernmentDepartment();
                                        $messages[] = Lang::_('success');
                                    }
                                }else{//创建第五级
                                    $government->father_id=$input['four_id'];
                                    $government->level='department_five';
                                    $government->createGovernmentDepartment();
                                    $messages[] = Lang::_('success');
                                }
                            }else{//创建第四级
                                $government->father_id=$input['three_id'];
                                $government->level='department_four';
                                $government->createGovernmentDepartment();
                                $messages[] = Lang::_('success');
                            }
                        }else{//创建第三级
                            $government->father_id=$input['two_id'];
                            $government->level='department_three';
                            $government->createGovernmentDepartment();
                            $messages[] = Lang::_('success');
                        }
                    }else{//创建第二级
                        $government->father_id=$input['one_id'];
                        $government->level='department_two';
                        $government->createGovernmentDepartment();
                        $messages[] = Lang::_('success');
                    }
                }else{//创建第一级
                    $government->father_id=0;
                    $government->level='department_one';
                    $government->createGovernmentDepartment();
                    $messages[] = Lang::_('success');
                }
            }else{
                $messages[] = Lang::_('department name not exit');
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function deleteAction(){
        $id = Request::get('id');
        $data = GovernmentDepartment::fetchById($id);
        $arr = GovernmentDepartment::findListByFather($data->id);
        if($arr){
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cant delete have department son')]);
        }else if(GovernmentDepartment::deleteGovernmentDepartment($id)){
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        }else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }
        exit;
    }

    public function modifyAction(){
        $id = Request::get('id');
        var_dump($id);
        $government = GovernmentDepartment::fetchById($id);
        var_dump($government);
        
        if(Request::isPost()){
            $input = Request::getPost();
            if(isset($input['name'])){
                $government->name=$input['name'];
                $government->pinyin=Cutf8py::encode($input['name'], 'all');
                $government->pinyin_short=Cutf8py::encode($input['name']);
                if($input['one_id']){
                    if($input['two_id']){
                        if($input['three_id']){
                            if($input['four_id']){
                                if($input['five_id']){
                                    if($input['six_id']){
                                        $messages[] = Lang::_('cant low to department one');
                                    }else{//创建第六级
                                        $government->father_id=$input['five_id'];
                                        $government->level='department_six';
                                        $government->createGovernmentDepartment();
                                        $messages[] = Lang::_('success');
                                    }
                                }else{//创建第五级
                                    $government->father_id=$input['four_id'];
                                    $government->level='department_five';
                                    $government->createGovernmentDepartment();
                                    $messages[] = Lang::_('success');
                                }
                            }else{//创建第四级
                                $government->father_id=$input['three_id'];
                                $government->level='department_four';
                                $government->createGovernmentDepartment();
                                $messages[] = Lang::_('success');
                            }
                        }else{//创建第三级
                            $government->father_id=$input['two_id'];
                            $government->level='department_three';
                            $government->createGovernmentDepartment();
                            $messages[] = Lang::_('success');
                        }
                    }else{//创建第二级
                        $government->father_id=$input['one_id'];
                        $government->level='department_two';
                        $government->createGovernmentDepartment();
                        $messages[] = Lang::_('success');
                    }
                }else{//创建第一级
                    $government->father_id=0;
                    $government->level='department_one';
                    $government->createGovernmentDepartment();
                    $messages[] = Lang::_('success');
                }
            }else{
                $messages[] = Lang::_('department name not exit');
            }
        }
        
        //生成上级地区
        $parents=$government->getParents();
        unset($parents[count($parents)-1]);
        foreach($parents as $p){
            $level_id=($p->level);
            var_dump($level_id);
            $government->$level_id=$p->id;
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages','government'));
    }
}