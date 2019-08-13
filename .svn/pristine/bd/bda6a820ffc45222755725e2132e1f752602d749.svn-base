<?php
    class SmsPipleController extends  \BackendBaseController
    {
        public function indexAction() {
            $data = SmsPiple::findAll();
            View::setVars(compact('data'));
        }

        public  function  addAction()
        {
            $messages = [];
            if(request::isPost()) {
                $data = $this->request->getPost();
                $modelpiple = new SmsPiple();
                if($modelpiple->create($data)) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }                
            }
            View::setMainView('layouts/add');
            View::setVars(compact('messages'));
        }


        public  function  editAction()
        {
            $messages = [];
            $id = Request::getQuery('id','int');
            if(request::isPost()) {
                $data = $this->request->getPost();
                $modelpiple = new SmsPiple($data['id']);
                if($modelpiple->save($data)) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }
            $piple = SmsPiple::getOne($id);
            View::setMainView('layouts/add');
            View::setVars(compact('piple','messages'));
        }
        
        
        
    }