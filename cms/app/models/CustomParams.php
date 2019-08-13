<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CustomParams extends Model {


    public static $funtypes = array(
        'text'=>"文本框",
        'select'=>"下拉菜单",
        'radio'=>"单选",
        'checkbox'=>"复选",
        'object_select'=>"级联菜单",
        'file'=>"文件上传",
        'object_richtext'=>"富文本框"
    );


    public static $validatetypes = array(
        'iscard'=>"身份证",
        'isemail'=>"邮箱地址",
        'number'=>"数字",
        'ismobile'=>"手机号码",
        'issecurity'=>"密码",
        'isvideourl'=>"网址"
    );


    public static function listFunTypes() {
        return self::$funtypes;
    }

    public static function listValidateTypes() {
        return self::$validatetypes;
    }

    public function getSource() {
        return 'custom_params';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'param_label', 'param_name', 'param_fun_type', 'param_data', 'param_default', 'param_validate', 'param_validate_msg', 'param_remark',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['param_label', 'param_name', 'param_fun_type', 'param_data', 'param_default', 'param_validate', 'param_validate_msg', 'param_remark',],
            MetaData::MODELS_NOT_NULL => ['id', 'param_label', 'param_name', 'param_fun_type', 'param_data', 'param_default', 'param_validate', 'param_validate_msg', 'param_remark',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'param_label' => Column::TYPE_VARCHAR,
                'param_name' => Column::TYPE_VARCHAR,
                'param_fun_type' => Column::TYPE_VARCHAR,
                'param_data' => Column::TYPE_TEXT,
                'param_default' => Column::TYPE_TEXT,
                'param_validate' => Column::TYPE_VARCHAR,
                'param_validate_msg' => Column::TYPE_VARCHAR,
                'param_remark' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'param_label' => Column::BIND_PARAM_STR,
                'param_name' => Column::BIND_PARAM_STR,
                'param_fun_type' => Column::BIND_PARAM_STR,
                'param_data' => Column::BIND_PARAM_STR,
                'param_default' => Column::BIND_PARAM_STR,
                'param_validate' => Column::BIND_PARAM_STR,
                'param_validate_msg' => Column::BIND_PARAM_STR,
                'param_remark' => Column::BIND_PARAM_STR,
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

    public static function makeValidator($inputs) {
        return Validator::make($inputs, [
            'param_label' => 'required|min:2|max:50',
        ], [
            'param_label.required' => '请填写字段名称',
            'param_label.min' => '名称不得小于 2 个字符',
            'param_label.max' => '名称不得大于 50 个字符',
        ]);
    }


    public static function getParams(array $param_ids=array()) {
        if(count($param_ids)) {
            return self::query()->andWhere('id IN (' . implode(',', $param_ids) . ')')->execute()->toArray();
        }
        else {
            return self::query()->execute()->toArray();
        }
    }

    public static function findAll($page_size = 50) {
        if($page_size==-1) {
            return self::query()->execute()->toArray();
        }
        else {
            return self::query()->paginate($page_size, 'Pagination');
        }
    }

    public static function getOne($param_id) {
        return self::query()
            ->andCondition('id', $param_id)
            ->first();
    }

    public static function getParamName($param_id) {
        $key = "custom_param::param_id:".$param_id;
        $param_name = RedisIO::get($key);
        if(!$param_name) {
            $custom_param = self::getOne($param_id);
            $param_name = $custom_param->param_name;
            RedisIO::set($key, $param_name);
        }
        return $param_name;

    }

    public static function processValue(&$data) {
        $value = '';
        $values = [];
        $size = count($data['param_data_value']);
        for ($i = 0; $i < $size; $i++) {
            $val = $data['param_data_value'][$i];
            if ("" !== $val) {
                $key = $data['param_data_setting'][$i];
                if ("" !== $key) {
                    $values[$key] = $val;
                } else {
                    $values[] = $val;
                }
            }
        }
        if (!empty($values)) {
            $value = json_encode($values);
        }
        return $value;
    }

    public static function setValidateJson($data) {
        $param_label = $data['param_label'];
        $param_fun_type = $data['param_fun_type'];
        $validatetype = $data['param_validate'];
        $minlength = $data['minlength'];
        $maxlength = $data['maxlength'];
        $valid = true;
        switch($param_fun_type) {
            case "select":
            case "radio":
                $valid = false;
                break;
        }
        if($valid&&$maxlength>=$minlength) {
            $param_validate_type = '{"required": true';
            if($validatetype !=""&& $validatetype !="none") {
                $param_validate_type .= ',"'.$validatetype.'": true';
            }
            if($minlength !=-1 ) {
                $param_validate_type .= ',"minlength": '.$minlength;
            }
            if($maxlength !=-1 ) {
                $param_validate_type .= ',"maxlength": '.$maxlength;
            }
            $param_validate_type .= '}';

            /*报错内容*/
            $param_validate_msg = '{"required": "请输入'.$param_label.'"';

            if($minlength !=-1 ) {
                $param_validate_msg .= ',"minlength": "太短了"';
            }
            if($maxlength !=-1 ) {
                $param_validate_msg .= ',"maxlength":  "太短了"';
            }

            $param_validate_msg .= '}';

            return [$param_validate_type, $param_validate_msg];
        }

    }

}