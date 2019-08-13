<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class UgcLive extends Model {
    const STREAM_START = 'start';
    const STREAM_END = 'end';
    const STREAM_QUA_SUPPER_PIX = '1080'; //超清像素
    const STREAM_QUA_HIGH_PIX = '720'; //高清像素
    const STREAM_QUA_STANDARD_PIX = '480'; //标清像素
    const PAGE_SIZE = 25;

    public function getSource() {
        return 'ugc_live';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'data_id', 'admin_id', 'stream_event', 'stream_type', 'source_ip', 'start_time', 'end_time', 'domain', 'stream_name', 'path', 'rtmp_url', 'push_tool', 'width', 'height', 'vidio_framerate', 'videorate', 'videocoding_algorithm', 'audiorate', 'audio_framerate', 'audio_samplingrate', 'audio_channel', 'push_args', 'cdn_url1', 'cdn_url2', 'cdn_url3', 'is_rec', 'terminal',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['data_id', 'admin_id', 'stream_event', 'stream_type', 'source_ip', 'start_time', 'end_time', 'domain', 'stream_name', 'path', 'rtmp_url', 'push_tool', 'width', 'height', 'vidio_framerate', 'videorate', 'videocoding_algorithm', 'audiorate', 'audio_framerate', 'audio_samplingrate', 'audio_channel', 'push_args', 'cdn_url1', 'cdn_url2', 'cdn_url3', 'is_rec', 'terminal',],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'admin_id' => Column::TYPE_INTEGER,
                'stream_event' => Column::TYPE_INTEGER,
                'stream_type' => Column::TYPE_INTEGER,
                'source_ip' => Column::TYPE_VARCHAR,
                'start_time' => Column::TYPE_INTEGER,
                'end_time' => Column::TYPE_INTEGER,
                'domain' => Column::TYPE_VARCHAR,
                'stream_name' => Column::TYPE_VARCHAR,
                'path' => Column::TYPE_VARCHAR,
                'rtmp_url' => Column::TYPE_VARCHAR,
                'push_tool' => Column::TYPE_VARCHAR,
                'width' => Column::TYPE_FLOAT,
                'height' => Column::TYPE_FLOAT,
                'vidio_framerate' => Column::TYPE_INTEGER,
                'videorate' => Column::TYPE_INTEGER,
                'videocoding_algorithm' => Column::TYPE_VARCHAR,
                'audiorate' => Column::TYPE_VARCHAR,
                'audio_framerate' => Column::TYPE_VARCHAR,
                'audio_samplingrate' => Column::TYPE_VARCHAR,
                'audio_channel' => Column::TYPE_INTEGER,
                'push_args' => Column::TYPE_VARCHAR,
                'cdn_url1' => Column::TYPE_VARCHAR,
                'cdn_url2' => Column::TYPE_VARCHAR,
                'cdn_url3' => Column::TYPE_VARCHAR,
                'is_rec' => Column::TYPE_INTEGER,
                'terminal' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'data_id', 'admin_id', 'stream_event', 'stream_type', 'start_time', 'end_time', 'width', 'height', 'vidio_framerate', 'videorate', 'audio_channel', 'is_rec', 'terminal',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'admin_id' => Column::BIND_PARAM_INT,
                'stream_event' => Column::BIND_PARAM_INT,
                'stream_type' => Column::BIND_PARAM_INT,
                'source_ip' => Column::BIND_PARAM_STR,
                'start_time' => Column::BIND_PARAM_INT,
                'end_time' => Column::BIND_PARAM_INT,
                'domain' => Column::BIND_PARAM_STR,
                'stream_name' => Column::BIND_PARAM_STR,
                'path' => Column::BIND_PARAM_STR,
                'rtmp_url' => Column::BIND_PARAM_STR,
                'push_tool' => Column::BIND_PARAM_STR,
                'width' => Column::BIND_PARAM_STR,
                'height' => Column::BIND_PARAM_STR,
                'vidio_framerate' => Column::BIND_PARAM_INT,
                'videorate' => Column::BIND_PARAM_INT,
                'videocoding_algorithm' => Column::BIND_PARAM_STR,
                'audiorate' => Column::BIND_PARAM_STR,
                'audio_framerate' => Column::BIND_PARAM_STR,
                'audio_samplingrate' => Column::BIND_PARAM_STR,
                'audio_channel' => Column::BIND_PARAM_INT,
                'push_args' => Column::BIND_PARAM_STR,
                'cdn_url1' => Column::BIND_PARAM_STR,
                'cdn_url2' => Column::BIND_PARAM_STR,
                'cdn_url3' => Column::BIND_PARAM_STR,
                'is_rec' => Column::BIND_PARAM_INT,
                'terminal' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'stream_event' => 'start',
                'stream_type' => 'push',
                'push_tool' => 'obs_0202',
                'width' => '1280',
                'height' => '720',
                'vidio_framerate' => '123',
                'videorate' => '234',
                'videocoding_algorithm' => 'ACC',
                'audiorate' => '64',
                'audio_framerate' => '12',
                'audio_samplingrate' => '44,100',
                'audio_channel' => '2',
                'is_rec' => '1',
                'terminal' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    //TODO 获取指定主播是否在直播
    /* 
     * @desc 获取主播的主播状态
     * @param int 主播admin_id
     * 
     * */
    public static function getWorkStatus($admin_id) {
        return self::getLineNum($admin_id) > 0 ? Lang::_('work_status_online') : Lang::_('work_status_offline');
    }


    //TODO 获取指定主播的直播线路数量
    public static function getLineNum($admin_id) {
        return self::query()->andWhere('admin_id = :admin_id:')
            ->andWhere('stream_event = :event:')
            ->bind(array('admin_id' => $admin_id, 'event' => self::STREAM_START))->execute()->count();
    }

    /*
     * @desc 获取主播在线推流列表
     *
     * */
    public static function getOnlineList($admin_id) {
        return self::query()->andWhere('admin_id = :admin_id:')
            ->andWhere('stream_event = :event:')
            ->bind(array('admin_id' => $admin_id, 'event' => self::STREAM_START))->execute()->toArray();
    }

    public static function findAll($stream_event = '') {
        $criteria = self::query();
        if (!empty($stream_even))
            $criteria = $criteria->andWhere('stream_event = :event:')->bind(array('event' => $stream_event));
        return $criteria->Order("id DESC")->paginate(self::PAGE_SIZE, 'Pagination');
    }

    /*
     * @desc 计算CDN_URL数量
     *
     * */
    public static function countCdnUrl($id) {
        $model = new self();
        $stream = $model->findFirst($id);
        $cdn_urls = 0;
        if (!empty($stream->cdn_url1)) $cdn_urls++;
        if (!empty($stream->cdn_url2)) $cdn_urls++;
        if (!empty($stream->cdn_url3)) $cdn_urls++;
        return $cdn_urls;
    }

    /*
     * @desc 获取UgcLive流信息     *
     * */
    public static  function getUgcLiveInfoByMob($mobile,$channel_id)
    {
        $criteral = self::Query();
        return $criteral->join('Admin',"Admin.id = UgcLive.admin_id")
            ->columns(array("Admin.mobile","UgcLive.*"))
            ->where("Admin.mobile = '{$mobile}'")
            ->andWhere("Admin.channel_id = {$channel_id}")
            ->order("UgcLive.id Desc")
            ->first();
    }

    /*
     * @desc 获取UgcLive流信息
     * */
    public static function getUgcLiveInfoByStream($stream_name)
    {
        return self::query()->where("stream_name = '{$stream_name}'")->first();
    }




}