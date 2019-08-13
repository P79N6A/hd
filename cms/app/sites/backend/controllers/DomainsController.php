<?php
/**
 *  域名管理 xy
 *  model domains
 *  @created    2015-11-16
 */

class DomainsController extends \BackendBaseController {

    public function indexAction() {
    	$data = Domains::findAll();
        View::setVars(compact('data'));
    }

    /**
     * Add action
     */

    public function addAction() {
        $model = new Domains();
        $messages = [];
        $father_id = 0;
        $channel_id = Session::get('user')->channel_id;
        if (Request::isPost()) {
            $data = Request::getPost();
            $data['channel_id'] = $channel_id;

            $validator = Domains::makeValidator($data);
            if (!$validator->fails()) {
                $data['created_at'] = $data['updated_at'] = time();
                $data['status'] = 1;
                if(!$model->save($data)){
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                }else{
                    Domains::findFirst($model->id);
                    $messages[] = Lang::_('success');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $service_type = Domains::getServiceTypes();
        View::setMainView('layouts/add');
        View::setVars(compact('father_id','messages','service_type'));
    }

    /**
     * edit action
     */
    public function editAction() {
    	$model = Domains::findFirst(Request::get('id', 'int'));
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = Domains::editValidator($data);
            if (!$validator->fails()) {
                $data['updated_at'] = time();
                if (!$model->update($data)) {
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    $messages[] = Lang::_('success');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }

        $category = Category::getSingleOne($model->category_id);
        if($category)
            $parents = $category->getParents();
        $parentcount =  count($parents);

        $service_type = Domains::getServiceTypes();
        View::setMainView('layouts/add');
        View::setVars(compact('category', 'parents', 'parentcount','messages','model','service_type'));
    }

    /**
     * 审核域名
     */
     public function lockAction(){
        $id = Request::get("id");
        $msg = Domains::lock($id);
        $msg = $msg ? $this->_json([], 200) : $this->_json(400, Lang::_('error'));
        echo $msg;
        exit;
    }
     
 

}