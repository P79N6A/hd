<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class VoteTheme extends Model {

    public function getSource() {
        return 'VoteTheme';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'type', 'theme_title', 'status', 'vote_star', 'vote_end', 'limit_num',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['type', 'theme_title', 'status', 'vote_star', 'vote_end', 'limit_num',],
            MetaData::MODELS_NOT_NULL => ['id', 'type', 'theme_title', 'status', 'vote_star', 'vote_end', 'limit_num',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'theme_title' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
                'vote_star' => Column::TYPE_INTEGER,
                'vote_end' => Column::TYPE_INTEGER,
                'limit_num' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'type', 'status', 'vote_star', 'vote_end', 'limit_num',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'theme_title' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'vote_star' => Column::BIND_PARAM_INT,
                'vote_end' => Column::BIND_PARAM_INT,
                'limit_num' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'limit_num' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }


    public function createVote($data) {

        $this->save($data);
        return $this->id;
    }

    public static function deleteTheme($channel_id) {
        return VoteTheme::findFirst($channel_id)->delete();
    }

    public function modifyTheme($date_theme) {

        return $this->save($date_theme);
    }

    public static function getOneTheme($theme_id) {
        $result = VoteTheme::query()->andCondition('id', $theme_id)->first();
        return $result;
    }


    public static function makeValidators(array $inputs, $excluded_id = 0) {
        return Validator::make($inputs, [
            'theme_title' => 'required|min:2|max:50',
            'vote_star' => 'required',
            'vote_end' => 'required',
            'type' => 'required',
            'option' => 'required',
            'status' => 'required'
        ], [
            'theme_title.required' => '请填写投票主题',
            'theme_title.min' => '用户名不得小于 2 个字符',
            'theme_title.max' => '用户名不得多于 50 个字符',
            'vote_star.required' => '请填写开始时间',
            'vote_end.required' => '请填写结束时间',
            'type.required' => '请选择样式',
            'option.required' => '请填写内容',
            'status.required' => '请填写状态'
        ]);
    }

    public static function makeValidators2(array $inputs, $excluded_id = 0) {
        return Validator::make($inputs, [
            'theme_title' => 'required|min:2|max:50',
            'vote_star' => 'required',
            'vote_end' => 'required',
            'type' => 'required',
            'status' => 'required'
        ], [
            'theme_title.required' => '请填写投票主题',
            'theme_title.min' => '用户名不得小于 2 个字符',
            'theme_title.max' => '用户名不得多于 50 个字符',
            'vote_star.required' => '请填写开始时间',
            'vote_end.required' => '请填写结束时间',
            'type.required' => '请选择样式',
            'status.required' => '请填写状态'
        ]);
    }

}