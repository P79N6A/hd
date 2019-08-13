<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class TemplateFriends extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'template_friends';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'domain_id', 'template_id', 'category_id', 'region_id', 'data_id', 'url', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'domain_id', 'template_id', 'category_id', 'region_id', 'data_id', 'url', 'created_at', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'domain_id', 'template_id', 'category_id', 'region_id', 'data_id', 'url', 'created_at', 'updated_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'domain_id' => Column::TYPE_INTEGER,
                'template_id' => Column::TYPE_INTEGER,
                'category_id' => Column::TYPE_INTEGER,
                'region_id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'url' => Column::TYPE_VARCHAR,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'domain_id', 'template_id', 'category_id', 'region_id', 'data_id', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'domain_id' => Column::BIND_PARAM_INT,
                'template_id' => Column::BIND_PARAM_INT,
                'category_id' => Column::BIND_PARAM_INT,
                'region_id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'url' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
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

    public static function findAll($tpl_id) {
        return TemplateFriends::query()
            ->columns(['Category.*', 'Regions.*', 'TemplateFriends.*', 'Templates.name'])
            ->leftjoin('Category', 'Category.id = TemplateFriends.category_id')
            ->leftjoin('Regions', 'Regions.id = TemplateFriends.region_id')
            ->leftjoin('Templates', 'Templates.id = TemplateFriends.template_id')
            ->where('TemplateFriends.channel_id=' . Session::get('user')->channel_id . ' and TemplateFriends.template_id =' . $tpl_id)
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function checkUnique($domain_id, $url, $id) {
        $condition = "";
        if ($id > 0) {
            $condition = 'id != ' . $id . ' and ';
        }
        return self::query()
            ->andwhere($condition . "url = '$url' and domain_id = " . $domain_id)
            ->first();
    }

    public static function checkUniqueTopic($domain_id, $data_id) {
        return self::query()
            ->andCondition('domain_id', $domain_id)
            ->andCondition('data_id', $data_id)
            ->first();
    }
    
    public static function checkUniqueCategory($domain_id, $data_id) {
    	return self::query()
    	->andCondition('domain_id', $domain_id)
    	->andCondition('category_id', $data_id)
    	->first();
    }

    public static function tplUrlByParams($params) {

        $ids = [
            'category_id' => 0,
            'region_id' => 0,
            'data_id' => 0,
        ];
        $query = self::query();
        foreach ($ids as $key => $val) {
            if (isset($params[$key])) {
                $ids[$key] = $val = (int)$params[$key];
            }
            $query->andCondition($key, $val);
        }
        $r = $query->columns(['url'])->first();
        return [$r, $ids];

    }
    //根据dataId获取模板
    public static function getTplByDataId($data_id){
        $key = D::memKey("tplDomainId",["data_id"=>$data_id]);
        $data = MemcacheIO::get($key);
        if( !$data || !open_cache() ){
            $tpl = self::query()
                ->innerJoin("Templates","Templates.id=TemplateFriends.template_id")
                ->where("TemplateFriends.data_id = :data_id:")
                ->andWhere("Templates.type = 9")
                ->bind(["data_id"=>$data_id])
                ->first();
            if ( $tpl ){
                $data = $tpl->toArray();
                MemcacheIO::set($key,$data);
            }
        }
        return $data;
    }
}