<?php
/**
 * Created by PhpStorm.
 * User: cyc
 * Date: 2015/11/5
 * Time: 9:44
 */

class AssetController extends \BackendBaseController {
    public function indexAction() {
        $user = Session::get('user')->toarray();
        $data = Asset::findAll($user['id']);
        View::setVars(compact('data'));
    }

    public function createAction() {
        $data = '';
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $user = Session::get('user')->toarray();
            $data['admin_id']=$user['id'];
            $data['time']=strtotime($data['time']);
            $validator = Asset::makeValidator($data);
            if (!$validator->fails()) {
                $ass= new Asset();
                $result = $ass->modifyAsset($data);
                if ($result) {
                    $messages[] = Lang::_('success');
                } else {
                    $messages[] = Lang::_('error');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }


    public function modifyAction() {
        $data = '';
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $user = Session::get('user')->toarray();
            $data['admin_id']=$user['id'];
            $data['time']=strtotime($data['time']);
            $validator = Asset::makeValidator($data);
            if (!$validator->fails()) {
                $id=$data['id'];
                $ass= Asset::getOneAsset($id);
                $result = $ass->modifyAsset($data);
                if ($result) {
                    $messages[] = Lang::_('success');
                } else {
                    $messages[] = Lang::_('error');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        if (Request::getQuery()) {
            $id = Request::getQuery('id', 'int');
            $data = Asset::getOneAsset($id);
        }
        View::setVars(compact('messages','data'));
        View::setMainView('layouts/add');
    }

    public function deleteAction() {
        $id=$this->request->getQuery("id","int");
        $return=Asset::deleteAsset($id);
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

}