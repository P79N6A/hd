<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Domains extends Model {

    const PAGE_SIZE = 50;

    protected static $serviceTypes = [
        'frontend' => '前台模板',
        'interaction' => '前台交互',
        'api' => 'API',
    ];

    public function getSource() {
        return 'domains';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'cdn_alias', 'category_id', 'service_type', 'created_at', 'updated_at', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'cdn_alias', 'category_id', 'service_type', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'cdn_alias', 'category_id', 'service_type', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'cdn_alias' => Column::TYPE_VARCHAR,
                'category_id' => Column::TYPE_INTEGER,
                'service_type' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'category_id', 'service_type', 'created_at', 'updated_at', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'cdn_alias' => Column::BIND_PARAM_STR,
                'category_id' => Column::BIND_PARAM_INT,
                'service_type' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'name' => '',
                'cdn_alias' => '',
                'service_type' => 'frontend'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll() {
        $query = self::query()
            ->columns(array('Domains.*', 'Category.name'))
            ->leftjoin('Category', 'Domains.category_id = Category.id')
            ->andwhere('Domains.channel_id = ' . Session::get('user')->channel_id);

        return $query->orderBy('Domains.id desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }


    public static function makeValidator($inputs) {
        return Validator::make($inputs, [
            'channel_id' => "required",
            'category_id' => "required",
            'name' => "required|unique:domains",
            'cdn_alias' => "required",
        ], [
            'channel_id.required' => '频道ID必填',
            'category_id.required' => '分类ID必填',
            'name.required' => '域名必填',
            'name.unique' => '域名必须唯一',
            'cdn_alias.required' => '请填写CDN别名',
        ]);
    }

    public static function editValidator($input) {
        $validator = Validator::make(
            $input, [
            'category_id' => "required",
            'name' => "required",
        ], [
                'category_id.required' => '分类ID必填',
                'name.required' => '域名必填',
            ]
        );
        return $validator;
    }

    /**
     * @param $host
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function tplByDomainAndType($host, $service_type, $by_alias = false) {
        if ($by_alias) {
            $cond = 'cdn_alias';
        } else {
            $cond = 'name';
        }
        return self::query()
            ->andCondition($cond, $host)
            ->andCondition('service_type', $service_type)
            ->andCondition('status', 1)
            ->first();
    }

    /**
     * 审核通过
     * @param int $id
     * @return bool
     */
    public static function lock($id) {
        $data = Domains::findFirst((int)$id);
        if ($data) {
            $data->status = $data->status == 1 ? 0 : 1;
            return $data->save();
        }
        return false;
    }

    public static function findOneDomain($domain_id) {
        $result = self::query()->where("id = '{$domain_id}'")->first();
        return $result;
    }

    public static function findChannelDomains($channel_id) {
        $result = self::query()->where("channel_id = '{$channel_id}'")->andCondition('status', 1)->execute()->toArray();
        return $result;
    }

    public static function findDomainsByType($channel_id, $service_type) {
        $result = self::query()->where("channel_id = '{$channel_id}' and service_type='". $service_type."'")->andCondition('status', 1)->execute()->toArray();
        return $result;
    }

    public static function getServiceTypes() {
        return self::$serviceTypes;
    }

}