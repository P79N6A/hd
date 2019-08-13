<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/5/12
 * Time: 15:43
 */
class AdminGroupController extends \BackendBaseController
{
    public function indexAction()
    {
        $data = AdminGroup::findAll();
        View::setVars(compact('data'));
    }

    public function addAction()
    {
        $messages = [];
        if(request::isPost()) {
            $data = $this->request->getPost();
            if(empty($data['name']) || empty($data['indexname']))
            {
                $messages[] = Lang::_('param is empty');
            }else{
                $model = new AdminGroup();
                if($model->create($data)) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function editAction()
    {
        $messages = [];
        $id = Request::getQuery('id','int');

        if(request::isPost()) {
            $data = $this->request->getPost();
            $id = $data['id'];
            $model_group = AdminGroup::findOne($id);
            $model_group->name = $data['name'];
            $model_group->indexname = $data['indexname'];
            $model_group->desc = $data['desc'];
            if($model_group->save()) {
                $messages[] = Lang::_('success');
            }else{
                $messages[] = Lang::_('failed');
            }
        }
        $data = AdminGroup::findOne($id);
        View::setMainView('layouts/add');
        View::setVars(compact('data','messages'));
    }

    public function confAction()
    {

        $messages = [];
        $id = Request::getQuery('id','int');
        if(request::isPost()) {
            $data = $this->request->getPost();
        }
        View::setMainView('layouts/add');
        View::setVars(compact('data','messages'));
    }

    public  function kvlistAction()
    {
        $gid = Request::getQuery('gid','int');
        $data = AdminGroupKv::findList($gid);
        View::setVars(compact(array('data','gid')));

    }


    public function kvaddAction()
    {
        $gid = Request::getQuery('gid','int');

        if(request::isPost()) {
            $data = $this->request->getPost();
            if(empty($data['tag']) || empty($data['key']) || empty($data['value']))
            {
                $messages[] = Lang::_('param is empty');
            }else{
                $model = new AdminGroupKv();
                if($model->create($data)) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact(array('messages','gid')));
    }


    public function assignAction()
    {
        $messages = [];
        $uid = Request::getQuery('id','int');
        if(request::isPost()) {
            $data = $this->request->getPost();
            $uid = $data['uid'];
            $gids = $data['sel_group'];
            $kv = new Userconf();
            if($kv->updateGruop($uid,$gids)) {
                $messages[] = Lang::_('success');
            }else{
                $messages[] = Lang::_('failed');
            }
        }
        $usergrouplist = AdminGroup::getAll();
        $mygids = AdminConf::getUserGids($uid);
        View::setMainView('layouts/add');
        View::setVars(compact(array('messages','uid','usergrouplist','mygids')));
    }
}