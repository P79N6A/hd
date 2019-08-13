<?php

class LotteryChannelsController extends \BackendBaseController {
    const FILE_NOT_UPLOAD = 4;

    /**
     * Index action
     */
    public function indexAction() {
        $group_id = Request::get('group_id');
        $channel_id = Session::get("user")->channel_id;
        $data = LotteryChannels::findAllByGroup($channel_id , $group_id);
        View::setVars(compact('data'));
    }

    /**
     * Add action
     */
    public function addAction() {
        $model = new LotteryChannels();
        $group_id = Request::get('group_id');
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = LotteryChannels::makeValidator($data);

            $background = $this->validateAndUpload($messages, 0);//缩略图上传地址
            if ($background != 'nopicture') {
                $data['background'] = $background;
            }
            $style = $this->validateAndUpload($messages, 1);//头图上传地址
            if ($style != 'nopicture') {
                $data['style'] = $style;
            }

            $data['channel_id'] = Session::get("user")->channel_id;
            if (!$validator->fails()) {
                if (!$this->isFileTypeValid()) {
                    $messages[] = Lang::_('please upload valid image');
                }
                else {
                    $data['created_at'] = $data['updated_at'] = time();
                    if(!$model->save($data)){
                        foreach ($model->getMessages() as $msg) {
                            $messages[] = $msg->getMessage();
                        }
                    }else{
                        LotteryChannels::refreshLotteryChannel();
                        $messages[] = Lang::_('success');
                    }
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $lottery_group = LotteryGroup::findOne($group_id);
        View::setMainView('layouts/add');
        View::setVars(compact('model','messages','lottery_group'));
    }

    protected function uploadFile($file, $path="background") {
        $ext = $file->getExtension();
        $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/yao/'.$path);
        return $path;

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

    /**
     * Edit action
     */
    public function editAction() {
        $model = LotteryChannels::findFirst(Request::get('id', 'int'));
        $group_id = Request::get('group_id');
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $background = $this->validateAndUpload($messages, 0);//缩略图上传地址
            if ($background != 'nopicture') {
                $data['background'] = $background;
            }
            $style = $this->validateAndUpload($messages, 1);//头图上传地址
            if ($style != 'nopicture') {
                $data['style'] = $style;
            }

            $validator = LotteryChannels::makeValidator($data,$model->id);
            if (!$validator->fails()) {
                $data['updated_at'] = time();
                if(!$model->update($data)){
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                }else{
                    LotteryChannels::refreshLotteryChannel();
                    $messages[] = Lang::_('success');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $lotteries = Lotteries::getLotteryByHuichang($model->id);
        $lotteryPrize = array();
        foreach($lotteries as $lottery){
            $lotteryPrize = array_merge($lotteryPrize,LotteryPrizes::getAllByLottery($lottery['id'])->toArray());
        }
        $lottery_group = LotteryGroup::findOne($group_id);
        View::setMainView('layouts/add');
        View::setVars(compact('model','messages','lottery_group','lotteries','lotteryPrize'));
    }

     /**
     * Delete action
     */
    public function deleteAction() {
        $model = LotteryChannels::findFirst(Request::get('id', 'int'));
        $code = 200;
        if (empty($model)) {
            $msg = Lang::_('failed');
        } else {
            if (Lotteries::getLotteryByChannel($model->id)) {
                $msg = Lang::_('Had Data');
                $code = 400;
            } else {
                $model->delete();
                $msg = Lang::_('success');
            }
        }
        $this->_json([], $code, $msg);
    }

    protected function validateAndUpload(&$messages,$pos=0) {
        $path = '';
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[$pos];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), 'lottery');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif($error == 4) {
                $path = 'nopicture';
            } else {
//                $messages[] = Lang::_('unknown error');
            }
        } else {
//            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }

}