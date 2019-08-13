<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/4/27
 * Time: 21:04
 */
class SmsTemplateController extends \BackendBaseController
{
    public  function indexAction()
    {
        $data = SmsTemplate::findAll();
        View::setVars(compact('data'));
    }

    public function addAction()
    {
        $messages = [];
        if(request::isPost()) {
            $data = $this->request->getPost();
            $data['create_at'] = time();
            $data['status'] = -1;
            $model = new SmsTemplate();
            if($model->create($data)) {
                $messages[] = Lang::_('success');
            }else{
                $messages[] = Lang::_('failed');
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public  function editAction()
    {
        $messages = [];
        $id = Request::getQuery('id','int');
        if(request::isPost()) {
            $data = $this->request->getPost();
            $model = new SmsTemplate($data['id']);
            if($model->save($data)) {
                $messages[] = Lang::_('success');
            }else{
                $messages[] = Lang::_('failed');
            }
        }
        $template = SmsTemplate::getOne($id);
        View::setMainView('layouts/add');
        View::setVars(compact('template','messages'));
    }


    public  function auditAction()
    {
        $messages = [];
        $id = Request::getQuery('id','int');
        if(request::isPost()) {
            $data = $this->request->getPost();
            $model = SmsTemplate::findFirst($data['id']);
            $model->status = $data['status'];
            if($model->save()) {
                $messages[] = Lang::_('success');
            }else{
                $messages[] = Lang::_('failed');
            }
        }
        $template = SmsTemplate::getOne($id);
        View::setMainView('layouts/add');
        View::setVars(compact('template','messages'));
    }
    
    
    


    public  function confAction()
    {
        $messages = [];
        $id = Request::getQuery('id','int');
        if(request::isPost()) {
            $data = $this->request->getPost();
            $model = SmsTemplate::findFirst($data['id']);
            $params = array(
                'esur'=>array(
                    'itemno'=>$data['esur_itemno'],
                    'params'=>$data['esur_params']),
                'netease'=>array(
                    'itemno'=>$data['netease_itemno'],
                    'params'=>$data['netease_params']));

            $model->param = json_encode($params);
            if($model->save()) {
                $messages[] = Lang::_('success');
            }else{
                $messages[] = Lang::_('failed');
            }
        }
        $template = SmsTemplate::getOne($id);
        $conf = json_decode($template->param,true);
        View::setMainView('layouts/add');
        View::setVars(compact('template','messages','conf'));
    }


}