<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Videos extends Model {
	
	use HasChannel;
	
    public function getSource() {
        return 'videos';
    }

    /**
     * @param $id
     * @return array
     */
    public static function apiFindVideoById($id) {
        $video = Videos::findFirst($id);
        $videoFile = VideoFiles::apiGetFileByVideo($id);
        $data = [];
        if ($video && $videoFile) {
            return array_merge($videoFile->toArray(), $video->toArray());
        }
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'keywords', 'comment_type', 'channel_id', 'collection_id', 'supply_id', 'duration', 'created_at', 'updated_at', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['keywords', 'comment_type', 'channel_id', 'collection_id', 'supply_id', 'duration', 'created_at', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'keywords', 'comment_type', 'channel_id', 'collection_id', 'supply_id', 'duration', 'created_at', 'updated_at', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'keywords' => Column::TYPE_VARCHAR,
                'comment_type' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'collection_id' => Column::TYPE_INTEGER,
                'supply_id' => Column::TYPE_INTEGER,
                'duration' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'comment_type', 'channel_id', 'collection_id', 'supply_id', 'duration', 'created_at', 'updated_at', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'keywords' => Column::BIND_PARAM_STR,
                'comment_type' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'collection_id' => Column::BIND_PARAM_INT,
                'supply_id' => Column::BIND_PARAM_INT,
                'duration' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'keywords' => '',
                'comment_type' => '1',
                'supply_id' => '0',
                'duration' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * @param $id
     * @return array|bool
     */
    public static function getWithFiles($id) {
        $r = self::findFirst($id);
        if ($r) {
            $r = $r->toArray();
            $r['files'] = VideoFiles::query()->andCondition('video_id', $id)->execute()->toArray();
        }
        return $r;
    }

    /**
     * @param $id
     * @return array|bool
     */
    public static function getByCollection($id) {
        $rs = self::query()->andCondition('collection_id', $id)->execute()->toArray();
        foreach ($rs as $k => $r) {
            $rs[$k]['files'] = VideoFiles::query()->andCondition('video_id', $r['id'])->execute()->toArray();
        }
        return $rs;
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
    
}