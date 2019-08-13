<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class UserFeedback extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'user_feedback';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'uid', 'channel_id', 'contact', 'feedback', 'ip', 'flag', 'addtime', 'updatetime',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id'],
            MetaData::MODELS_NON_PRIMARY_KEY => ['uid', 'channel_id', 'contact', 'feedback', 'ip', 'flag', 'addtime', 'updatetime',],
            MetaData::MODELS_NOT_NULL => ['id', 'uid', 'channel_id', 'addtime', 'updatetime',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'uid' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'contact' => Column::TYPE_VARCHAR,
                'feedback' => Column::TYPE_TEXT,
                'ip' => Column::TYPE_VARCHAR,
                'flag' => Column::TYPE_INTEGER,
                'addtime' => Column::TYPE_DATETIME,
                'updatetime' => Column::TYPE_DATETIME,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'uid', 'channel_id', 'flag',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'uid' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'contact' => Column::BIND_PARAM_STR,
                'feedback' => Column::BIND_PARAM_STR,
                'ip' => Column::BIND_PARAM_STR,
                'flag' => Column::BIND_PARAM_INT,
                'addtime' => Column::BIND_PARAM_STR,
                'updatetime' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'uid' => '0',
                'channel_id' => '0',
                'flag' => '0',
                'updatetime' => 'CURRENT_TIMESTAMP'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getAll() {
        $channel_id = Session::get("user")->channel_id;

        return self::query()
            ->andCondition('channel_id', $channel_id)
            ->orderBy('updatetime desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');

    }

    public static function search($search) {
        $keyword = $search['keyword'];
        return self::query()->andWhere("feedback like '%$keyword%' and channel_id=" . Session::get('user')->channel_id)
            ->orderBy('id desc')
            ->paginate(UserFeedback::PAGE_SIZE, 'Pagination');
    }

}