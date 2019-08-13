<?php
/**
 *  电视节目流管理
 *  model stationsEpg
 *  @author     Zhangyichi
 *  @created    2015-9-11
 *
 *  @param id,stations_id,name,width,height,cdn,percent,kpbs,audiokpbs,drm
 */
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class StationsEpg extends Model {

    public function getSource() {
        return 'stations_epg';
    }

    /**
     * 某电台/广播流地址
     * @param int $stations_id
     * @return array|mixed
     */
    public static function apiGetEpgById($stations_id){
        $key = D::memKey('apiGetEpgById',['id'=>$stations_id]);
        $data = MemcacheIO::get($key);
        if(!$data){
            $data =  self::query()
                ->andCondition('stations_id',$stations_id)
                ->execute()
                ->toArray();
            MemcacheIO::set($key,$data,1800);
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'stations_id', 'name', 'width', 'height', 'cdn', 'percent', 'kpbs', 'audiokpbs', 'drm', 'sort',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['stations_id', 'name', 'width', 'height', 'cdn', 'percent', 'kpbs', 'audiokpbs', 'drm', 'sort',],
            MetaData::MODELS_NOT_NULL => ['id', 'stations_id', 'name', 'width', 'height', 'cdn', 'percent', 'kpbs', 'audiokpbs', 'drm', 'sort',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'stations_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'width' => Column::TYPE_INTEGER,
                'height' => Column::TYPE_INTEGER,
                'cdn' => Column::TYPE_TEXT,
                'percent' => Column::TYPE_TEXT,
                'kpbs' => Column::TYPE_INTEGER,
                'audiokpbs' => Column::TYPE_INTEGER,
                'drm' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'stations_id', 'width', 'height', 'kpbs', 'audiokpbs', 'drm', 'sort',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'stations_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'width' => Column::BIND_PARAM_INT,
                'height' => Column::BIND_PARAM_INT,
                'cdn' => Column::BIND_PARAM_STR,
                'percent' => Column::BIND_PARAM_STR,
                'kpbs' => Column::BIND_PARAM_INT,
                'audiokpbs' => Column::BIND_PARAM_INT,
                'drm' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
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

    //属性获取
    public function getId() {
        return $this->id;
    }
    public function getsStationsId() {
        return $this->stations_id;
    }
    public function getName() {
        return $this->name;
    }
    public function getWidth() {
        return $this->width;
    }
    public function getHeight() {
        return $this->height;
    }
    public function getCdn() {
        return $this->cdn;
    }
    public function getPercent() {
        return $this->percent;
    }
    public function getKpbs() {
        return $this->kpbs;
    }
    public function getAudioKpbs() {
        return $this->audiokpbs;
    }
    public function getDrm() {
        return $this->drm;
    }

    public static function getOne($epg_id) {
        $parameters = array();
        $parameters['conditions'] = "id=".$epg_id;
        return StationsEpg::findFirst($parameters);
    }

    //查找操作
    //获取所有节目流
    public static function getStationsEpg($stations_id=0) {
        if($stations_id>0) {
            $data = StationsEpg::query()->where("stations_id = {$stations_id}")->orderBy("sort desc, id asc")->paginate(50, 'Pagination');
        }
        else {
            $data = StationsEpg::query()->order("sort desc")->paginate(50, 'Pagination');
        }
        return $data;
    }
    //获取指定电视台的所有节目流
    public static function getStationsEpgByStationsId($stations_id) {
        $data = StationsEpg::query()
            ->where("stations_id = {$stations_id}")
            ->execute();
        return $data;
    }

    //指定节目流ID查找
    public static function getStationsEpgById($id) {
        return StationsEpg::findFirst($id);
    }

    //增加操作
    //节目流对象创建信息
    public function createStationsEpg($data) {
        isset($data['id'])?$data['id']=null:true;
        $this->assign($data);
        return ($this->save())?true:false;
    }

    //删除操作
    //根据节目流ID删除
    public static function deleteStationsEpg($id) {
        $stationsEpg=StationsEpg::findFirst($id);
        if($stationsEpg){
            return $stationsEpg->delete();
        }else{
            return false;
        }
    }

    //修改操作
    //节目流对象修改信息
    public function modifyStationsEpg($data){
        $this->assign($data);
        return ($this->update())?true:false;
    }

    //检验表单信息
    public static function checkForm($inputs){
        $validator = Validator::make(
            $inputs, [
            'stations_id' => 'required',
            'name' => 'required',
            'width' => 'required|max:4',
            'height' => 'required|max:4',
            'cdn' => 'required|url',
            'percent' => 'required',
            'kpbs' => 'required|max:4',
            'audiokpbs' => 'required|max:11',
            'drm' => 'required|max:4',
        ], [
                'stations_id.required' => '请填写频道ID',
                'name.required' => '请填写节目流名称',
                'width.required'=>'请填写宽度',
                'width.max'=>'宽度过长',
                'height.required'=>'请填写高度',
                'height.max'=>'高度过长',
                'cdn.required'=>'请填写cdn',
                'cdn.url'=>'请填写正确的url',
                'percent.required'=>'请填写percent',
                'kpbs.required'=>'请填写视频码率',
                'kpbs.max'=>'视频码率过大',
                'audiokpbs.required'=>'请填写音频码率',
                'audiokpbs.max'=>'音频码率过大',
                'drm.required'=>'请填写防盗链',
                'drm.max'=>'防盗链格式错误',
            ]
        );
        return $validator;
    }

    //检验表单信息
    public static function makeValidator($inputs){
        $validator = Validator::make(
            $inputs, [
            'name' => 'required',
            'width' => 'required|max:4',
            'height' => 'required|max:4',
            'cdn' => 'required|url',
            'percent' => 'required',
            'kpbs' => 'required|max:4',
            'audiokpbs' => 'required|max:11',
            'drm' => 'required|max:4',
        ], [
                'name.required' => '请填写节目流名称',
                'width.required'=>'请填写宽度',
                'width.max'=>'宽度过长',
                'height.required'=>'请填写高度',
                'height.max'=>'高度过长',
                'cdn.required'=>'请填写cdn',
                'cdn.url'=>'请填写正确的url',
                'percent.required'=>'请填写percent',
                'kpbs.required'=>'请填写视频码率',
                'kpbs.max'=>'视频码率过大',
                'audiokpbs.required'=>'请填写音频码率',
                'audiokpbs.max'=>'音频码率过大',
                'drm.required'=>'请填写防盗链',
                'drm.max'=>'防盗链格式错误',
            ]
        );
        return $validator;
    }

    /**
     * 通过频道代码获取 EPGs
     *
     * @param $channel_id
     * @param $main
     * @param string $type
     * @return bool
     */
    public static function tplByStationCode($channel_id, $main, $type='code', $key='cztv') {
        if(!in_array($type, ['code', 'id'])) {
            throw new \Phalcon\Mvc\Model\Exception('Invalid Epg main type '.$type);
        }
        $key = D::memKey('tplEpgs',['c'=>$channel_id, 'm'=>$main, 't'=>$type]);
        return MemcacheIO::snippet($key, 1800, function() use($channel_id, $main, $type) {
            $station = Stations::tplOne($channel_id, $main, $type);
            if($station) {
                $rs = self::query()
                    ->andCondition('stations_id', $station['id'])
                    ->execute()
                    ->toArray();
                foreach($rs as $idx => $r) {
                    $rs[$idx]['url'] = self::joinUrl($station, $r);
                }
                $station['epgs'] = $rs;
            } elseif($channel_id != 0) {
                $station = Stations::tplOne(0, $main, $type);
                if($station) {
                    $rs = self::query()
                        ->andCondition('stations_id', $station['id'])
                        ->execute()
                        ->toArray();
                    foreach($rs as $idx => $r) {
                        $rs[$idx]['url'] = self::joinUrl($station, $r);
                    }
                    $station['epgs'] = $rs;
                }
            }
            return $station;
        });
    }

    public static function joinUrl($station, $epg, $key='cztv') {
        if($station['channel_name']=='cloud') {
            $path = "";
            switch($station['customer_name']) {
                case "xiaoshan_01": $path = "http://m.l.cztv.com:554/xiaoshantv_edge/xiaoshan_13/playlist.m3u8"; break;
                case "shengzhou_01": $path = "http://m.l.cztv.com:554/shengzhoutv_edge/shengzhou_1/playlist.m3u8"; break;
                case "shengzhou_11": $path = "http://m.l.cztv.com:554/shengzhoutv_edge/shengzhou_2/playlist.m3u8"; break;
                case "zhuji_01": $path = ""; break;
                case "zhuji_11": $path = ""; break;
                case "shangyu_01": $path = "http://m.l.cztv.com:554/shangyutv_edge/shangyu_1/playlist.m3u8"; break;
                case "shangyu_02": $path = "http://m.l.cztv.com:554/shangyutv_edge/shangyu_2/playlist.m3u8"; break;
                case "shangyu_03": $path = "http://m.l.cztv.com:554/shangyutv_edge/shangyu_3/playlist.m3u8"; break;
                case "shangyu_11": $path = "http://m.l.cztv.com:554/shangyutv_edge/shangyu_4/playlist.m3u8"; break;
                case "quzhou_01": $path = "http://m.l.cztv.com:554/quzhoutv_edge/quzhou_1/playlist.m3u8"; break;
                case "yuyao_01": $path = "http://m.l.cztv.com:554/live_edge/yuyao_1/playlist.m3u8"; break;
                case "yuyao_11": $path = "http://hls1.yun.cztv.com/channels/lantian/sx3_yuyaoaudio01/128k.m3u8"; break;
            }
           return $path;
        }
        else {
            $middle = $station['channel_name'].'/'.$station['customer_name'];
            $t = time();
            $k = md5($key.'/'.$middle.$t);
            return $epg['cdn'].'/channels/'.$middle.'/'.$epg['name'].'.m3u8?k='.$k.'&t='.$t;
        }
    }

}