<?php
/**
 *  版本管理
 *  model Version
 *  @author     kuangmail
 *  @created    2016-01-18
 *  
 */

class VersionController extends \BackendBaseController {


    public function indexAction() {
        $data = Version::findAll();
        View::setVars(compact('data'));
    }

    /**
     * 版本添加
     */
    public function addAction() {
        if (Request::isPost()) {
            $data = Request::getPost();
            
            $validator = Version::makeValidator($data);
            if (!$validator->fails()) {
                $data['updated_at'] = strtotime($data['updated_at']);;
                $version = new Version();
                $messages = $version->createVersion($data);
            }
            else {
                $messages = $validator->messages()->all();
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }
    
    /**
     * 版本编辑
     */
    public function editAction($version_id=0) {
        $version_id = Request::get("id", "int");
        if(!$version_id) {
            redirect(Url::get("version/add"));
        }
        $version = Version::getOne($version_id);
        if (Request::isPost()) {
            $data = Request::getPost();
            
            $validator = Version::makeValidator($data);

            if (!$validator->fails()) {
                $data['updated_at'] = strtotime($data['updated_at']);
                $messages = $version->modifyVersion($data);
            }
            else {
                $messages = $validator->messages()->all();
            }
        }
        
        View::setMainView('layouts/add');
        View::setVars(compact('version','messages'));
    }

    /**
     * 版本删除
     */
    public function deleteAction() {
        $version_id = $this->request->getQuery("id","int");
        $version = Version::findFirst($version_id);
        $return = $version->delete();
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

   

}
