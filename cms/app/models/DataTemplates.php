<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class DataTemplates extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'data_templates';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'name', 'media_type', 'status'
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['name', 'media_type','status'],
            MetaData::MODELS_NOT_NULL => ['id', 'name', 'media_type','status'],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'media_type' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'media_type', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'media_type' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'media_type' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }


    public static function makeValidator($inputs) {
        return Validator::make($inputs, [
            'name' => 'required|min:2|max:50',
        ], [
            'name.required' => '请填写扩展字段模板名称',
            'name.min' => '名称不得小于 2 个字符',
            'name.max' => '名称不得大于 50 个字符',
        ]);
    }



    public static function findListByMediaType($media_type) {
        $query = self::query()->andwhere('media_type = ' . $media_type);
        return $query
            ->paginate(DataTemplates::PAGE_SIZE, 'Pagination');
    }

    public static function checkRepeated($template_id, $name) {
        if($template_id) {
            $data_template = self::query()->where("id <> {$template_id} and name = '{$name}' ")->first();
        }
        else {
            $data_template = self::query()->where("name = '{$name}' ")->first();
        }
        return ($data_template)?true:false;
    }


    public static function getOne($template_id) {
        return self::query()
            ->andCondition('id', $template_id)
            ->first();
    }


    public static function findAllByMediaType($media_type) {
        return self::query()->andwhere('media_type = ' . $media_type)->execute()->toArray();
    }



}