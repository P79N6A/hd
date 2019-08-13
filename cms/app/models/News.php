<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class News extends Model {

    use HasChannel;

    public function getSource() {
        return 'news';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'created_at', 'updated_at', 'keywords', 'comment_type', 'content', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'created_at', 'updated_at', 'keywords', 'comment_type', 'content',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'created_at', 'updated_at', 'keywords', 'comment_type', 'content', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'keywords' => Column::TYPE_VARCHAR,
                'comment_type' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_TEXT,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'created_at', 'updated_at', 'comment_type', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'keywords' => Column::BIND_PARAM_STR,
                'comment_type' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'partition_by' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'keywords' => '',
                'comment_type' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * @param $inputs
     * @param int $excluded_id
     * @return \Illuminate\Validation\Validator
     */
    public static function makeValidator($inputs, $excluded_id = 0) {
        return Validator::make(
            $inputs,
            [
                //'content' => 'required',
            ],
            [
               // 'content.required' => '请填写新闻正文内容',
            ]
        );
    }

    /**
     * 安全更新的字段
     * @return array
     */
    public static function safeUpdateFields() {
        return ['created_at', 'updated_at', 'keywords', 'content', 'source', 'no_comment','comment_type'];
    }



    private static function setRedisDataInfo($id,$data_id)
    {
        $key = "NEWS:DATAID:{$data_id}";
        $metaData = new Phalcon\Mvc\Model\MetaData\Memory();
        $attributes = $metaData->getAttributes(new self());
        $news_data = self::findFirst($id);
        $row = [];
        if($news_data)
        {
            $row = $news_data->toarray();
            foreach($attributes as $attribute)
            {
                RedisIO::hset($key,$attribute,$row[$attribute]);
                RedisIO::expire($key,parent::MAX_REDIS_TTL_DAY);
            }
        }
        return $row;
    }

    public function afterSave(){
        $this->updateRedisData();
    }

    private function updateRedisData()
    {
        if(RedisIO::hexists("NEWS:DATA:NEWSID:{$this->id}","data_id"))
        {
            $data_id = RedisIO::hget("NEWS:DATA:NEWSID:{$this->id}","data_id");
            $key = "NEWS:DATAID:{$data_id}";
            $metaData = new Phalcon\Mvc\Model\MetaData\Memory();
            $attributes = $metaData->getAttributes(new self());
            $row = $this->toarray();
            foreach($attributes as $attribute)
            {
                RedisIO::hset($key,$attribute,$row[$attribute]);
                RedisIO::expire($key,parent::MAX_REDIS_TTL_DAY);
            }
        }


    }



}