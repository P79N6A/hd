<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class RegionData extends Model {

    public function getSource() {
        return 'region_data';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'data_id', 'country_id', 'province_id', 'city_id', 'county_id', 'town_id', 'village_id', 'description',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['data_id', 'country_id', 'province_id', 'city_id', 'county_id', 'town_id', 'village_id', 'description',],
            MetaData::MODELS_NOT_NULL => ['id', 'data_id', 'country_id', 'province_id', 'city_id', 'county_id', 'town_id', 'village_id', 'description',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'country_id' => Column::TYPE_INTEGER,
                'province_id' => Column::TYPE_INTEGER,
                'city_id' => Column::TYPE_INTEGER,
                'county_id' => Column::TYPE_INTEGER,
                'town_id' => Column::TYPE_INTEGER,
                'village_id' => Column::TYPE_INTEGER,
                'description' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'data_id', 'country_id', 'province_id', 'city_id', 'county_id', 'town_id', 'village_id', 'description',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'country_id' => Column::BIND_PARAM_INT,
                'province_id' => Column::BIND_PARAM_INT,
                'city_id' => Column::BIND_PARAM_INT,
                'county_id' => Column::BIND_PARAM_INT,
                'town_id' => Column::BIND_PARAM_INT,
                'village_id' => Column::BIND_PARAM_INT,
                'description' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'province_id' => '0',
                'city_id' => '0',
                'county_id' => '0',
                'village_id' => '0',
                'description' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
	public function updateRegionData($data,$data_id)
	{
		$region_data_model = self::findFirst("data_id = {$data_id}");
		if($region_data_model){
			return $region_data_model->modifyRegionData($data);
		}else{
			return $this->createRegionData($data,$data_id);
		}

	}



    public function createRegionData($data, $data_id) {
        $this->data_id = $data_id;
        if (array_key_exists('country_id', $data)) {
            $this->country_id = $data['country_id'] ?: 1;
        } else {
            $this->country_id = '0';
        }

        if (array_key_exists('province_id', $data)) {
            $this->province_id = $data['province_id'] ?: 0;
        } else {
            $this->province_id = '0';
        }

        if (array_key_exists('city_id', $data)) {
            $this->city_id = $data['city_id'] ?: 0;
        } else {
            $this->city_id = '0';
        }

        if (array_key_exists('county_id', $data)) {
            $this->county_id = $data['county_id'] ?: '0';
        } else {
            $this->county_id = '0';
        }

        if (array_key_exists('town_id', $data)) {
            $this->town_id = $data['town_id'] ?: 0;
        } else {
            $this->town_id = '0';
        }

        if (array_key_exists('village_id', $data)) {
            $this->village_id = $data['village_id'] ?: 0;
        } else {
            $this->village_id = '0';
        }

        if (array_key_exists('description', $data)) {
            $this->description = $data['description'] ?: '';
        } else {
            $this->description = '';
        }
        return ($this->save()) ? true : false;
    }






    public function modifyRegionData($data) {
		$this->country_id = isset($data['country_id'])?intval($data['country_id']):1;
		$this->province_id = isset($data['province_id'])?intval($data['province_id']):0;
		$this->city_id =isset($data['city_id'])?intval($data['city_id']):0;
		$this->county_id =isset($data['county_id'])?intval($data['county_id']):0;
		$this->town_id =isset($data['town_id'])?intval($data['town_id']):0;
		$this->village_id =isset($data['village_id'])?intval($data['village_id']):0;
		$this->description =isset($data['description'])?strval($data['description']):"";
        return ($this->update()) ? true : false;
    }

	private function inintData($data){
		$region_data['country_id'] = isset($data['country_id'])?intval($data['country_id']):0;
		$region_data['province_id'] = isset($data['province_id'])?intval($data['province_id']):0;
		$region_data['city_id'] =isset($data['city_id'])?intval($data['city_id']):0;
		$region_data['county_id'] =isset($data['county_id'])?intval($data['county_id']):1;
		$region_data['town_id'] =isset($data['town_id'])?intval($data['town_id']):0;
		$region_data['village_id'] =isset($data['village_id'])?intval($data['village_id']):0;
		$region_data['description'] =isset($data['description'])?strval($data['description']):"";
		return $region_data;
	}

    public static function findRegionData($data_id) {
        return self::query()->andwhere('data_id=' . $data_id)->execute()->toarray();
    }

    public static function deleteRegionData($id) {
        return self::query()->andwhere('id=' . $id)->first()->delete();
    }

    public static function delRegionByDataId($data_id) {
    	$datas = self::findRegionData($data_id);
    	if(count($datas) > 0) {
    		foreach ($datas as $k => $v) {
    			self::deleteRegionData($v['id']);
    		}
    	}
    }
    
    private function checkRegionByType($data, $findData) {
    	$keys = array_keys($data);
    	$temp = false;
    	foreach ($keys as $key) {
    		if($key != 'description') {
	    		if( $data[$key] == $findData[$key]) {
	    			$temp = true;
		    	}else {
	    			return false;
		    	}
    		}
    	}
    	return $temp;
    }
    
    public function checkRegion($data_id, $data) {
    	$findData = self::findRegionData($data_id);
    	if(count($findData) > 0) {
	    	foreach ($findData as $kFind => $vFind) {
	    		foreach ($data as $k => $v) {
	    			$bTemp = $this->checkRegionByType($v,$vFind);
	    			if($bTemp) {
	    				self::deleteRegionData($vFind['id']);
	    			}else {
	    				continue;
	    			}
	    		}
	    	}
    	}
    }
    
    /**
     * 保存地区，可多个地区，第一个不带后缀的单独保存，其他的循环获取后保存
     * @param $data
     * @param $data_id
     * @return bool
     */
    public function saveRegion($data,$data_id) {
    	$arr = array();
    	$index=0;
    	$temp = false;
    	foreach($data as $k => $v){
    		if(preg_match('/^(country_id)+/',$k)) {
    			$i=substr($k,-1);
    			$arr[$index]=array(
    					'country_id' => isset($data['country_id'.$i])?$data['country_id'.$i]:0,
    					'province_id' => isset($data['province_id'.$i])?$data['province_id'.$i]:0,
    					'city_id' => isset($data['city_id'.$i])?$data['city_id'.$i]:0,
    					'county_id' => isset($data['county_id'.$i])?$data['county_id'.$i]:0,
    					'town_id' => isset($data['town_id'.$i])?$data['town_id'.$i]:0,
    					'village_id' => isset($data['village_id'.$i])?$data['village_id'.$i]:0,
    					'description' => isset($data['description'.$i])?$data['description'.$i]:0
    			);
    			if($arr[$index]['province_id'] > 0) {
    				$temp = true;
    			}
    			if($arr[$index]['country_id']==0) {
    				unset($arr[$index]);
    				$index--;
    			}
    			$index++;
    		}
    	}
    	if($temp) {
    		$this->checkRegion($data_id, $arr);
    		//self::delRegionByDataId($data_id);
		    foreach($arr as $k => $v){
		    	$region=new RegionData();
		    	$result = $region->createRegionData($v,$data_id);
		    	if($result == false) {
		    		return false;
				}
		    }
    	}
    	return true;
    }
    
    /**
     * 回显地区
     */
    public static function showRegion($data_id) {
    	$region_arr = RegionData::findRegionData($data_id);
    	//处理地名
    	foreach($region_arr as $k =>$v){
    		if($v['country_id']){
    			$region_arr[$k]['str']=Regions::fetchById($v['country_id'])->name;
    		}else{
    			unset($region_arr[$k]['country_id']);
    		}
    		if($v['province_id']){
    			$region_arr[$k]['str']=$region_arr[$k]['str'].'-'.Regions::fetchById($v['province_id'])->name;
    		}else{
    			unset($region_arr[$k]['province_id']);
    		}
    		if($v['city_id']){
    			$region_arr[$k]['str']=$region_arr[$k]['str'].'-'.Regions::fetchById($v['city_id'])->name;
    		}else{
    			unset($region_arr[$k]['city_id']);
    		}
    		if($v['county_id']){
    			$region_arr[$k]['str']=$region_arr[$k]['str'].'-'.Regions::fetchById($v['county_id'])->name;
    		}else{
    			unset($region_arr[$k]['county_id']);
    		}
    		if($v['town_id']){
    			$region_arr[$k]['str']=$region_arr[$k]['str'].'-'.Regions::fetchById($v['town_id'])->name;
    		}else{
    			unset($region_arr[$k]['town_id']);
    		}
    		if($v['village_id']){
    			$region_arr[$k]['str']=$region_arr[$k]['str'].'-'.Regions::fetchById($v['village_id'])->name;
    		}else{
    			unset($region_arr[$k]['village_id']);
    		}
    		if($v['description']){
    			$region_arr[$k]['str']=$region_arr[$k]['str'].'；      备注： '.$v['description'];
    		}else{
    			unset($region_arr[$k]['description']);
    		}
    	}
    	
    	return $region_arr;
    }
}