<?php


class ElementController extends \BackendBaseController{
    
    public function indexAction(){
        if(empty($moduleid))
            $moduleid = Request::getQuery("id", "int");
        if(!$moduleid) {
            redirect(Url::get("module/index"));
        }
        $modulex = AuthModule::getOne($moduleid);        
        $data = AuthElement::getElementList($modulex->child);
        // 原子排序
        //$this->sortElements($moduleid);//这段代码，会导致菜单乱序
        View::setVars(compact('modulex', 'data'));
    }
    
    /**
     * 添加原子
     */    
    public function addAction() {
        if(empty($moduleid))
            $moduleid = Request::getQuery("moduleid", "int");
        if(!$moduleid) {
            redirect(Url::get("module/index"));
        }

        View::setMainView('layouts/add');
        View::setVars(compact('moduleid'));
    }

    public function editAction() {
        if(empty($moduleid))
            $moduleid = Request::getQuery("moduleid", "int");
        if(!$moduleid) {
            redirect(Url::get("module/index"));
        }
        if(empty($elementid))
            $elementid = Request::getQuery("id", "int");
        if(!$elementid) {
            redirect(Url::get("module/elementadd", ['id'=>$moduleid]));
        }
        $element = AuthElement::getOne($elementid);
        $modulex = AuthModule::getOne($moduleid);
        $botherelement = AuthElement::getElementList($modulex->child);

        View::setMainView('layouts/add');
        View::setVars(compact('moduleid', 'element', 'botherelement'));
    }

    public function saveAction() {
        $savesuccess = false;
        if (Request::isPost()) {
            $msg = [];
            $data = Request::getPost();
            $elementid = Request::getPost("id", "int");
            if(!isset($data['auth_hide'])) $data['auth_hide'] = 1;
            if(!isset($data['belong'])) $data['belong'] = 0;

            $validator = AuthElement::makeValidator($data);            
            if (!$validator->fails()) {
                if($elementid) {//modify
                    $element = AuthElement::getOne($elementid);
                }
                else {//add
                    $element = new AuthElement();
                }
                $messages = $element->saveElement($data);
            }
            else {
                $messages = $validator->messages()->all();
            }
        }
        if($messages[0] == '成功') $savesuccess = true;
        View::setMainView('layouts/add');
        View::setVars(compact('savesuccess', 'messages'));
        View::pick('layouts/save');
    }

    /**
     * Delete action
     */
    public function deleteAction() {
        if(empty($moduleid))
            $moduleid = Request::getQuery("moduleid", "int");
        if(!$moduleid) {
            redirect(Url::get("module/index"));
        }
        $id = Request::get('id', 'int');

        $code = 200;
        $msg = Lang::_('failed');
        if ($id&&AuthElement::deleteElement(array('id'=>$id, 'moduleid'=>$moduleid))) {
            $msg = Lang::_('success');
        }
        $this->_json([], $code, $msg);
    }

    /**
     * 原子排序
     */
    protected function sortElements($moduleid) {
        $elementIds = AuthModule::getOne($moduleid);
        $sortData = AuthElement::sortElementByModuleChild($elementIds->child);
        $sortElementId = "";
        for($i = 0; $i < count($sortData); $i++) {
            if($i == count($sortData)-1) {
                $sortElementId =$sortElementId.$sortData[$i]['id'];
            }else {
                $sortElementId =$sortElementId.$sortData[$i]['id'].",";
            }
        }
        $elementIds->child = $sortElementId;
        $updateModule = new AuthModule();
        $updateModule->updateElementIds($elementIds);

    }
    
    
}