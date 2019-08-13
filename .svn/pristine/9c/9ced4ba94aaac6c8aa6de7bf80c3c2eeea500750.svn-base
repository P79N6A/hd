<?php
/**
 *  直播管理 防盗链配置
 *  controller signal_drm
 *  @author     cjh
 *  @created    2016-12-22
 *  
 *  @param id,drm_name,drm_value
 */

class SignalDrmController extends \BackendBaseController {

    public function indexAction() {
        $id = Request::getQuery('id','int');
        $page = Request::getQuery('page','int','1');
        $signalDrm = new SignalDrm();   
        $data = $signalDrm->findAllData($page);
        View::setVars(compact('data'));
    }

    public function addAction() {
        if (Request::isPost()) {
			$data=Request::getPost();
			$validator = SignalDrm::makeValidator($data);
			if($validator->passes()){
				$signalDrm = new SignalDrm();
				if($signalDrm->createData($data)) {
					$messages[] = Lang::_('success');
				}
				else {
					$messages[] = Lang::_('failed');
				}
			}
			else {
				foreach($validator->messages()->all() as $msg){
					$messages[]=$msg;
				}
			}
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages', 'data'));
    }

    public function editAction() {
        $id = Request::getQuery('id','int');
        $signalDrm = new SignalDrm();
        $data = $signalDrm->getOne($id);
        $this->initFormView();        
        $messages = [];
        if (Request::isPost()) {
            $data=Request::getPost();
            $validator=SignalDrm::makeValidator($data);
            if($validator->passes()){
                if($signalDrm->modifyData($data)) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }else{
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
        }
        View::setVars(compact('messages','data'));
    }

    public function deleteAction(){
        $id = Request::getQuery('id','int');
        $signalDrm = new SignalDrm();
        $return = $signalDrm->deleteData($id);
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function searchAction(){
    	$title = Request::get('title');
    	$value = Request::get('value');
    	$mess = array(
    			'title' => $title,
    			'value' => $value
    	);
    	
    	if($mess){
    		$data = SignalDrm::searchData($mess);
    	}
    	if($mess['title'] == "" && $mess['value'] == "" ){
			$this->response->redirect('signal_drm/index');	// 跳转到首页
		}
		
		View::pick('signal_drm/index');
		View::setVars(compact('mess','data'));
    }



}