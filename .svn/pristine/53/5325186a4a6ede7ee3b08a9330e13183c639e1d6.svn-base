<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Vitae extends Model {

    public function getSource() {
        return 'vitae';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'admin_id', 'experience', 'skill', 'contacts', 'email', 'recruit_time',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['admin_id', 'experience', 'skill', 'contacts', 'email', 'recruit_time',],
            MetaData::MODELS_NOT_NULL => ['id', 'admin_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'admin_id' => Column::TYPE_INTEGER,
                'experience' => Column::TYPE_TEXT,
                'skill' => Column::TYPE_TEXT,
                'contacts' => Column::TYPE_VARCHAR,
                'email' => Column::TYPE_VARCHAR,
                'recruit_time' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'admin_id', 'recruit_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'admin_id' => Column::BIND_PARAM_INT,
                'experience' => Column::BIND_PARAM_STR,
                'skill' => Column::BIND_PARAM_STR,
                'contacts' => Column::BIND_PARAM_STR,
                'email' => Column::BIND_PARAM_STR,
                'recruit_time' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [

            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getOneByAdmin($admin_id) {
        $parameters = array();
        $parameters['conditions'] = "admin_id=" . $admin_id;
        $vitae = Vitae::findFirst($parameters);
        if ($vitae == null) {
            Vitae::createVitae($admin_id);
            $vitae = Vitae::findFirst($parameters);
        }
        return $vitae;
    }

    public static function getOneById($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        return Vitae::findFirst($parameters);
    }

    public static function createVitae($admin_id) {
        $vitae = new Vitae();
        $vitae->admin_id = $admin_id;
        return ($vitae->save()) ? true : false;
    }

    public function modifyVitae($data) {
        if ($data['contacts'] === '') $data['contacts'] = null;
        if ($data['email'] === '') $data['email'] = null;
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    //检验表单信息
    public static function checkForm($inputs) {
        $validator = Validator::make(
            $inputs, [
            'id' => 'required',
            'admin_id' => 'required',
            'contacts' => 'size:11',
            'email' => 'email',
        ], [
                'id.required' => '简历ID不存在',
                'admin_id.required' => '用户ID不存在',
                'email' => '邮箱格式不正确',
                'contacts.size' => '备用手机格式不正确',

            ]
        );
        return $validator;
    }
}