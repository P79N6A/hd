<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AppPush extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'app_push';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'data_id', 'title', 'type', 'terminal', 'cdn_type', 'push_time', 'status', 'content', 'remark', 'created_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['data_id', 'title', 'type', 'terminal', 'cdn_type', 'push_time', 'status', 'content', 'remark', 'created_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'data_id', 'title', 'type', 'push_time', 'status', 'created_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'type' => Column::TYPE_INTEGER,
                'terminal' => Column::TYPE_INTEGER,
                'cdn_type' => Column::TYPE_VARCHAR,
                'push_time' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_VARCHAR,
                'remark' => Column::TYPE_VARCHAR,
                'created_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'data_id', 'type', 'terminal', 'push_time', 'status', 'created_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_INT,
                'terminal' => Column::BIND_PARAM_INT,
                'cdn_type' => Column::BIND_PARAM_STR,
                'push_time' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'remark' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'data_id' => '0',
                'terminal' => '0',
                'push_time' => '0',
                'status' => '0',
                'created_at' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function makeValidator($input, $excluded_id = 0) {
        return Validator::make(
            $input, [
            'title' => 'required|min:2|max:99',
            'type' => 'required',
        ], [
                'title.required' => '标题必填',
                'title.min' => '标题最短2字符',
                'title.max' => '标题最长99字符',
                'type.required' => '类型必选'
            ]
        );
    }

    public static function findAll() {
        return self::query()->paginate(self::PAGE_SIZE, 'Pagination');
    }

    /**
     * 新增短信push
     */
    public static function pushMobile($mobile) {
        $model = new self;
        $data_msg = ['title' => '验证码', 'mobile' => $mobile];
        $msg = json_encode($data_msg);
        $model->save([
            'data_id' => 0,
            'title' => $mobile,
            'type' => 'msg',
            'terminal' => 'mobile',
            'push_time' => time(),
            'status' => 0,
            'content' => $msg,
            'created_at' => time()
        ]);
        return $model;
    }

    /**
     * 审核通过
     * @param int $id
     * @return bool
     */
    public static function approve($id, $status) {
        $data = AppPush::findFirst((int)$id);
        if ($data) {
            $data->status = $status;
            return $data->update();
        }
        return false;
    }

}