<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Regions extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'regions';
    }

    public static function apiGetRegionsByFather($father_id) {
        $rs = [];
        $father_id = (int)$father_id;
        if ($father_id > 0) {
            $rs = self::query()
                ->andCondition('father_id', $father_id)
                ->execute()->toArray();
        }
        return $rs;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'father_id', 'name', 'pinyin', 'pinyin_short', 'level', 'longitude', 'latitude',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['father_id', 'name', 'pinyin', 'pinyin_short', 'level', 'longitude', 'latitude',],
            MetaData::MODELS_NOT_NULL => ['id', 'father_id', 'name', 'pinyin', 'pinyin_short', 'level', 'longitude', 'latitude',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'father_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'pinyin' => Column::TYPE_VARCHAR,
                'pinyin_short' => Column::TYPE_VARCHAR,
                'level' => Column::TYPE_INTEGER,
                'longitude' => Column::TYPE_INTEGER,
                'latitude' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'father_id', 'level', 'longitude', 'latitude',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'father_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'pinyin' => Column::BIND_PARAM_STR,
                'pinyin_short' => Column::BIND_PARAM_STR,
                'level' => Column::BIND_PARAM_INT,
                'longitude' => Column::BIND_PARAM_INT,
                'latitude' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'name' => '',
                'pinyin' => '',
                'pinyin_short' => '',
                'level' => 'city',
                'longitude' => '0.00000000',
                'latitude' => '0.00000000'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function tplFirst($id) {
        $id = (int)$id;
        $r = self::query()
            ->andCondition('id', $id)
            ->first();
        if ($r) {
            return $r->toArray();
        } else {
            return [];
        }
    }

    /**
     * 根据父类ID获取子分类 - 供模板使用
     * @param $id
     * @return array
     */
    public static function tplSub($father_id) {
        $rs = [];
        $father_id = (int)$father_id;
        if ($father_id > 0) {
            $rs = self::query()
                ->andCondition('father_id', $father_id)
                ->execute()
                ->toArray();
        }
        return $rs;
    }

    public static function getRootCategory() {
        return Regions::find(array(
            'father_id=0'
        ));
    }

    public static function getSingleOne($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        return Regions::findFirst($parameters);
    }

    private function setParent($d, $parents) {
        if ($d->father_id) {
            $parents = $this->setParent(regions::getSingleOne($d->father_id), $parents);
        }
        array_push($parents, $d);
        return $parents;
    }

    public function getParents() {
        $parents = array();
        $parents = $this->setParent($this, $parents);
        return $parents;
    }

    public static function fetchById($id) {
        return self::findFirst($id);
    }

    public static function findAll() {
        $data = self::query()
            ->paginate(self::PAGE_SIZE, 'Pagination');
        return $data;
    }

    public static function findListByFather($father_id) {
        return self::query()->andwhere('father_id = ' . $father_id)->execute()->toArray();
    }

    public function createRegions() {
        return ($this->save()) ? true : false;
    }

    public function modifyRegions() {
        return ($this->update()) ? true : false;
    }

    public static function deleteRegions($id) {
        return self::findFirst($id)->delete();
    }

    public static function findId($name) {
        $result = self::query()->where("name = '{$name}'")->execute()->toarray();
        return $result;
    }

    public static function queByDataID($data_id) {
        $region_data = RegionData::query()
            ->andCondition('data_id', $data_id)
            ->execute()
            ->toArray();
        $region_ids = [];
        if (!empty($region_data)) {
            foreach ($region_data as $rd) {
                $region_ids[] = $rd['country_id'];
                $region_ids[] = $rd['province_id'];
                $region_ids[] = $rd['city_id'];
                $region_ids[] = $rd['county_id'];
                $region_ids[] = $rd['town_id'];
                $region_ids[] = $rd['village_id'];
            }
        }
        $region_ids = array_unique($region_ids);
        return $region_ids;
    }
    
    /**
     * 查找所有省份中最小的id
     * @return 最小省份id
     */
    public static function queIdByLevel() {
    	$region_data = self::query()
    		->columns( array('Regions.id'))
    		->where("level = 'province'")
    		->orderBy('id asc')
    		->execute()
    		->toarray();

    	$region_id = $region_data[0]['id'];
    	return $region_id;
    }

    //逆向百度地图的地址到regions 的数据
    public static function reverseAddress($addressJsonStr){
        $addressJson = json_decode($addressJsonStr,true);
        if(!$addressJson){
            return "";
        }
        $wordLen = 1;
        if(strpos(php_uname('s'), "NT") > 0){
            $wordLen = 3; //windows系统
        }
        $province = mb_substr($addressJson["province"], 0, mb_strlen($addressJson["province"])-$wordLen);
        $city = mb_substr($addressJson["city"], 0, mb_strlen($addressJson["city"])-$wordLen);
        $district = $addressJson["district"];
        $key = "reverse_address:".md5($province.$city.$district);
        $addressInfo = RedisIO::get($key);
        if(!$addressInfo){
            $addressInfo = "";
            $names = [$province,$city,$district];
            foreach ($names as $name){
                $model = self::query()->where("name = '{$name}'")->first()->toArray();
                if(!$model){
                    break;
                }
                $id = $model['id'];
                if (empty($addressInfo)){
                    $addressInfo = $name.",".$id;
                }else{
                    $addressInfo .= "," . $name .",". $id;
                }

            }
            RedisIO::set($key,$addressInfo);
            return $addressInfo;
        }else{
           return $addressInfo;
        }

    }
}