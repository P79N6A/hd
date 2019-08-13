<?php
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SpecComment extends Model {
    public function getSource() {
        return 'spec_comment';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'themname', 'publishway', 'anonymous', 'prevent_action', 'interval', 'create_at', 'adminid'
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['themname', 'publishway', 'anonymous', 'prevent_action', 'interval', 'create_at', 'adminid'],
            MetaData::MODELS_NOT_NULL => ['id', 'themname', 'publishway', 'anonymous', 'prevent_action', 'create_at', 'adminid'],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'themname' => Column::TYPE_VARCHAR,
                'publishway' => Column::TYPE_INTEGER,
                'anonymous' => Column::TYPE_INTEGER,
                'prevent_action' => Column::TYPE_INTEGER,
                'interval' => Column::TYPE_INTEGER,
                'create_at' => Column::TYPE_INTEGER,
                'adminid' => Column::TYPE_INTEGER
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'start_time', 'end_time', 'singup_count'
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'themname' => Column::BIND_PARAM_INT,
                'publishway' => Column::BIND_PARAM_INT,
                'anonymous' => Column::BIND_PARAM_INT,
                'prevent_action' => Column::BIND_PARAM_INT,
                'interval' => Column::BIND_PARAM_INT,
                'create_at' => Column::BIND_PARAM_INT,
                'adminid' => Column::BIND_PARAM_INT
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'publishway' => '2',
                'anonymous' => '1',
                'prevent_action' => '1',
                'interval' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * 验证字段有效性
     * @param $input
     * @return mixed
     */
    public static function makeValidator($input) {
        return Validator::make(
            $input,
            [
                'id' => 'required',
                'themname' => 'required',
                'publishway' => 'required',
                'anonymous' => 'required',
                'prevent_action' => 'required',
                'create_at' => 'required',
                'content' => 'required',
                'create_at' => 'required',
                'adminid' => 'required'
            ],
            [
                'id.required' => '评论主题必须',
                'themname.required' => '评论主题名称必须',
                'publishway.required' => '发布方式不能为空',
                'anonymous.required' => '匿名评论字段不能为空',
                'prevent_action.required' => '防刷机制不能为空',
                'create_at.required' => '创建时间不能为空',
                'adminid.required' => '管理员不能为空'
            ]
        );
    }

    public static function getAll() {
        return SpecComment::query()->paginate(100, 'Pagination');
    }

    /*
     * user:fenggu
     * date:2016-04-06
     * time:1026
     * */
    public static function getOneById($themeid) {
        if ($themeid) {

            $ret = self::findFirst(array(
                'conditions' => "id=:id:",
                'bind' => array('id' => $themeid)
            ));
            if ($ret !== false)
                return $ret->toArray();
            else
                return array();
        }
    }


}
