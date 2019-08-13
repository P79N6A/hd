<?php

class LotteryGroupController extends \BackendBaseController {
    const FILE_NOT_UPLOAD = 4;
    /**
     * Index action
     */
    public function indexAction() {
        $data = LotteryGroup::findAll();
        View::setVars(compact('data'));
    }

    /**
     * Add action
     */
    public function addAction() {
        $model = new LotteryGroup();
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $data['channel_id'] = Session::get('user')->channel_id;
            $validator = LotteryGroup::makeValidator($data);
            if (!$validator->fails()) {
                DB::begin();
                $thumb = $this->validateAndUpload($messages, 0);//缩略图上传地址
                if ($thumb != 'nopicture') {
                    $data['thumb'] = $thumb;
                }
                $top_banner = $this->validateAndUpload($messages, 1);//头图上传地址
                if ($top_banner != 'nopicture') {
                    $data['top_banner'] = $top_banner;
                }

                $data['open_time'] = strtotime($data['open_time']);
                $data['close_time'] = strtotime($data['close_time']);
                $data['created_at'] = $data['updated_at'] = time();
                $lottery_id = $model->saveGetId($data);
                if(!$lottery_id){
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                    DB::rollback();
                }else{
                    $new_model = new Data();
                    $user = Session::get('user');
                    $data_model = [];
                    $data_model['channel_id'] = $user->channel_id;
                    $data_model['title'] = $model->name;
                    $data_model['intro'] = $model->intro;
                    $data_model['thumb'] = $model->thumb;
                    $data_model['created_at'] = time();
                    $data_model['updated_at'] = time();
                    $data_model['author_id'] = $user->id;
                    $data_model['author_name'] = $user->name;
                    $data_model['status'] = 1;
                    $data_model['partition_by'] = date('Y');
                    if (!$data_id = $new_model->doSave($data_model, Data::getAllowed(), 'lottery', $lottery_id)) {
                        $messages[] = '媒资创建不成功';
                    }

                    $new_id = Request::getQuery('id');
                    if(!$new_id){
                        $messages[] = '对应新闻不存在';
                    }
                    $new_model = Data::getById($new_id, Auth::user()->channel_id);
                    if(!empty(Data::getDataDataIdByType($new_model->data_data,'lottery'))){
                        $messages[] = '已存在对应摇奖';
                    }
                    $data_data = json_decode($new_model->data_data, true);
                    $data_data[] = $data_id;
                    $new_model->data_data = json_encode($data_data);
                    if (!$new_model->update()) {
                        $messages[] = '添加到对应新闻失败';
                    }

                    if(!empty($messages)) {
                        DB::rollback();
                    }else{
                        DB::commit();
                        $messages[] = Lang::_('success');
                    }
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $model->open_time = date('Y-m-d H:i');
        $model->close_time = date('Y-m-d H:i',strtotime('+1 day'));
        View::setMainView('layouts/add');
        View::setVars(compact('model','messages'));
    }

    /**
     * Edit action
     */
    public function editAction() {
        $data_id = Request::get('data_id', 'int');
        $data_lottery = Data::getMediaByData($data_id);
        $data_model = $data_lottery[0];
        $model = $data_lottery[1];
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = LotteryGroup::makeValidator($data);
            if (!$validator->fails()) {
                DB::begin();
                $thumb = $this->validateAndUpload($messages, 0);//缩略图上传地址
                if ($thumb != 'nopicture') {
                    $data['thumb'] = $thumb;
                }
                $top_banner = $this->validateAndUpload($messages, 1);//头图上传地址
                if ($top_banner != 'nopicture') {
                    $data['top_banner'] = $top_banner;
                }
                $data['open_time'] = strtotime($data['open_time']);
                $data['close_time'] = strtotime($data['close_time']);
                $data['updated_at'] = time();

                if (!$model->update($data)) {
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                    DB::rollback();
                } else {
                    $user = Session::get('user');
                    $data_model->channel_id = $user->channel_id;
                    $data_model->title = $model->name;
                    $data_model->intro = $model->intro;
                    $data_model->thumb = $model->thumb;
                    $data_model->created_at = time();
                    $data_model->updated_at = time();
                    $data_model->author_id = $user->id;
                    $data_model->author_name = $user->name;
                    $data_model->status = 1;
                    $data_model->partition_by = date('Y');
                    if (!$data_model->update()) {
                        $messages[] = '媒资修改不成功';
                    }

                    if(!empty($messages)) {
                        DB::rollback();
                    }else{
                        DB::commit();
                        $messages[] = Lang::_('success');
                    }
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $model->open_time = date('Y-m-d H:i', $model->open_time);
        $model->close_time = date('Y-m-d H:i', $model->close_time);
        $lottery_channel = LotteryChannels::listLotteryChannelByGroup($model->id);
        $lotteries = array();
        $lotteryPrize = array();
        foreach($lottery_channel as $channel){
            $lotteries_one = Lotteries::getLotteryByHuichang($channel['id']);
            $lotteries = array_merge($lotteries,$lotteries_one);
            foreach($lotteries_one as $lottery){
                $lotteryPrize = array_merge($lotteryPrize,LotteryPrizes::getAllByLottery($lottery['id'])->toArray());
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('model', 'messages','lottery_channel','lotteries','lotteryPrize'));
    }

    /**
     * Delete action
     */
    public function deleteAction() {
        $id = Request::get('id', 'int');
        $model = LotteryGroup::findFirst($id);
        $code = 200;
        if (empty($model)) {
            $msg = Lang::_('failed');
        }
        if (Lotteries::checkGroup($id)) {
            $msg = Lang::_('Forbid Edit');
            $code = 400;
        }else{
            $model->delete();
            $msg = Lang::_('success');
        }
        $this->_json([], $code, $msg);
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

    protected function uploadFile($file) {
        $ext = $file->getExtension();
        $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/lgroup');
        return $path;

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