<?php
/**
 * Created by PhpStorm.
 * User: zhangyichi
 * Date: 2015/12/15
 * Time: 16:25
 */

class FeaturesController extends \BackendBaseController {

    public function indexAction(){
        $data = Features::findAll(Session::get('user')->channel_id);
        View::setVars(compact('data'));
    }

    public function modifyAction(){
        $id = Request::getQuery('id','int');
        $messages = [];
        $data = Features::findById($id);
        if(Request::isPost()){
            $input=Request::getPost();
            $validator = Features::makeValidator($input,$id);
            if ($validator->passes()) {
                if($input['channel_id'] != Session::get('user')->channel_id) {
                    $this->accessDenied();
                }
                $input['author_id']=Session::get('user')->id;
                $input['updated_at']=time();
                $input['category_id']=$input['category_id'][0];
                $input['region_id'] = intval($input['region_id']);
                $return=$data->features->modifyFeatures($input);
                if($return){
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }else {
                $messages = $validator->messages()->all();
            }
        }
        $categoryData = $data->features->category_id;
        View::setMainView('layouts/add');
        View::setVars(compact('data','categoryData','messages'));
    }

    public function createAction(){
        $id = Request::getQuery('id','int');
        $messages = [];
        if(Request::isPost()){
            $input=Request::getPost();
            if($input['type'] == 'live') {
            	$input['category_id'][0] = 0;
            }
            $validator = Features::makeValidator($input,$id);
            if ($validator->passes()) {
                $input['channel_id']=Session::get('user')->channel_id;
                $input['author_id']=Session::get('user')->id;
                $input['created_at']=time();
                $input['updated_at']=time();
                $input['region_id'] = intval($input['region_id']);
                $input['category_id']=$input['category_id'][0];
                $feature=new Features();
                $return=$feature->createFeatures($input);
                if($return){
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }else {
                $messages = $validator->messages()->all();
            }
        }
        $categoryData = 0;
        View::setMainView('layouts/add');
        View::setVars(compact('categoryData','messages'));
    }

    public function bindingAction(){
        $data_id = Request::getQuery('id','int');
        $feature_type =Request::getQuery('type','string',"category");
        $data_name = Data::getById($data_id,Auth::user()->channel_id);
        $data_name = $data_name->title;
        $messages = [];
        $data = FeaturedData::findByData($data_id);
        $data=$data->toarray();
        if(Request::isPost()){
            $input = Request::getPost();
            $validator = FeaturedData::makeValidator($input);
            if ($validator->passes()) {
                $feature_thumb = $this->validateAndUpload($messages);
                $input['feature_thumb'] = isset($feature_thumb) ? $feature_thumb : $input['feature_thumb'];
                $feature_arr=$input['feature_id'];
                foreach($data as $d){
                    if(in_array($d['feature_id'],$feature_arr)){
                       unset($feature_arr[array_search($d['feature_id'],$feature_arr)]);
                    }else{
                        FeaturedData::deleteFeaturedData($d['id']);
                        unset($data[array_search($d,$data)]);
                    }
                }
//                var_dump($data);var_dump($input);exit;
                foreach($data as $f){
                    $getOne = FeaturedData::findById($f['id']);
                    $data[0]['data_id']=$f['data_id']=$input['data_id'];
                    $data[0]['feature_title']=$f['feature_title']=$input['feature_title'];
                    $data[0]['feature_thumb']=$f['feature_thumb']=$input['feature_thumb'];
                    $data[0]['updated_at']=$f['updated_at']=time();
                    $data[0]['sort']=$f['sort'] = $input['sort']?:$data[0]['sort'];
                    $getOne->modifyData($f);
                }
                foreach($feature_arr as $f){
                    $category_id = Features::getCategoryId($f);
                    if($category_id>0) {
                        CategoryData::query()
                            ->andCondition('data_id', $input['data_id'])
                            ->andCondition('category_id', $category_id)
                            ->execute()
                            ->delete();
                    }
                    $new_featured = new FeaturedData();
                    $new_featured->feature_id=$f;
                    $new_featured->data_id=$input['data_id'];
                    $new_featured->feature_title=$input['feature_title'];
                    $new_featured->feature_thumb=$input['feature_thumb'];
                    $new_featured->created_at=time();
                    $new_featured->updated_at=time();
                    $new_featured->sort = $input['sort']?:1;
//                    var_dump($new_featured->sort);
                    $new_featured->save();
                    $getOne = FeaturedData::findById($new_featured->id);
                    $arr = $getOne->toarray();
                    $arr['sort'] = $new_featured->sort = $new_featured->id;
                    $getOne->modifyData($arr);
                    array_push($data,$new_featured->toarray());
                }
                $messages[] = Lang::_('success');
            }else {
                $messages = $validator->messages()->all();
            }
        }
        $featured_data = [];
        foreach($data as $f){
            array_push($featured_data,$f['feature_id']);
        }
        if($data) {
            $data = $data[0];
        }
        View::setMainView('layouts/add');
        View::setVars(compact('data_id','messages','featured_data','data','data_name','feature_type'));
    }

    public function approveAction() {
        $features_id = Request::getQuery('id','int');
        $return = Features::approveFeatures($features_id);
        if($return) {
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        }else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }
        exit;
    }

    /**
     * 专题内媒资的管理
     */
    public function mediaindexAction(){
        $feature_id=Request::get('id','int');
        $parcel=FeaturedData::findAllByFeature($feature_id);
        $name=Request::get('name','string');
        if($name&&$feature_id){
            View::setVars(compact('parcel','name','feature_id'));
        }else{
            redirect('/features/index');
        }
    }

    public function mediadeleteAction() {
        $featuredData_id = Request::getQuery('id','int');
        $return = FeaturedData::deleteFeaturedData($featuredData_id);
        if($return) {
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        }else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }
        exit;
    }
    
    /**
     * 手动拖动排序
     */
    public function mediasortAction() {
    	$ids = Request::get("ids");
    	$sorts = Request::get("sorts");
    	if((empty($ids) || !is_array($ids)) 
    	|| (empty($sorts) || !is_array($sorts))) {
    		 $this->echoExit($this->_json([],40,Lang::_('only top can resort')));
    	}else {
    		$ret = FeaturedData::sortBySorts($ids,$sorts);
    		if($ret) {
    			$this->echoExit(json_encode(['code' => '200', 'msg' => Lang::_('success')]));
    		}else {
    			$this->echoExit(json_encode(['code' => '400', 'msg' => Lang::_('error')]));
    		}
    	}
    }

    private function echoExit($msg){
        echo $msg;
        exit;
    }

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
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/thumbs');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif($error == 4) {
                $path = Request::getPost('oldthumbs');
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }
}
