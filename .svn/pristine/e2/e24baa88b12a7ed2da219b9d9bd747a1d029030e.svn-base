<?php

/**
 *  块 管理
 *  @author     Zhi Chen
 *  @created    2016-04-15
 *  
 */
class RunningManController extends \BackendBaseController {

	const FILE_NOT_UPLOAD = 4;

	public function indexAction(){
		$phase = RunningMan::findAllWithGuest();
		View::setVar('phase',$phase);
	}


	public function addPhaseAction(){
		$model = new RunningMan();
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = RunningMan::makeValidator($data);
            
            if (!$validator->fails()) {
                $data['createtime'] = time();
                if(!$model->save($data)){
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                }else{
                    $messages[] = Lang::_('success');
                    
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        View::setVars(compact('messages'));
	}

	public function editPhaseAction(){
		$model = RunningMan::findFirst(Request::get('id', 'int'));

        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = RunningMan::makeValidator($data);
            if (!$validator->fails()) {
                if(!$model->save($data)){
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                }else{
                    $messages[] = Lang::_('success');
                    
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $phase = $model->toArray();
        View::setMainView('layouts/add');
        View::setVars(compact('phase','messages'));
	}

	public function delPhaseAction() {
		$id = Request::get('id', 'int');
        $model = RunningMan::findFirst($id);
        $code = 200;
        if (empty($model)) {
        	$code = 400;
            $msg = Lang::_('failed');
        } else {
            foreach (RunningGuest::find("phase_id='{$id}'") as $guest) 
            	$guest->delete();

            $model->delete();
            $msg = Lang::_('success');
            
        }
        $this->_json([], $code, $msg);
    }

	public function addGuestAction(){
		$phase = RunningMan::findAllBySelect();
		View::setVar('phase',$phase);

		$model = new RunningGuest();
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = RunningGuest::makeValidator($data);
            if (!$this->isFileTypeValid()) {
                $messages[] = Lang::_('please upload valid image');
            }

            $uploadFile = '';
            $data['guest_img'] = "";

            $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'head_img');            
            if (!$isFileEmpty && !empty($uploadFile)) {
                $data['guest_img'] = $this->uploadFile($uploadFile);
            }
            
            if (!$validator->fails()) {
                if(!$model->save($data)){
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                }else{
                    $messages[] = Lang::_('success');
                    
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        View::setVars(compact('model','messages'));

	}

	public function editGuestAction(){
		$phase = RunningMan::findAllBySelect();
		View::setVar('phase',$phase);

		
		$model = RunningGuest::findFirst(Request::get('id', 'int'));
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = RunningGuest::makeValidator($data);
            if (Request::hasFiles() && !$this->isFileTypeValid()) {
                $messages[] = Lang::_('please upload valid image');
            }

            $uploadFile = '';

            $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'head_img');            
            if (!$isFileEmpty && !empty($uploadFile)) {
                $data['guest_img'] = $this->uploadFile($uploadFile);
            }
            
            if (!$validator->fails()) {
                if(!$model->save($data)){
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                }else{
                    $messages[] = Lang::_('success');
                    
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $guest = $model->toArray();
        View::setVars(compact('guest','messages'));

	}

	public function delGuestAction() {
		$id = Request::get('id', 'int');
        $model = RunningGuest::findFirst($id);
        $code = 200;
        if (empty($model)) {
        	$code = 400;
            $msg = Lang::_('failed');
        } else {
            $model->delete();
            $msg = Lang::_('success');
            
        }
        $this->_json([], $code, $msg);
    }

	private function isFileUploadEmpty(&$uploadFile, $name) {
        $uploadFile = '';
        if(Request::hasFiles()) {
            $files = Request::getUploadedFiles();
            foreach ($files as $file) {
                if ($file->getKey() == $name) {
                    $error = $file->getError();
                    if (!$error) {
                        $uploadFile = $file;
                    }
                    return $error == self::FILE_NOT_UPLOAD;
                }
            }
        }
        return true;
    }

    private function isFileTypeValid() {
        if (Request::hasFiles(true)) {
            $files = Request::getUploadedFiles(true);
            foreach ($files as $file) {
                $fileType = $file->getRealType();
                return in_array($fileType, ['image/jpg', 'image/jpeg', 'image/gif', 'image/png']);
            }
        }
        return true;
    }

    private function uploadFile($file, $path="background") {
        $ext = $file->getExtension();
        $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/yao/'.$path);
        return $path;

    }
}