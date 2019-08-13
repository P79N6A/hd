<?php
/**
 *  直播管理 厂家配置
 *  controller signal_producer
 *  @author     cjh
 *  @created    2016-12-29
 *  @param id, vender_name, vender_code, weight, remarks
 */

class SignalProducerController extends \BackendBaseController {

    public function indexAction() {
    	
        $page = Request::getQuery('page','int','1');
        $signalVender = new SignalProducer();
        $data = $signalVender->findAllData($page);
        
        View::setVars(compact('data'));
    }

    public function addAction() {
        if (Request::isPost()) {
			$data=Request::getPost();
			$validator = SignalProducer::makeValidator($data);
			if($validator->passes()) {
				$signalVender = new SignalProducer();
				if($signalVender->createData($data)) {
					$messages[] = Lang::_('success');
				}
				else {
					$messages[] = Lang::_('failed');
				}
			}
			else {
				foreach($validator->messages()->all() as $msg) {
					$messages[]=$msg;
				}
			}
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages', 'data'));
    }

    public function editAction() {
        $id = Request::getQuery('id','int');
        $signalVender = new SignalProducer();
        $data = $signalVender->findOne($id);
        $this->initFormView();        
        $messages = [];
        if (Request::isPost()) {
            $data=Request::getPost();
            $validator=SignalProducer::makeValidator($data);
            if($validator->passes()) {
                if($signalVender->modifyData($data)) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }else{
                foreach($validator->messages()->all() as $msg) {
                    $messages[]=$msg;
                }
            }
        }
        
        View::setMainView('layouts/add');
        View::setVars(compact('messages','data'));
    }

    public function deleteAction() {
        $id = Request::getQuery('id','int');
        $signalVender = new SignalProducer();
        $return = $signalVender->deleteData($id);
        if($return) {
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function searchAction() {
    	$title = Request::get('title');
    	$value = Request::get('value');
    	$mess = array(
    			'title' => $title,
    			'value' => $value
    	);
    	
    	if($mess) {
    		$data = SignalProducer::searchData($mess);
    	}
    	if($mess['title'] == "" && $mess['value'] == "" ) {
			$this->response->redirect('signal_producer/index');	// 跳转到首页
		}
		
		View::pick('signal_producer/index');
		View::setVars(compact('mess','data'));
    }
    
}