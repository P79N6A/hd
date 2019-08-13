<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Vote extends Model {
    const STATUS_START = 1;
    const STATUS_END = 2;
    const VERIFY_ON = 1;
    const VERIFY_OFF = 2;//不使用
    const VERIFY_ENGLISH = 3;//使用英文的
    const VERIFY_CHINESE = 4;//使用中文的

    public function getSource() {
        return 'vote';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'option_id', 'option_min', 'option_max', 'type', 'times', 'rate', 'start_time', 'end_time', 'status', 'captcha_verify',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['option_id', 'option_min', 'option_max', 'type', 'times', 'rate', 'start_time', 'end_time', 'status', 'captcha_verify',],
            MetaData::MODELS_NOT_NULL => ['id', 'option_min', 'option_max', 'type', 'times', 'start_time', 'end_time', 'status', 'captcha_verify',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'option_id' => Column::TYPE_TEXT,
                'option_min' => Column::TYPE_INTEGER,
                'option_max' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_VARCHAR,
                'times' => Column::TYPE_INTEGER,
                'rate' => Column::TYPE_INTEGER,
                'start_time' => Column::TYPE_INTEGER,
                'end_time' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'captcha_verify' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'option_min', 'option_max', 'times', 'rate', 'start_time', 'end_time', 'status', 'captcha_verify',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'option_id' => Column::BIND_PARAM_STR,
                'option_min' => Column::BIND_PARAM_INT,
                'option_max' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_STR,
                'times' => Column::BIND_PARAM_INT,
                'rate' => Column::BIND_PARAM_INT,
                'start_time' => Column::BIND_PARAM_INT,
                'end_time' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'captcha_verify' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'option_min' => '1',
                'option_max' => '1',
                'type' => 'ip',
                'times' => '1',
                'captcha_verify' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public function createVote($data){
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    public static function findVoteById($id){
        return self::query()->andCondition('id',$id)->first();
    }

    public static function changeOptionId($id , $option_str){
        $vote = self::query()->andCondition('id',$id)->first();
        $vote->option_id = $option_str;
        return $vote->update();
    }

    public static function makeValidators(array $inputs, $excluded_id = 0) {
        return Validator::make($inputs, [
            'title' => 'required|min:2|max:50',
            'toasts' => 'required',//选择个数限制
            'votenum' => 'required',//次数限制
            'votetype' => 'required',//访问限制
            'timeradios' => 'required'//时间限制
        ], [
            'title.required' => '请填写投票标题',
            'title.min' => '投票标题不得小于 2 个字符',
            'title.max' => '投票标题不得多于 50 个字符',
            'toasts.required' => '请填写选择个数限制',
            'votenum.required' => '请填写次数限制',
            'votetype.required' => '请选择访问限制',
            'timeradios.required' => '请填写时间限制'
        ]);
    }

}