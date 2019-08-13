<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Referer extends Model {

    use HasChannel;

    public function getSource() {
        return 'referer';
    }

    /**
     * @param $channel_id
     * @return array
     */
    public static function apiListReferer($channel_id) {
        $data = Referer::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('status', 1)
            ->execute()
            ->toArray();
        if (!empty($data)) {
            return array_refine($data, 'id');
        }
        return [];
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'sort', 'status','url','thumb',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'sort', 'status','url','thumb',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'sort', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'url' => Column::TYPE_VARCHAR,
                'sort' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'sort', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'sort' => Column::BIND_PARAM_INT,
                'url' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'thumb' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '0',
                'name' => '',
                'sort' => '0',
                'status' => '1',
                'thumb'=>'',
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getAllByChannel($channel_id) {
        $refs = self::channelQuery($channel_id)->execute();
        $rs = [];
        foreach ($refs as $r) {
            $rs[$r->id] = $r->name;
        }
        return $rs;
    }

    public static function fetchReferByChannel($channel_id) {
        return self::channelQuery($channel_id)->execute()->toarray();
    }
    
    

    /**
     * 校验渠道下的ID是否存在
     *
     * @param $channel_id
     * @param $id
     * @return bool
     */
    public static function existsUnderChannel($channel_id, $id) {
        $r = self::getById($channel_id, $id);
        if ($r) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 通过id获取引用来源
     *
     * @param $channel_id
     * @param $id
     * @return mixed
     */
    public static function getById($channel_id, $id) {
        return Referer::channelQuery($channel_id)
            ->andCondition('id', $id)
            ->columns('id')
            ->first();
    }

    /**
     * 通过id获取引用来源
     *
     * @param $channel_id
     * @param $id
     * @return mixed
     */
    public static function tplById($channel_id, $id) {
        return Referer::channelQuery($channel_id)
            ->andCondition('id', $id)
            ->first();
    }


    public static function findAll($channel_id) {
        $result = Referer::channelQuery($channel_id)->order('id desc')->paginate(50, 'Pagination');
        return $result;
    }


    public static function makeValidator($inputs) {
        return Validator::make($inputs, [
            'name' => 'required',
            'url' => 'required'
        ], [
            'name.required' => '请填写名称',
            'name.min' => '名称不得小于 2 个字符',
            'name.max' => '名称不得大于 20 个字符',
            'url.required' => '请填写url地址'
        ]);
    }

    public static  function findOne($id){
        return Referer::query()->where("id = $id")
            ->first();
    }

    public static function findByDomain($channel_id, $domain) {
        return Referer::query()->where("channel_id = $channel_id and url = '$domain'")
            ->first();

    }

    public static function addDomian($channel_id, $domain, $name) {
        $model = new Referer();
        return $model->addSource(array('channel_id' =>$channel_id, 'name'=>$name, 'url'=>$domain));
    }

    public static function deleteSource($id) {
        return self::findFirst($id)->delete();
    }

    public function addSource($data) {
        return $this->saveGetId($data);
    }
}