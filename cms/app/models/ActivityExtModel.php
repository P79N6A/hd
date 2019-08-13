<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class ActivityExtModel extends Model {

    const PAGE_SIZE = 50;

    public function getSource() {
        return 'activity_ext_model';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'activity_id', 'field_name', 'field_text', 'field_type', 'filed_width', 'field_def', 'field_isshowback', 'field_required', 'terminal', 'sort',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'activity_id', 'field_name', 'field_text', 'field_type', 'filed_width', 'field_def', 'field_isshowback', 'field_required', 'terminal', 'sort',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'activity_id', 'sort',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'activity_id' => Column::TYPE_INTEGER,
                'field_name' => Column::TYPE_VARCHAR,
                'field_text' => Column::TYPE_VARCHAR,
                'field_type' => Column::TYPE_INTEGER,
                'filed_width' => Column::TYPE_INTEGER,
                'field_def' => Column::TYPE_VARCHAR,
                'field_isshowback' => Column::TYPE_INTEGER,
                'field_required' => Column::TYPE_INTEGER,
                'terminal' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'activity_id', 'field_type', 'filed_width', 'field_isshowback', 'field_required', 'terminal', 'sort',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'activity_id' => Column::BIND_PARAM_INT,
                'field_name' => Column::BIND_PARAM_STR,
                'field_text' => Column::BIND_PARAM_STR,
                'field_type' => Column::BIND_PARAM_INT,
                'filed_width' => Column::BIND_PARAM_INT,
                'field_def' => Column::BIND_PARAM_STR,
                'field_isshowback' => Column::BIND_PARAM_INT,
                'field_required' => Column::BIND_PARAM_INT,
                'terminal' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'field_isshowback' => '1',
                'field_required' => '1',
                'terminal' => 'android,ios,wap,web',
                'sort' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }


    /*
     * user:fenggu
     * date:2016-4-11
     * time:16:05
     * desc:获取报名表中的配置信息
     * */
    public static function getRecByActIdAndChannelId($actid, $channelid) {
        return self::find(
            array('conditions' => 'activity_id=:activity_id: AND channel_id = :channel_id:',
                'bind' => array('activity_id' => $actid, 'channel_id' => $channelid)
            ))->toArray();
    }

    /*
     * user:fenggu
     * date:2016-4-11
     * time:16:05
     * desc:获取报名表扩展字段名称队列
     * */
    public static function getExtFiledsNameListByActIDAndChannelId($channelid, $actid) {
        $ret = self::find(
            array('conditions' => 'activity_id=:activity_id: AND channel_id = :channel_id:',
                'bind' => array('activity_id' => $actid, 'channel_id' => $channelid),
                'columns' => array('field_name')
            ))->toArray();
        $arr = Array();
        foreach ($ret as $item) {
            $arr[] = $item['field_name'];
        }
        return $arr;
    }

    /*
     *  user:fenggu
     *  date:2016-4-11
     *  time:20:35
     *  desc:获取频道活动报名表字段的默认值
     * */
    public static function getExtFiledsDefValue($channel_id, $activity_id) {
        $ret = self::find(
            array('conditions' => 'activity_id=:activity_id: AND channel_id = :channel_id:',
                'bind' => array('activity_id' => $activity_id, 'channel_id' => $channel_id),
                'columns' => array('field_name', 'field_def')))->toArray();
        $arrdefs = array();
        foreach ($ret as $kv) {
            $arrdefs[$kv['field_name']] = $kv['field_def'];
        }
        return $arrdefs;
    }

    public static function getExtFieldsInfo($channel_id, $activity_id) {
        return self::find(
            array('conditions' => 'activity_id=:activity_id: AND channel_id = :channel_id:',
                'bind' => array('activity_id' => $activity_id, 'channel_id' => $channel_id),
                'columns' => array('field_name', 'field_text', 'field_type', 'filed_width', 'field_def', 'field_required'))
        )->toArray();
    }

    public static function getExtVisiabledFields($channel_id, $activity_id) {
        return self::find(
            array('conditions' => 'activity_id=:activity_id: AND channel_id = :channel_id: AND field_isshowback = 1',
                'bind' => array('activity_id' => $activity_id, 'channel_id' => $channel_id),
                'columns' => array('field_name', 'field_text'))
        )->toArray();
    }

    /**
     * 根据活动id获取活动所有字段
     * @auth zhangyichi
     * @param $channel_id
     * @param $activity_id
     * @return mixed
     */
    public static function getExtModelListById($channel_id, $activity_id) {
        return self::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('activity_id', $activity_id)
            ->order('sort DESC')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    /**
     * 表单验证完整性
     * @param $inputs
     * @return mixed
     */
    public static function makeValidator($inputs) {
        return Validator::make($inputs, [
            'activity_id' => 'required',
            'field_name' => 'required|max:90',
            'field_text' => 'required|max:50',
            'filed_width' => 'required|max:11',
        ], [
            'activity_id.required' => '活动不存在',
            'field_name.required' => '请填写字段名称',
            'field_name.max' => '活动简介不得大于 90 个字符',
            'field_text.required' => '请填写字段描述',
            'field_text.max' => '活动地点不得大于 50 个字符',
            'filed_width.required' => '请填写字段长度',
            'filed_width.max' => '字段长度不得大于 11 个字符',
        ]);
    }

}