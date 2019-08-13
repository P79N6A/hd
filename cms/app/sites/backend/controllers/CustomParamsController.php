<?php
/**
 *  自定义字段管理
 *  controller CustomParams
 *  @author     zhanghaiquan
 *  @created    2016-11-30
 * 
 */


class CustomParamsController extends \BackendBaseController {
    
    public function indexAction() {
        $data = CustomParams::findAll();
        View::setVars(compact('data'));
    }

    public function addAction() {
        $listfuntype = CustomParams::listFunTypes();
        $listvalidatetypes = CustomParams::listValidateTypes();
        $model = new CustomParams();
        $savesuccess = false;
        if (Request::isPost()) {
            $data = Request::getPost();
            $data['param_data'] = CustomParams::processValue($data);
            $param_datas = json_decode($data['param_data']);
            list($param_validate_type, $param_validate_msg) = CustomParams::setValidateJson($data);
            $data['param_validate'] = $param_validate_type;
            $data['param_validate_msg'] = $param_validate_msg;
            $validator = CustomParams::makeValidator($data);
            if (!$validator->fails()) {
                $messages = array();
                if (!$model->create($data)) {
                    foreach ($model->getMessages() as $m) {
                        array_push($messages, $m->getMessage());
                    }
                }
            }
            else {
                $messages = $validator->messages()->all();
            }
            View::setVars(compact('messages'));
            View::setVars(compact('savesuccess'));
        }

        $minlength = -1;
        $maxlength = -1;
        $validatetype = 'none';
        View::setMainView('layouts/add');
        View::setVars(compact('listfuntype', 'listvalidatetypes', 'model', 'validatetype', 'minlength', 'maxlength', 'param_datas'));
    }


    public function editAction() {
        if(empty($id))
            $id = Request::getQuery("id", "int");
        if(!$id) {
            redirect(Url::get("data_templates/add"));
        }
        $listfuntype = CustomParams::listFunTypes();
        $listvalidatetypes = CustomParams::listValidateTypes();
        $model = CustomParams::getOne($id);
        $param_datas = json_decode($model->param_data);
        $savesuccess = false;
        if (Request::isPost()) {
            $data = Request::getPost();
            $data['param_data'] = CustomParams::processValue($data);
            $param_datas = json_decode($data['param_data']);
            list($param_validate_type, $param_validate_msg) = CustomParams::setValidateJson($data);
            $data['param_validate'] = $param_validate_type;
            $data['param_validate_msg'] = $param_validate_msg;
            $validator = CustomParams::makeValidator($data);
            if (!$validator->fails()) {
                $messages = array();
                $key = "custom_param::param_id:".$model->id;
                RedisIO::delete($key);
                if (!$model->update($data)) {
                    foreach ($model->getMessages() as $m) {
                        array_push($messages, $m->getMessage());
                    }
                }
            } else {
                $messages = $validator->messages()->all();
            }
            View::setVars(compact('messages'));
            View::setVars(compact('savesuccess'));
        }
        $paramvalidate = json_decode($model->param_validate);
        $validatetype = "";
        $minlength = -1;
        $maxlength = -1;

        foreach($paramvalidate as $key=>$value) {
            switch($key) {
                case "iscard":
                case "isemail":
                case "number":
                    $validatetype = $key;
                    break;
                case "minlength": $minlength = $value;break;
                case "maxlength": $maxlength = $value;break;
            }
        }


        $valid = true;
        switch($model->param_fun_type) {
            case "select":
            case "radio":
                $valid = false;
                break;
        }
        View::setMainView('layouts/add');
        View::setVars(compact('listfuntype', 'listvalidatetypes', 'model', 'param_datas', 'valid', 'validatetype', 'minlength', 'maxlength'));
    }
}