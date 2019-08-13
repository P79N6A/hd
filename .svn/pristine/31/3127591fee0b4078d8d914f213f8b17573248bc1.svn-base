<?php

use GenialCloud\Exceptions\DatabaseTransactionException;

/**
 * 文章管理
 *
 * @author     Xue Wei
 * @created    2015-9-18
 */
class MediaAlbumsController extends MediaBaseController {

    protected $urlName = 'media_albums';

    protected $type = 'album';

    protected function doUpload() {
        $path = '';
        $filename = '';
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                $filename = $file->getName();
                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/albums');
                } else {
                    $error = Lang::_('please upload valid poster image');
                }
            } elseif($error == 4) {
                $error = Lang::_('please choose upload poster image');
            } else {
                $error = Lang::_('unknown error');
            }
        } else {
            $error = Lang::_('please choose upload poster image');
        }
        return [$path, $filename, $error];
    }

    /**
     * 删除相册图片
     */
    public function removeImageAction() {

        if($this->denySystemAdmin()) {
            return true;
        }

        $album_id = Request::get('album_id', 'string', '');
        $id = (int)Request::get('id', 'int', '');
        $msg = 'ok';
        try {
            if(!$album_id || !$id) {
                throw new Exception(Lang::_('not enough parameters'));
            }
            $album = Album::findWithChannel($album_id, Auth::user()->channel_id);
            if(!$album) {
                throw new Exception(Lang::_('invalid album'));
            }
            $image = AlbumImage::query()
                ->andCondition('id', $id)
                ->andCondition('album_id', $album_id)
                ->first();
            if(!$image) {
                throw new Exception(Lang::_('picture not exists'));
            }
            DB::begin();
            if(!$image->delete()) {
                DB::rollback();
                throw new Exception(Lang::_('delete data failed'));
            }
            Oss::deleteFile($image->path);
            DB::commit();
        } catch(Exception $e) {
            $msg = $e->getMessage();
        }
        if($msg!='ok') {
            $this->jsonp(['error' => $msg]);
        }
        exit;
    }

    /**
     * 删除临时相册文件
     */
    public function removeTmpImageAction() {

        if($this->denySystemAdmin()) {
            return true;
        }

        $token = Request::get('token', 'string', '');
        $id = (int)Request::get('id', 'int', '');
        $msg = 'ok';
        try {
            if(!$token || !$id) {
                throw new Exception(Lang::_('not enough parameters'), 404);
            }
            $r = AlbumTmp::query()
                ->andCondition('code', $token)
                ->andCondition('id', $id)
                ->first();
            if(!$r) {
                throw new Exception(Lang::_('invalid temp file'), 403);
            }
            DB::begin();
            if(!$r->delete()) {
                DB::rollback();
                throw new Exception(Lang::_('delete data failed'), 500);
            }
            Oss::deleteFile($r->path);
            DB::commit();
        } catch(Exception $e) {
            $msg = $e->getMessage();
        }
        if($msg!='ok') {
            $this->jsonp(['error' => $msg]);
        }
        exit;
    }

    protected function responseUpload($path, $intro, $id, $token=null, $album_id=null) {
        $url = Oss::url($path);
        echo json_encode([
            'initialPreview' => [
                "<img style='height:160px' src='{$url}' class='file-preview-image' id='{$id}'>",
            ],
            'initialPreviewConfig' => [
                [
                    'caption' => $intro,
                    'width' => '120px',
                    'url' =>  $album_id?
                        Url::get('media_albums/removeimage', ['id' => $id, 'album_id' => $album_id]):
                        Url::get('media_albums/removetmpimage', ['id'=>$id, 'token' => $token])
                ],
            ],
            'append' => true,
        ]);
        exit;

    }

    /**
     * 新增相册图片上传
     */
    public function tmpUploadAction() {

        if($this->denySystemAdmin()) {
            return true;
        }

        $token = Request::get('token', 'string', '');
        $intro = Request::getPost('intro', 'string', '');
        $id = 0;
        if(!$token) {
            $this->jsonp(['error' => Lang::_('invalid token')]);
        }
        list($path, , $error) = $this->doUpload();
        if(!$error) {
            $model = new AlbumTmp;
            $model->path = $path;
            $model->intro = $intro;
            $model->code = $token;
            $model->author_id = Auth::user()->id;
            $model->created_at = time();
            if(!$id = $model->saveGetId()) {
                $msgs = $model->getMessages();
                foreach($msgs as $msg) {
                    $messages[] = $msg->getMessage();
                }
                $error = implode(', ', $messages);
            }
        }
        if($error) {
            $this->jsonp(compact('error'));
        }
        $this->responseUpload($path, $intro, $id, $token);
    }

    /**
     * 编辑相册图片上传
     */
    public function uploadAction() {

        if($this->denySystemAdmin()) {
            return true;
        }

        $album_id = Request::get('album_id', 'int', '');
        $intro = Request::getPost('intro', 'string', '');
        $id = 0;
        $path = '';
        if($album_id) {
            $album = Album::findWithChannel($album_id, Auth::user()->channel_id);
            if($album) {
                list($path, , $error) = $this->doUpload();
                if(!$error) {
                    $model = new AlbumImage();
                    $r = [
                        'path' => $path,
                        'intro' => $intro,
                        'sort' => 0,
                    ];
                    if(!$id = $model->saveOne($album_id, $r, date('Y', $album->created_at))) {
                        $msgs = $model->getMessages();
                        foreach($msgs as $msg) {
                            $messages[] = $msg->getMessage();
                        }
                        $error = implode(', ', $messages);
                    }
                }
            }
        } else {
            $error = Lang::_('invalid album');
        }
        if($error) {
            $this->jsonp(compact('error'));
        }
        $this->responseUpload($path, $intro, $id, null, $album_id);
    }

    /**
     * 新增相册
     */
    public function addAction() {

        if($this->denySystemAdmin()) {
            return true;
        }

        $this->initFormView();

        $messages = [];
        $images = [];
        $model = new Album;

        $token = '';
        if(Request::isPost() && $token = Request::getPost('token', 'string', '')) {
            $images = AlbumTmp::query()
                ->andCondition('code', $token)
                ->andCondition('author_id', Auth::user()->id)
                ->execute();
        }//dd($images);

        if(!$token) {
            $token = md5(uniqid(str_random()));
        }

        if(Request::isPost()) {
            $data = $this->preProcessData(Request::getPost());
            $vData = Data::makeValidator($data);
            if(!$vData->fails() && $thumb = $this->validateAndUpload($messages)) {
                $model->thumb = $data['thumb'] = $thumb;
                $data['created_at'] = time();
                $data['updated_at'] = time();
                $data['author_id'] = Auth::user()->id;
                $data['author_name'] = Auth::user()->name;
                $data['channel_id'] = Auth::user()->channel_id;
                //DB 事务
                DB::begin();
                try {
                    //存新闻
                    if(!$id = $model->saveGetId($data)) {
                        $this->throwDbE('model');
                    }
                    $data['partition_by'] = date('Y');
                    $dModel = new Data();
                    if(!$data_id = $dModel->doSave($data, Data::getAllowed(), 'album', $id)) {
                        $this->throwDbE('dModel');
                    }
                    //私有分类发布
                    $msgs = PrivateCategoryData::publish($dModel->id, $dModel->partition_by);
                    if(empty($msgs)) {
                        DB::commit();
                        foreach($images as $tmp) {
                            $m = new AlbumImage();
                            $r = $tmp->toArray();
                            $m->saveOne($id, $r, $data['partition_by']);
                            $tmp->delete();
                        }
                        $this->alert(Lang::_('success'), 'success');
                        return true;
                    } else {
                        DB::rollback();
                        $messages = array_merge($messages, $msgs);
                    }
                } catch(DatabaseTransactionException $e) {
                    DB::rollback();
                    if($e->getCode() === 0) {
                        $_m = $e->getMessage();
                        $msgs = $$_m->getMessages();
                        foreach($msgs as $msg) {
                            $messages[] = $msg->getMessage();
                        }
                    } else {
                        $messages[] = $e->getMessage();
                    }
                }
            } else {
                foreach($vData->messages()->all() as $msg) {
                    $messages[] = $msg;
                }
            }
        }

        $media_type = PrivateCategory::MEDIA_TYPE_ALBUM;
        $privateCategoryData = false;
        View::setVars(compact('model', 'messages', 'images', 'token', 'media_type', 'privateCategoryData'));
    }

    /**
     * 编辑相册
     */
    public function editAction() {

        if($this->denySystemAdmin()) {
            return true;
        }

        $model = Album::findWithChannel(Request::get('id', 'int'), Auth::user()->channel_id);
        if(!$model) {
            abort(404);
        }
        $r = Data::getByMedia($model->id, 'album');
        $r->assignToMedia($model);
        if(!$model) {
            $this->alert(Lang::_('invalid request'));
        } else {
            $this->initFormView();
            $messages = [];
            if(Request::isPost()) {

                //修改缓存时间
                $last_modified_key = "media/" . $r->type . ":" . $r->id;
                F::_clearCache($last_modified_key, $r->channel_id);

                $data = $this->preProcessData(Request::getPost());
                $vData = Data::makeValidator($data, $model->id);
                if(!$vData->fails() && $thumb = $this->validateAndUpload($messages)) {
                    $model->thumb = $data['thumb'] = $thumb;
                    $data['updated_at'] = time();
                    //DB 事务
                    DB::begin();
                    try {
                        if(!$model->update($data, Album::safeUpdateFields())) {
                            $this->throwDbE('model');
                        }
                        if(!$r->update($data, Data::safeUpdateFields())) {
                            $this->throwDbE('r');
                        }
                        //私有分类发布
                        $msgs = PrivateCategoryData::publish($r->id, $r->partition_by);
                        if(empty($msgs)) {
                            DB::commit();
                            $this->alert(Lang::_('success'), 'success');
                            return true;
                        } else {
                            DB::rollback();
                            $messages = array_merge($messages, $msgs);
                        }
                    } catch(DatabaseTransactionException $e) {
                        DB::rollback();
                        if($e->getCode() === 0) {
                            $_m = $e->getMessage();
                            $msgs = $$_m->getMessages();
                            foreach($msgs as $msg) {
                                $messages[] = $msg->getMessage();
                            }
                        } else {
                            $messages[] = $e->getMessage();
                        }
                    }
                } else {
                    foreach($vData->messages()->all() as $msg) {
                        $messages[] = $msg;
                    }
                }
            }
            $images = AlbumImage::find(['album_id='.$model->id,'order'=>'sort desc']);

            $media_type = PrivateCategory::MEDIA_TYPE_ALBUM;

            $privateCategoryData = privateCategoryData::getIdByData($r->id);
            View::setVars(compact('model', 'messages', 'images', 'privateCategoryData', 'media_type'));
        }

    }
    /**
     * 相册排序
     */
    public function sortAction(){
        $ids = Request::getPost("ids");
        $album_id = Request::get("album_id");
        if(empty($ids) || !is_array($ids)){
            $this->echoExit($this->_json([],400,Lang::_('only upload can resort')));
        }
        if($album_id) {
            $msg = AlbumImage::reSort($ids);
        }
        else {
            $msg = AlbumTmp::reSort($ids);
        }

        if(empty($msg)){
            $message = $this->_json([],200);
        }else{
            $message = $this->_json(json_encode($msg),400,'error');
        }
        $this->echoExit($message);
    }
    /**
     * 简介修改
     */
    public function eintroAction(){
        $id = Request::get("id");
        $intro = Request::get('intro', 'string', '');
        $album_id = Request::get("album_id");
        if(!$id || !is_numeric($id)){
            $this->echoExit($this->_json([],200,Lang::_('id required')));
        }
        if(!$album_id || !is_numeric($album_id)){
            $this->echoExit($this->_json([],200,Lang::_('album_id required')));
        }
        $model = AlbumImage::findFirst($id);
        $model->intro = $intro;
        $msg = $model->update();

        if($msg){
            $message = $this->_json([],200);
        }else{
            $message = $this->_json(json_encode($msg),400,'error');
        }
        $this->echoExit($message);
    }
    public function ajaxGetListAction(){
        $data_id = Request::getQuery("data_id","int","0");
        $data = Data::findFirstOrFail($data_id);
        $album_data = Album::getWithImages($data->source_id);
        $album_data['data_id'] = $data_id;
        if($album_data){
            echo json_encode(["success"=>true,"data"=>$album_data]);
        }else{
            echo json_encode(["success"=>false]);
        }
        exit();
    }
    private function echoExit($msg){
        echo $msg;
        exit;
    }

}