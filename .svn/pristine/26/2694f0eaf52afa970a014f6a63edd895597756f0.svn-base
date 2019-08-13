<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class ActivitySignupAttachment extends Model {

    public function getSource() {
        return 'activity_signup_attachment';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'ext_id', 'signup_id', 'type', 'title', 'thumb', 'url',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['ext_id', 'signup_id', 'type', 'title', 'thumb', 'url',],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'ext_id' => Column::TYPE_INTEGER,
                'signup_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'thumb' => Column::TYPE_VARCHAR,
                'url' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'ext_id', 'signup_id', 'type',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'ext_id' => Column::BIND_PARAM_INT,
                'signup_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'thumb' => Column::BIND_PARAM_STR,
                'url' => Column::BIND_PARAM_STR,
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

    public function createActivitySignupAttachment($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    public static function getOneByExtId($ext_id) {
        return self::query()->andCondition('ext_id',$ext_id)->first();
    }

    public static function getUrlByExtId($ext_id ,$signup_id) {
        $attachment = self::getOneByExtId($ext_id);
        if($attachment) {
            $url = $attachment->url;
            $movie_url = substr($url,0,strrpos($url,'_playlist.m3u8'));
            $movie_url = 'http:/'.$movie_url;
            $attachment->signup_id = $signup_id;
            $attachment->update();
            return $movie_url;
        }else{
            return '';
        }
    }

}