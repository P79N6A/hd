<?php
/**
 * Created by PhpStorm.
 * User: zhanghaiquan
 * Date: 2016/11/29
 * Time: 9:23
 */

class DataTemplatesController extends \BackendBaseController {

    public function indexAction(){
        $media_type = Request::get("media_type", "int");
        $condition = array('media_type' => $media_type);
        if($media_type=="") $media_type = PrivateCategory::MEDIA_TYPE_NEWS;
        $data = DataTemplates::findListByMediaType($media_type);
        $listtype = PrivateCategory::listType();
        View::setVars(compact('data', 'media_type', 'condition', 'listtype'));
    }

    public function addAction() {
        $media_type = Request::get("media_type", "int");

        $custom_params = CustomParams::findAll(-1);


        $model = new DataTemplates();


        $savesuccess = false;
        if (Request::isPost()) {
            $data = Request::getPost();
            $param_not_empty = (isset($data['params'])&&count($data['params']))?true:false;

            $validator = DataTemplates::makeValidator($data);
            $exist_data_template = DataTemplates::checkRepeated(0, $data['name']);
            if ($param_not_empty&&!$validator->fails()&&!$exist_data_template) {
                $data['status'] = 0;
                $model = new DataTemplates();
                $messages = array();
                if (!$model->create($data)) {
                    foreach ($model->getMessages() as $m) {
                        array_push($messages, $m->getMessage());
                    }
                }
                else {
                    $messages[] = "创建成功";
                    DataTemplateParams::resetParams($model->id, $data['params']);
                }

            }
            else if(!$param_not_empty) {
                $messages[] = "请至少选择一个扩展参数";
            }
            else {
                $messages[] = "模板名称重复";
            }
            View::setVars(compact('messages'));
            View::setMainView('layouts/add');
            View::setVars(compact('savesuccess'));
        }
        else {
            View::setMainView('layouts/add');
            View::setVars(compact('channel_id', 'father_id', 'media_type'));
        }
        $data_template_params = array();
        View::setVars(compact('model', 'custom_params', 'data_template_params'));
    }

    /**
     * 编辑栏目
     */
    public function editAction($id = 0) {
        if(empty($id))
            $id = Request::getQuery("id", "int");
        if(!$id) {
            redirect(Url::get("data_templates/add"));
        }
        $messages = [];
        $custom_params = CustomParams::findAll(-1);
        $model = DataTemplates::getOne($id);
        if (Request::isPost()) {
            $data = Request::getPost();
            $param_not_empty = (isset($data['params'])&&count($data['params']))?true:false;
            $update_data['name'] = $data['name'];

            $exist_data_template = DataTemplates::checkRepeated($id, $data['name']);

            if ($param_not_empty&&!$exist_data_template) {
                $messages = array();
                if ($model->update($update_data)) {
                    DataTemplateParams::resetParams($model->id, $data['params']);
                }
                else {
                    foreach ($model->getMessages() as $m) {
                        array_push($messages, $m->getMessage());
                    }
                }
            }
            else if(!$param_not_empty) {
                $messages[] = "请至少选择一个扩展参数";
            }
            else {
                $messages[] = "模板名称重复";
            }
        }
        $data_template_params = DataTemplateParams::getParams($model->id);

        View::setMainView('layouts/add');
        View::setVars(compact('model', 'custom_params', 'messages', 'data_template_params'));
    }

    /**
     * 删除
     */
    public function deleteAction() {
        if(empty($id))
            $id = Request::getQuery("id", "int");

        $model = DataTemplates::getOne($id);
        if($model->delete()) {
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 启用
     */
    public function lockAction() {
        if(empty($id))
            $id = Request::getQuery("id", "int");

        $model = DataTemplates::getOne($id);
        $model->status = 1;
        if($model->save()) {
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }
}