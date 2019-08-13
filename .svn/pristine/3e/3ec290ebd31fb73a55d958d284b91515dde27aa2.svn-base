<?php
/**
 *  专题栏目管理
 *  model specialcategory
 *  @created    2015-12.3
 *  
 */

class SpecialCategoryController extends \BackendBaseController {


    public function indexAction() {
        $special_id = Request::get("id", "int");
        $publish = Request::get("publish","int");
        $category_id = Request::get("category_id","int");
        if(!$special_id){
            redirect('/specials/index');
        }
        $data = SpecialCategory::findAllBySpecial(Session::get('user')->channel_id,$special_id);
        $special=Specials::findOne($special_id);
        $name=$special->title;
        View::setVars(compact('data','name','special_id','publish','category_id'));
    }
    
    /**
     * 添加专题分类
     */
    public function addAction() {
        $special_id = Request::get("id", "int");
        $special = Specials::getSpecData($special_id);
        $model = new SpecialCategory();
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            if(!isset($data['special_id'])||empty($data['special_id'])){
                $messages[] = Lang::_('nospecial');
            }else {
                $validator = SpecialCategory::makeValidator($data,$data['special_id']);
                if (!$validator->fails()) {
                    $logo = $this->validateAndUpload($messages);
                    $data['logo'] = isset($logo) ? $logo : '';
                    $data['channel_id'] = Session::get('user')->channel_id;
                    if ($model->save($data)) {
                        $messages[] = Lang::_('success');
                    }else {
                        $messages[] = Lang::_('failed');
                    }
                } else {
                    $messages = $validator->messages()->all();
                }
            }
        }

        //清除栏目lastmodified缓存
        F::_clearCache("media/latestInIds:" . $model->special_id ,$model->channel_id);

        $listspecials = Specials::listSpecials();

        View::setMainView('layouts/add');
        View::setVars(compact('messages','model','listspecials','special_id','special'));
    }
    
    /**
     * 编辑专题分类
     */
    public function editAction() {
        $id = Request::get("id", "int");
        if(!$id) {
            redirect(Url::get("special_category/add"));
        }
        $messages = [];
        $model = SpecialCategory::findFirst($id);
        $special_id = $model->special_id;
        $special = Specials::getSpecData($model->special_id);
        if (Request::isPost()) {
            $data = Request::getPost();
            if(!isset($data['special_id'])||empty($data['special_id'])){
                $messages[] = Lang::_('nospecial');
            }else {
                $validator = SpecialCategory::makeValidator($data, $data['special_id'], $id);
                if (!$validator->fails()) {
                    $logo = $this->validateAndUpload($messages);
                    $data['logo'] = isset($logo) ? $logo : $data['logo'];
                    if ($model->assign($data)->update()) {
                        $messages[] = Lang::_('success');
                    }else {
                        $messages[] = Lang::_('failed');
                    }
                } else {
                    $messages = $validator->messages()->all();
                }
            }
        }

        //清除栏目lastmodified缓存
        F::_clearCache("media/latestInIds:" . $model->special_id ,$model->channel_id);

        $listspecials = Specials::listSpecials();
        View::setMainView('layouts/add');
        View::setVars(compact('messages','model','listspecials','special_id','special'));
    }

    /**
     * 删除专题栏目
     */
    public function deleteAction() {
        $id = Request::get('id');
        $data = SpecialCategory::findFirst($id);
        if (!empty($data)) {
            $data->delete();
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }        
        exit;
    }

    /**
     * 专题分类和编辑页面在一起
     * 目前不使用
     */
    public function editCategoryAction() {
        $id = Request::get('id');//专题ID
        $messages = [];
        if(Request::isPost()){
            $input = Request::getPost();
            $validator = SpecialCategory::makeValidator($input,$id,$input['specialcategoryid']);
            if($validator->passes()){
                $logo = $this->validateAndUpload($messages);
                $input['logo'] = isset($logo) ? $logo : $input['logo'];
                $input['special_id'] = $id;
                $input['channel_id'] = Session::get('user')->channel_id;
                if($input['specialcategoryid']) {
                    $return=SpecialCategory::modifySpecialCategory($input['specialcategoryid'],$input);
                }else {
                    $sc = new SpecialCategory();
                    $return = $sc->createSpecialCategory($input);
                }
                if($return){
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }else {
                $messages = $validator->messages()->all();
            }
        }
        $data=SpecialCategory::listAllBySpecial($id);
        if(!empty($data)){
            for($i=0;$i<count($data);$i++) {
                unset($data[$i]['channel_id']);
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('data','messages'));
    }

    public function deleteCategoryAction() {
        $id = Request::get('id');
        $return=SpecialCategory::deleteSpecialCategory($id);
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
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
