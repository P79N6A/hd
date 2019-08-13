<?php

class LotteryGoodsController extends \BackendBaseController {

    /**
     * Index action
     */
    public function indexAction() {
        $data = LotteryGoods::findAll();
        View::setVars(compact('data'));
    }

    /**
     * Add action
     */
    public function addAction() {
        $model = new LotteryGoods();
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $data['channel_id'] = Session::get('user')->channel_id;
            $validator = LotteryGoods::makeValidator($data);
            if (!$validator->fails()) {
                $thumb = $this->validateAndUpload($messages);
                $data['thumb'] = $thumb;
                $data['overtime'] = $data['minute']*60 + $data['second'];
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
      
        View::setMainView('layouts/add');
        View::setVars(compact('model','messages'));
    }

    /**
     * Edit action
     */
    public function editAction() {
        $messages = [];
        $model = LotteryGoods::findFirst(Request::get('id', 'int'));
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = LotteryGoods::makeValidator($data);
            if (!$validator->fails()) {
                $thumb = $this->validateAndUpload($messages);
                $data['thumb'] = $thumb;
                $data['overtime'] = $data['minute']*60 + $data['second'];
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
        View::setMainView('layouts/add');
        View::setVars(compact('model', 'messages'));
    }

    /**
     * Delete action
     */
    public function deleteAction() {
        $id = Request::get('id', 'int');
        $model = LotteryGoods::findFirst($id);
        $code = 200;
        if (empty($model)) {
            $msg = Lang::_('failed');
        } 
        if (LotteryPrizes::checkGoods($id)) {
            $msg = Lang::_('Forbid Edit');
            $code = 400;
        }else{
            $model->delete();
            $msg = Lang::_('success');
        }
        $this->_json([], $code, $msg);
    }

    /**
     * @param $messages
     * @return string
     */
    protected function validateAndUpload(&$messages) {
        $path = '';
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/thumbnails');
                } else {
                    $messages[] = Lang::_('please upload valid poster image');
                }
            } elseif($error == 4) {
                $path = Request::getPost('thumb', null, '');
                if(!$path) {
                    $messages[] = Lang::_('please choose upload poster image');
                }
            } else {
                $messages[] = Lang::_('unknown error');
            }
        }
        return $path;
    }

}