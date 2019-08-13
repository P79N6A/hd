<?php
/**
 * Created by PhpStorm.
 * User: cyc
 * Date: 2015/11/4
 * Time: 9:51
 */

class SalaryController extends \BackendBaseController {

    public function indexAction() {
        $user = Session::get('user')->toarray();
        $data = Salary::findAll($user['id']);
        View::setVars(compact('data'));
    }

    public function createAction() {
        $data = '';
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $user = Session::get('user')->toarray();
            $data['admin_id']=$user['id'];
            $validator = Salary::makeValidator($data);
            if (!$validator->fails()) {
                $sal= new Salary();
                $result = $sal->createSalary($data);
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
            $validator = Salary::makeValidator($data);
            if (!$validator->fails()) {
                $id=$data['id'];
                $sal= Salary::getOneSalary($id);
                $result = $sal->modifySalary($data);
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
            $data = Salary::getOneSalary($id);
        }
        View::setVars(compact('messages','data'));
        View::setMainView('layouts/add');
    }

    public function deleteAction() {
        $id=$this->request->getQuery("id","int");
        $return=Salary::deleteSalary($id);
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

}
