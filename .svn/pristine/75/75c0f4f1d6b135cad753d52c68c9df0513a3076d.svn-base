<?php
/**
 *  直播管理 码率配置
 *  controller signal_rates
 *  @author     cjh
 *  @created    2016-12-28
 *  @param id, rate_type, rate_name, rate_kpbs, rate_weight, rate_unit
 */

class SignalRatesController extends \BackendBaseController {

    public function indexAction() {

        $page = Request::getQuery('page','int','1');
        $signalRates = new SignalRates();
        $data = $signalRates->findAllData($page);
        
        View::setVars(compact('data'));
    }

    public function addAction() {
        if (Request::isPost()) {
			$inputs=Request::getPost();
			$validator = SignalRates::makeValidator($inputs);
			if($validator->passes()) {
				$signalRates = new SignalRates();
				if($signalRates->createData($inputs)) {
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
        $signalRate = new SignalRates();
        $data = $signalRate->findOne($id);
        $this->initFormView();        
        $messages = [];
        if (Request::isPost()) {
            $data=Request::getPost();
            $validator=SignalRates::makeValidator($data);
            if($validator->passes()) {
                if($signalRate->modifyData($data)) {
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
        View::setVars(compact('messages','data'));
    }

    public function deleteAction() {
        $id = Request::getQuery('id','int');
        $signalRates = new SignalRates();
        $return = $signalRates->deleteData($id);
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
    	$unit = Request::get('unit');
    	$mess = array(
    			'title' => $title,
    			'value' => $value,
    			'unit' => $unit
    	);
    	
    	if($mess) {
    		$data = SignalRates::searchData($mess);
    	}
    	if($mess['title'] == "" && $mess['value'] == "" && $mess['unit'] == "" ) {
			$this->response->redirect('signal_rates/index');	// 跳转到首页
		}
		
		View::pick('signal_rates/index');
		View::setVars(compact('mess','data'));
    }
    
}