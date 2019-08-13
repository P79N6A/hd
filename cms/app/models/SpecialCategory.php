<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SpecialCategory extends Model {

    use HasChannel;

    const PAGE_SIZE = 50;

    public function getSource() {
        return 'special_category';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'special_id', 'name', 'code', 'logo',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'special_id', 'name', 'code', 'logo',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'special_id', 'name', 'code',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'special_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'code' => Column::TYPE_VARCHAR,
                'logo' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'special_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'special_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'code' => Column::BIND_PARAM_STR,
                'logo' => Column::BIND_PARAM_STR,
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

    public static function makeValidator($inputs, $excluded_id = 0, $id = NULL) {
        return Validator::make(
            $inputs,
            [
                'name' => 'required|max:50|unique:special_category,name,' . $id . ',id,special_id,' . $excluded_id,
                'code' => 'required|max:50|unique:special_category,code,' . $id . ',id,special_id,' . $excluded_id,
            ],
            [
                'name.required' => '请填写栏目名称',
                'name.max' => '栏目名称不得多于50个字',
                'name.unique' => '该专题已存在此栏目',
                'code.required' => '请填写别名代码',
                'code.max' => '别名代码不得多于50个字',
                'code.unique' => '该专题已存在此别名代码',
            ]
        );
    }


    public static function findById($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        return SpecialCategory::findFirst($parameters);
    }
    /**
     * @param $channel_id
     * @return mixed
     */
    public static function findAll($channel_id) {
        return self::query()
            ->columns(['SpecialCategory.*', 'Data.title'])
            ->leftJoin('Data', 'Data.source_id = SpecialCategory.special_id')
            ->andWhere("Data.channel_id=:channel_id: and Data.type = 'special'", ['channel_id' => $channel_id])
            ->orderBy('SpecialCategory.id desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function findAllBySpecial($channel_id, $special_id) {
        return self::query()
            ->columns(['SpecialCategory.*', 'Data.title','DataStatistics.*'])
            ->leftJoin('Data', 'Data.source_id = SpecialCategory.special_id')
            ->leftJoin("DataStatistics",'DataStatistics.data_id = Data.source_id')
            ->andWhere("Data.channel_id=:channel_id: and Data.type = 'special'", ['channel_id' => $channel_id])
            ->andWhere('SpecialCategory.special_id=' . $special_id)
            ->orderBy('SpecialCategory.id desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    /**
     * 获取某专题下分类
     *
     * @param int $special_id
     * @return array
     */
    public static function listAllBySpecial($special_id) {
        return self::query()
            ->andCondition('special_id', $special_id)
            ->orderBy('id desc')
            ->execute()
            ->toArray();
    }

    /**
     * 模板系统实用
     *
     * @param $special_id
     * @return array
     */
    public static function tplBySpecial($special_id) {
        return self::listAllBySpecial($special_id);
    }

    public static function listSpName($special_id) {
        return self::query()->andCondition('special_id', $special_id)->execute();
    }

    public static function tplFindInCodes($codes, $channel_id) {
        $cats = self::channelQuery($channel_id)
            ->inWhere('code', $codes)
            ->execute()
            ->toArray();
        $rs = array_refine($cats, 'code', 'id');
        foreach ($codes as $code) {
            if (!isset($rs[$code])) {
                $rs[$code] = 0;
            }
        }
        return $rs;
    }

    public function createSpecialCategory($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    public static function deleteSpecialCategory($id) {
        return self::findFirst($id)->delete();
    }

    public static function modifySpecialCategory($id, $data) {
        return (self::findFirst($id)->assign($data)->save()) ? true : false;
    }

    public static function getTitle($spec_category_id,$channel_id){
        return self::query()->where("channel_id = '{$channel_id}' AND id='{$spec_category_id}'")->first();
    }



}