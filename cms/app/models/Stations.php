<?php
/**
 *  电视广播台管理
 *  model station
 * @author     Zhangyichi
 * @created    2015-9-16
 *
 * @param id ,is_system,channel_id,code,name,type,logo,channel_name,customer_name,epg_path
 */

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Stations extends Model {

    use HasChannel;

    const PAGE_SIZE = 50;

    public function getSource() {
        return 'stations';
    }

    /**
     * 根据ID获取某个电台
     * @param $id
     * @return array|mixed
     */
    public static function apiGetStationsById($id) {
        $key = D::memKey('apiGetStationsById', ['id' => $id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $rs = self::findFirst($id);
            if ($rs) {
                $data = $rs->toArray();
                MemcacheIO::set($key, $data, 1800);
            }
        }
        return $data;
    }

    /**
     * @param $id
     * @return array|bool
     */
    public static function getWithEpgs($id) {
        $r = self::apiGetStationsById($id);
        if ($r) {
            $r['epgs'] = StationsEpg::apiGetEpgById($id);
        }
        return $r;
    }

    public static function apiGetStationsByCode($code , $channel_id) {
        $key = D::memKey('apiGetStationsByCode', ['code' => $code ,'channel_id' => $channel_id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $rs = self::query()->andCondition('code',$code)->first();
            if ($rs) {
                $data = $rs->toArray();
                MemcacheIO::set($key, $data, 1800);
            }
        }
        return $data;
    }

    /**
     * 获取某站点下stations列表
     * @param int $site_id
     * @param string $stations
     * @param int $type
     * @return array
     */
    public static function apiGetStationsByType($site_id, $stations, $type) {
        $key = D::memKey('apiGetStationsByType', ['site_id' => $site_id, 'type' => $type]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $stations = explode(",", trim($stations, ","));
            if (!empty($stations)) {
                $stations = implode(",", $stations);
                $data = self::query()
                    ->columns('id,name,logo,code,share_url')
                    ->andCondition('type', $type)
                    ->andWhere("id in ({$stations})")
                    ->orderBy("FIELD(id,$stations) ASC")
                    ->execute()
                    ->toArray();
                MemcacheIO::set($key, $data, 1800);
            }
        }
        return $data;
    }

    public static function tplStations($channel_id) {
        $key = D::memKey('tplStations', ['c' => $channel_id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->andCondition('channel_id', $channel_id)
                ->orderBy('type ASC')
                ->execute()
                ->toArray();
            MemcacheIO::set($key, $data, 1800);
        }
        return $data;
    }

    public static function tplOne($channel_id, $main, $type = 'code') {
        if (!in_array($type, ['code', 'id'])) {
            throw new \Phalcon\Mvc\Model\Exception('Invalid Epg main type ' . $type);
        }
        $key = D::memKey('tplStation', ['c' => $channel_id, 'm' => $main, 't' => $type]);
        return MemcacheIO::snippet($key, 1800, function () use ($channel_id, $main, $type) {
            $station = self::channelQuery($channel_id)
                ->andCondition($type, $main)
                ->first();
            if ($station) {
                $station = $station->toArray();
            } else {
                $station = [];
            }
            return $station;
        });

    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'is_system', 'channel_id', 'code', 'name', 'type', 'logo', 'channel_name', 'customer_name', 'epg_path', 'share_url', 'vms_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['is_system', 'channel_id', 'code', 'name', 'type', 'logo', 'channel_name', 'customer_name', 'epg_path', 'share_url', 'vms_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'is_system', 'channel_id', 'code', 'name', 'type', 'channel_name', 'customer_name',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'is_system' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'code' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'type' => Column::TYPE_INTEGER,
                'logo' => Column::TYPE_VARCHAR,
                'channel_name' => Column::TYPE_VARCHAR,
                'customer_name' => Column::TYPE_VARCHAR,
                'epg_path' => Column::TYPE_VARCHAR,
                'share_url' => Column::TYPE_VARCHAR,
                'vms_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'is_system', 'channel_id', 'code', 'type', 'vms_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'is_system' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'code' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_INT,
                'logo' => Column::BIND_PARAM_STR,
                'channel_name' => Column::BIND_PARAM_STR,
                'customer_name' => Column::BIND_PARAM_STR,
                'epg_path' => Column::BIND_PARAM_STR,
                'share_url' => Column::BIND_PARAM_STR,
                'vms_id' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getOne($station_id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $station_id;
        return Stations::findFirst($parameters);
    }

    public static function findName() {
	    
    	$data = self::query()
	    	->columns('id,name')
	    	->execute()
	    	->toArray();
  
    	return $data;
    	
    }

    public static function findOneName($id) {
    	$result = Stations::query()->where("Stations.id='{$id}'")->execute()->toarray();
    	return $result;
    }
    
    /**
     * 携带频道ID查询
     *
     * @param $id
     * @param $channel_id
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function findWithChannel($id, $channel_id) {
        return self::channelQueryAndSystem($channel_id)
            ->andCondition('id', $id)
            ->first();
    }

    //获取属性
    public function getCode() {
        return $this->code;
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getLogo() {
        return $this->logo;
    }

    public function getEpgPath() {
        if ($this->epg_path) {/*新数据都采用该字段*/
            return $this->epg_path;
        } else {
            if ('lantian' == $this->customer_name) {/*兼容过去数据*/
                return "/channels/" . $this->customer_name . "/" . $this->channel_name . "/";
            } else {
                return $this->customer_name . "/" . $this->channel_name . "/";
            }
        }
    }

    //查找操作
    //获取所有的电视台
    public static function getStations() {
        $data = Stations::query()
        	->andCondition('channel_id', Auth::user()->channel_id)
            ->execute()->toarray();
        return $data;
    }
    //查找操作
    //获取所有的电视台
    public static function getStationsByChannel($channel_id) {
        $datas = Stations::query()->where("channel_id=" . $channel_id." or channel_id= 0")->groupby('code')->orderby('code')
            ->execute()->toarray();
		
        foreach ($datas as $key => $val) {
        	$code = $val['code'];
        	if($val['channel_id'] != $channel_id) {
	        	$data = Stations::query()->where("channel_id=" . $channel_id." and code =".$code)->first();
	        	if(isset($data) && !empty($data)){
	        		$data = $data->toarray();
	        		if( $val['code'] == $data['code']){
	        			$datas[$key] = $data; 
	        		}
	        	}
        	}
        }
        return $datas;
    }


    public static function findAll($type = 0) {
        if (1 == $type || 2 == $type) {
            return Stations::query()->where("type=" . $type)->order('id desc')->paginate(50, 'Pagination');
        } else {
            return Stations::query()->order('id desc')->paginate(50, 'Pagination');
        }
    }

    public static function findAllStation($channel_id) {
        return self::channelQueryAndSystem($channel_id)
            ->order('id desc')
            ->paginate(50, 'Pagination');
    }

    public static function getStationsByCode($code){
        return self::query()
            ->andCondition('code',$code)
            ->execute();
    }

    //
    public static function getStationsByType($type) {
        $data = self::query()
            ->columns('id,name,code,logo')
            ->andCondition('type', $type)
            ->execute()
            ->toArray();
        return $data;
    }

    //指定电台ID查找
    public static function getStationsById($id) {
        return Stations::findFirst($id);
    }


    //增加操作
    //电台对象进行添加
    public function createStations($data) {
        isset($data['id']) ? $data['id'] = null : true;
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    //删除操作
    //根据电台ID删除电台
    public static function deleteStations($id) {
        return Stations::findFirst($id)->delete();
    }

    //修改操作
    //节目流对象修改信息
    public function modifyStations($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    //检验表单信息
    public static function makeValidator($inputs) {
        $validator = Validator::make(
            $inputs, [
            'is_system' => 'required|boolean',
            'channel_id' => 'required',
            'code' => 'required',
            'name' => 'required',
            'type' => 'required',
            'channel_name' => 'required',
            'customer_name' => 'required'
        ], [
                'is_system.required' => '请填写是否系统级',
                'is_system.boolean' => '是否系统级输入错误',
                'channel_id.required' => '请填写所属频道',
                'code.required' => '请填写电台编号',
                'name.required' => '请填写电台名',
                'type.required' => '请填写电台类型',
                'channel_name.required' => '请填写直播流相关字段1',
                'customer_name.required' => '请填写直播流相关字段2'
            ]
        );
        return $validator;
    }

    public static function changeValidator($inputs) {
        $validator = Validator::make(
            $inputs, [
            'code' => 'required',
            'name' => 'required',
            'type' => 'required',
            'channel_name' => 'required',
            'customer_name' => 'required'
        ], [
                'code.required' => '请填写电台编号',
                'name.required' => '请填写电台名',
                'type.required' => '请填写电台类型',
                'channel_name.required' => '请填写直播流相关字段1',
                'customer_name.required' => '请填写直播流相关字段2'
            ]
        );
        return $validator;
    }
}