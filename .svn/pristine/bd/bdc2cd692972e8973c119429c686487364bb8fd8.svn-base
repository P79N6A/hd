<?php

/**
 * User: admin
 * Date: 2015/11/23
 * Time: 16:10
 */
class SettingController extends \BackendBaseController {

    public function indexAction() {
        $data = Setting::getSettings();
        View::setVars(compact('data'));
    }

    public function addAction() {
        $this->initFormView();
        $model = new Setting();
        $values = [];
        if(Request::isPost()) {
            $data = Request::getPost();
            $data['channel_id'] = Auth::user()->channel_id;
            $data['value'] = Setting::processValue($data);
            $validator = Setting::makeValidator($data);
            if(!$validator->fails()) {
                $messages = $model->createSetting($data);
            } else {
                $messages = $validator->messages()->all();
            }
            $values = json_decode($data['value']);
        }
        View::setVars(compact('messages', 'model', 'values'));
    }

    public function editAction() {
        $this->initFormView();
        $id = Request::get('id');
        /**
         * @var Setting $model
         */
        $model = Setting::findFirst($id);
        $values = json_decode($model->value);
        if(Request::isPost()) {
            $data = Request::getPost();
            $data['value'] = Setting::processValue($data);
            $validator = Setting::makeValidator($data, $id);
            if(!$validator->fails()) {
                $messages = $model->modifySetting($data);
            } else {
                $messages = $validator->messages()->all();
            }
            $values = json_decode($data['value']);
        }
        View::setVars(compact('messages', 'model', 'values'));
    }

}
