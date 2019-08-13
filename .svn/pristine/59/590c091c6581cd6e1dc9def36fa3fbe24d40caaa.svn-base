<?php
/**
 *  专题管理
 *  model specials
 *  @author     xy
 *  @created    2015-12-3
 *  
 */

class SpecialsController extends \BackendBaseController {

    protected function preProcessData($data) {

        $processes = [
            'title' => ['strip_tags'],
            'intro' => ['strip_tags'],
        ];
        foreach($processes as $key => $ps) {
            if(isset($data[$key])) {
                foreach($ps as $p) {
                    $data[$key] = call_user_func_array($p, [$data[$key]]);
                }
            }
        }
        return $data;
    }


    public function indexAction() {
        $private_category_id = Request::getQuery("private_category_id", "int");
        if(isset($private_category_id)&&$private_category_id) {

            $data = Specials::query()
                ->columns(['Data.title', 'Data.id', 'Data.status','Data.thumb','Data.author_name','Specials.*','PrivateCategory.*'])
                ->leftjoin('Data', 'Data.source_id = Specials.id')
                ->leftJoin("PrivateCategoryData", "Data.id = PrivateCategoryData.data_id")
                ->leftJoin("PrivateCategory", "PrivateCategory.id = PrivateCategoryData.category_id")
                ->andwhere("Specials.channel_id= :channel_id: and PrivateCategoryData.category_id= :category_id: and Data.type = 'special'", ['category_id'=>$private_category_id, 'channel_id' =>Session::get('user')->channel_id])
                ->orderBy('Specials.updated_at desc, Specials.id desc')
                ->paginate(Specials::PAGE_SIZE, 'Pagination');
        }
        else {
            $data = Specials::query()
                ->columns(['Data.title', 'Data.id', 'Data.status','Data.thumb','Data.author_name','Specials.*','PrivateCategory.*'])
                ->leftjoin('Data', 'Data.source_id = Specials.id')
                ->leftJoin("PrivateCategoryData", "Data.id = PrivateCategoryData.data_id")
                ->leftJoin("PrivateCategory", "PrivateCategory.id = PrivateCategoryData.category_id")
                ->andwhere("Specials.channel_id= :channel_id: and Data.type = 'special'", ['channel_id' =>Session::get('user')->channel_id])
                ->orderBy('Specials.updated_at desc, Specials.id desc')
                ->paginate(Specials::PAGE_SIZE, 'Pagination');
        }
        View::setVars(compact('data'));
    }
    
    /**
     * 添加专题
     */
    public function addAction() {
        $model = new Specials();
        $data_data = new Data();
        $messages = [];
        $data = [];
        if (Request::isPost()) {
            $data = $this->preProcessData(Request::getPost());
            $validator = Specials::makeValidator($data);
            if(!$validator->fails()) {
                $thumb = $this->validateAndUpload($messages,'0');
                $banner = $this->validateAndUpload($messages,'1');
                $model->thumb = $data['thumb'] = $thumb;
                $model->banner = $data['banner'] = isset($banner) ? $banner : '';
                $admin = Session::get('user');
                $data['channel_id'] = $admin->channel_id;
                $data['author_id'] = $admin->id;
                $data['author_name'] = $admin->name;
                $data['created_at'] = $data['updated_at'] = time();
                $data['partition_by'] = date('Y');
                if($model->save($data)) {
                    if($data_data->doSave($data, Data::getAllowed(), 'special', $model->id, null)) {
                        $category_ids = $data['private_category_id'];
                        if(isset($category_ids[0])){
                            PrivateCategoryData::createAndModify($data_data->id,$category_ids[0]);
                        }
                        $messages[] = Lang::_('success');
                    }
                }
                if($data['template']){
                    $arr=explode(",",$data['template']);
                    foreach($arr as $k=>$v){
                        if($data[$v]){
                            $special_extra=new SpecialExtras();
                            $special_extra->special_id=$model->id;
                            $special_extra->name=$v;
                            $special_extra->value=$data[$v];
                            SpecialExtras::createSpecialExtras($special_extra);
                        }
                    }
                }
            }
            else {
                $messages = $validator->messages()->all();
            }
        }
        $media_type = PrivateCategory::MEDIA_TYPE_SPECIAL;
        $privateCategoryData = false;
        View::setMainView('layouts/add');
        View::setVars(compact('messages','model', 'media_type', 'privateCategoryData'));
    }
    
    /**
     * 编辑专题
     */
    public function editAction() {
        $id = Request::get("id", "int");
        $data = [];
        if(!$id) {
            redirect(Url::get("Specials/add"));
        }
        $model = Specials::findOne($id);
        $dModel = Data::query()->andCondition('type', 'special')->andCondition('source_id', $id)->first();
        if($model->specials->channel_id != Session::get('user')->channel_id) {
            $this->accessDenied();
        }
        if (Request::isPost()) {
            $data = $this->preProcessData(Request::getPost());
            $validator = Specials::makeValidator($data,$id);
            if (!$validator->fails()) {
                $thumb = $this->validateAndUpload($messages,'0');
                $data['thumb'] = isset($thumb) ? $thumb : $data['thumb'];
                $data['updated_at'] = time();
                $banner = $this->validateAndUpload($messages,'1');
                $data['banner'] = isset($banner) ? $banner : $data['banner'];
                $smodel = Specials::findFirst($id);
                if($smodel->update($data)) {
                    //更新data
                    if($dModel->update($data)) {
                        $messages[] = Lang::_('success');
                    }
                    $category_ids = $data['private_category_id'];
                    if(isset($category_ids[0])){
                        PrivateCategoryData::createAndModify($dModel->id,$category_ids[0]);
                    }
                    if($data['template']){
                        SpecialExtras::deleteAll($id);
                        $arr=explode(",",$data['template']);
                        foreach($arr as $k=>$v){
                            if($data[$v]){
                                $special_extra=new SpecialExtras();
                                $special_extra->special_id=$id;
                                $special_extra->name=$v;
                                $special_extra->value=$data[$v];
                                SpecialExtras::createSpecialExtras($special_extra);
                            }
                        }
                    }
                    $model = Specials::findOne($id); 
                }
                
            }
            else {
                $messages = $validator->messages()->all();
            }
        }

        $media_type = PrivateCategory::MEDIA_TYPE_SPECIAL;
        $privateCategoryData = PrivateCategoryData::getIdByData($dModel->id);


        $template = SpecialExtras::findSpecialExtras($model->specials->id);
        $model->private_category_id = PrivateCategoryData::getCategoryId($dModel->id);
        View::setMainView('layouts/add');
        View::setVars(compact('model','messages','template', 'media_type', 'privateCategoryData'));
    }

    /**
     * 删除专题
     */
    public function deleteAction() {
        $id = Request::get('id');
        $data = Specials::findFirst($id);
        $channel_id = Session::get('user')->channel_id;
        if($data->channel_id != $channel_id) {
            $this->accessDenied();
        }
        if (!empty($data) && $data->channel_id==Session::get("user")->channel_id ) {
            //$data->delete();
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }        
        exit;
    }

    /**
     * 专题内媒资的管理
     */
    public function mediaindexAction(){
        $special_id=Request::get('id','int');
        $parcel=SpecialCategoryData::findAllBySpecial($special_id);
        $special=Specials::findOne($special_id);
        $name=$special->title;
        View::setVars(compact('parcel','name','special_id'));
    }

    protected function validateAndUpload(&$messages,$i) {
        $path = '';
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[$i];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/logos');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif($error == 4) {
                $path = Request::getPost('oldlogo');
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }

    
}
