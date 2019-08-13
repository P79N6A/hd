<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Specials extends Model {
    use HasChannel;
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'specials';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'banner', 'start_time', 'end_time', 'keywords', 'comment_type', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'banner', 'start_time', 'end_time', 'keywords', 'comment_type', 'created_at', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'start_time', 'end_time', 'keywords', 'comment_type', 'created_at', 'updated_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'banner' => Column::TYPE_VARCHAR,
                'start_time' => Column::TYPE_DATETIME,
                'end_time' => Column::TYPE_DATETIME,
                'keywords' => Column::TYPE_VARCHAR,
                'comment_type' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'comment_type', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'banner' => Column::BIND_PARAM_STR,
                'start_time' => Column::BIND_PARAM_STR,
                'end_time' => Column::BIND_PARAM_STR,
                'keywords' => Column::BIND_PARAM_STR,
                'comment_type' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'start_time' => '0000-00-00 00:00:00',
                'end_time' => '0000-00-00 00:00:00',
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

    public static function makeValidator($inputs, $excluded_id = 0) {
        return Validator::make(
            $inputs,
            [
                'title' => 'required|max:20',
                'intro' => 'required|max:255',
            ],
            [
                'title.required' => '请填写标题',
                'title.max' => '标题不得多于20个字',
                'intro.required' => '请填写简介',
                'intro.max' => '简介不得多于255个字',
            ]
        );
    }

    /**
     * 所有
     *
     * @param $channel_id
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function all($channel_id) {
        return self::query()
            ->columns(['Data.title', 'Data.id AS data_id', 'Data.status', 'Data.thumb', 'Data.author_name', 'Specials.id'])
            ->leftjoin('Data', 'Data.source_id = Specials.id')
            ->andwhere("Specials.channel_id= :channel_id: and Data.type = 'special'", ['channel_id' => $channel_id])
            ->orderBy('Specials.updated_at desc, Specials.id desc')
            ->execute();
    }

    /**
     * @param $channel_id
     * @return mixed
     */
    public static function findAll($channel_id) {
        return self::query()
            ->columns(['Data.title', 'Data.id', 'Data.status', 'Data.thumb', 'Data.author_name', 'Specials.*'])
            ->leftjoin('Data', 'Data.source_id = Specials.id')
            ->andwhere("Specials.channel_id= :channel_id: and Data.type = 'special'", ['channel_id' => $channel_id])
            ->orderBy('Specials.updated_at desc, Specials.id desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function findOne($id) {
        return self::query()
            ->columns(['Data.title', 'Data.thumb', 'Data.intro', 'Specials.*'])
            ->leftjoin('Data', 'Data.source_id = Specials.id')
            ->andwhere('Specials.id=' . $id)
            ->andwhere('Specials.channel_id=' . Session::get('user')->channel_id . ' and Data.type = "special" ')
            ->first();
    }

    /*
    *专题标题列表
    */
    public static function listSpecials() {
        return self::query()
            ->columns(['Data.title', 'Specials.id'])
            ->leftjoin('Data', 'Data.source_id = Specials.id')
            ->andwhere('Specials.channel_id=' . Session::get('user')->channel_id . ' and Data.type = "special" ')
            ->orderBy('Specials.id desc')
            ->execute();
    }

    public static function queByDataID($data_id) {
        $category_data = SpecialCategoryData::query()
            ->andCondition('data_id', $data_id)
            ->execute()
            ->toArray();
        $category_ids = [];
        if (!empty($category_data)) {
            foreach ($category_data as $cd) {
                $category_ids[] = $cd['special_category_id'];
            }
        }
        $category_ids = array_unique($category_ids);
        return $category_ids;
    }

    public static function getSpecData($spec_id){
        return self::query()
            ->columns(['Data.*', 'Specials.*'])
            ->leftjoin('Data', 'Data.source_id = Specials.id')
            ->andwhere('Specials.channel_id=' . Session::get('user')->channel_id . ' and Data.type = "special" ')
            ->andWhere("Specials.id = $spec_id")
            ->first();
    }

}