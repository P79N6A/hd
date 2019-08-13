<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Site extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'site';
    }

    /**
     * @param string $appid
     * @return mixed
     */
    public static function getByAppId($appid){
        $key = D::memKey('SiteInfo',['app_id'=>$appid]);
        $data = MemcacheIO::get($key);
        if(!$data || !open_cache()){
            $result = self::query()->andCondition('app_id', $appid)->andCondition('status', 1)->first();
            if($result){
                $data = $result->toArray();
                MemcacheIO::set($key,$data, 86400*30);
            }
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'name', 'channel_id', 'app_id', 'app_secret', 'logo', 'domain', 'stations', 'status',
            ],

            MetaData::MODELS_PRIMARY_KEY => ['id'],
            MetaData::MODELS_NON_PRIMARY_KEY => ['id', 'name', 'channel_id', 'app_id', 'app_secret', 'logo', 'domain', 'stations', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'name', 'channel_id', 'app_id', 'app_secret', 'logo', 'domain', 'stations', 'status',],

            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['name', 'channel_id', 'app_id', 'app_secret', 'logo', 'domain', 'stations', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'name', 'channel_id', 'app_id', 'app_secret', 'logo', 'domain', 'stations', 'status',],

            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'channel_id' => Column::TYPE_INTEGER,
                'app_id' => Column::TYPE_VARCHAR,
                'app_secret' => Column::TYPE_VARCHAR,
                'logo' => Column::TYPE_VARCHAR,
                'domain' => Column::TYPE_VARCHAR,
                'stations' => Column::TYPE_TEXT,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id',  'channel_id', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'channel_id' => Column::BIND_PARAM_INT,
                'app_id' => Column::BIND_PARAM_STR,
                'app_secret' => Column::BIND_PARAM_STR,
                'logo' => Column::BIND_PARAM_STR,
                'domain' => Column::BIND_PARAM_STR,
                'stations' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'status' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    public static function findAll($channel_id){
        return Site::query()->where("channel_id = '{$channel_id}' and status =1")->paginate(Site::PAGE_SIZE,'Pagination');
    }

    public static function makeValidators($inputs) {
        return Validator::make(
            $inputs, [
            'name' => 'required|min:2|max:50',
            'logo' => 'required',
            'domain' => 'required|max:30',
            'stations' => 'required',
            'status' => 'required',
        ], [
            'name.required' => '请填写频道名',
            'name.min' => '站点名不得小于 2 个字符',
            'name.max' => '站点名不得多于 50 个字符',
            'logo.required'=>'请填写Logo',
            'domain.required'=>'请填写域名',
            'domain.max'=>'域名长度过长',
            'status.required'=>'请填写status',
        ]);
    }

    public static function findOne($site_id,$channel_id){
        $result = self::query()->where("id = '{$site_id}' and channel_id ='{$channel_id}' ")->execute()->toarray();
        return $result;
    }

    public static function getOne($site_id, $channel_id) {
        $result = self::query()->where("id = '{$site_id}' and channel_id ='{$channel_id}' ")->first();
        return $result;
    }

    public static function findOneObject($site_id){
        $result = self::query()->where("id = '{$site_id}'")->first();
        return $result;
    }


    public static function findDataByChannel($channel_id) {
        $data = self::query()
            ->where("channel_id = $channel_id")
            ->andWhere("status <> 3")
            ->paginate(Site::PAGE_SIZE, 'Pagination');
        return $data;
    }

    public static function findSiteByChannel($channel_id) {
        $data = self::query()
            ->andCondition('channel_id' , $channel_id)
            ->execute();
        return $data;
    }

    public function updateData($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    /**
     * 修改频道状态
     * @param $id
     * @param $status
     */
    public function changeStatus($site_id, $status) {
        $res = false;
        $data = self::query()
            ->andCondition("id", $site_id)
            ->andWhere("status <> 3")
            ->first();
        if(isset($data) && !empty($data)) {
            $data->status = $status;
            $res = $data->update();
            if($res) {
                $key = D::memKey('SiteInfo',['app_id'=>$data->app_id]);
                MemcacheIO::set($key, false, 86400*30);
            }
        }
        return $res;
    }

}