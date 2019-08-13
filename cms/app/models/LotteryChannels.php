<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class LotteryChannels extends Model {

    static $PAGE_SIZE = 50;

    public function getSource() {
        return 'lottery_channels';
    }

    public static function getLotteryChannel() {
        $key = 'lottery.channel';
        $data = MemcacheIO::get($key);
        if (!$data) {
            $data = self::find();
            if ($data) {
                $data = array_refine($data->toArray(), 'id', 'background');
                MemcacheIO::set($key, $data, 86400 * 365);
            } else {
                $data = [];
            }
        }
        return $data;
    }

    public static function refreshLotteryChannel() {
        $key = 'lottery.channel';
        MemcacheIO::delete($key);
        self::getLotteryChannel();
        return true;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'group_id', 'name', 'background', 'style', 'created_at', 'updated_at', 'type', 'sort',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'group_id', 'name', 'background', 'style', 'created_at', 'updated_at', 'type', 'sort',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'group_id', 'name', 'background', 'style', 'created_at', 'updated_at', 'type', 'sort',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'group_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'background' => Column::TYPE_VARCHAR,
                'style' => Column::TYPE_VARCHAR,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'group_id', 'created_at', 'updated_at', 'type', 'sort',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'group_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'background' => Column::BIND_PARAM_STR,
                'style' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '1',
                'sort' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll($channel_id = 0) {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query();
        $query = $query->andCondition('channel_id', $channel_id);
        return $query->orderBy('sort desc')->paginate(self::$PAGE_SIZE, 'Pagination');
    }

    public static function findAllByGroup($channel_id , $group_id ) {
        return $query = self::query()
            ->andCondition('channel_id' , $channel_id)
            ->andCondition('group_id' , $group_id)
            ->orderBy('sort desc , updated_at desc')
            ->paginate(self::$PAGE_SIZE, 'Pagination');
    }

    public static function makeValidator($input, $excluded_id = 0) {
        $validator = Validator::make(
            $input, [
            'name' => "required|unique:lottery_channels,name,{$excluded_id}",
            'type' => 'required',
        ], [
                'name.required' => '频道名必填',
                'name.unique' => '频道名已经存在',
                'type.required' => '类型必填'
            ]
        );
        return $validator;
    }

    public static function findOne($id) {
        return self::query()->andCondition('id',$id)->first();
    }

    public static function listLotteryChannel($channel_id = 0) {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query();
        $query = $query->andCondition('channel_id', $channel_id);
        $data = $query->orderBy('sort desc')->execute()->toArray();
        $return = [];
        if (!empty($data)) {
            $return = array_refine($data, 'id');
        }
        return $return;
    }

    public static function listLotteryChannelByGroup($lottery_group) {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query();
        $query = $query->andCondition('channel_id', $channel_id)->andCondition('group_id', $lottery_group);
        $data = $query->orderBy('sort desc')->execute()->toArray();
        $return = [];
        if (!empty($data)) {
            $return = array_refine($data, 'id');
        }
        return $return;
    }

}
