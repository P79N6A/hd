<?php
/**
 *  电视节目单管理
 *  controller stationsSet
 *  @author     cjh
 *  @created    2016-8-31
 *
 *  @param 
 */

class StationsSetController extends \BackendBaseController {

	/**
	 * 页面加载
	 */
    public function indexAction() {
        $data = StationsSet::findAll();
        View::setVars(compact('data'));
    }
    
    /**
     * 新增站点
     */
    public function createAction() {
        $messages = [];
        if (Request::isPost()) {
            $inputs=Request::getPost();
            $stationsSet = new StationsSet();
           	$messages = $stationsSet->saveData($inputs);
         }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }
   
    /**
     * 删除站点
     */
    public function deleteAction(){
        
    	$id = Request::getPOST('id');
    	
        if (empty($id) || null == $id) {
        	$id=$this->request->getQuery("id","int");
        	if(empty($id) ){
        		$this->_json([], 404, D::apiError(4001));
        	}
        }
      	
        $return=StationsSet::deleteData($id);
        if($return){
        	$arr=array('code'=>200);
        }else{
        	$arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 修改站点
     */
    public function modifyAction(){
        $messages = [];
        $msg = true;				// 存数据表返回值，true，false
        if (Request::isPost()) {
            $inputs=Request::getPost();
          	
            $stationsSet = new StationsSet();
            $messages = $stationsSet->saveData($inputs);
        }
        
        $id=Request::getQuery('id','int');
        $data = StationsSet::getOne($id);
        
        View::setMainView('layouts/add');
        View::setVars(compact('messages','data'));
    }

}