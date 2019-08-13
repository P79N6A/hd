<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class VideoLikes extends Model {
    private $_cache_expire = 2592000; // 过期时间30天
    private $_cache_prefix = 'VLIKE:';  // 缓存前缀
    private $_cache_prefix_count = 'CNT_VLIKE:';  // 缓存前缀

    public function initialize() {
        //使用互动数据库链接
        $this->setConnectionService('db_interactive');
    }

    public function getSource() {
        return 'video_likes';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'user_id', 'target_type', 'target_id', 'from_type', 'device_id', 'create_time', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['user_id', 'target_type', 'target_id', 'from_type', 'device_id', 'create_time', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'target_type', 'target_id', 'from_type', 'create_time', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'target_type' => Column::TYPE_INTEGER,
                'target_id' => Column::TYPE_INTEGER,
                'from_type' => Column::TYPE_INTEGER,
                'device_id' => Column::TYPE_VARCHAR,
                'create_time' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_CHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'user_id', 'target_type', 'target_id', 'from_type', 'create_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'target_type' => Column::BIND_PARAM_INT,
                'target_id' => Column::BIND_PARAM_INT,
                'from_type' => Column::BIND_PARAM_INT,
                'device_id' => Column::BIND_PARAM_STR,
                'create_time' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'target_type' => '1',
                'from_type' => '1',
                'device_id' => '0',
                'status' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * @param $device
     * @param $target_type
     * @param $target_id
     * @return string
     */
    public function generateCacheKey($device, $target_type, $target_id) {
        return $this->_cache_prefix . "$device:$target_type:$target_id";
    }

    public function generateCountKey($target_id, $target_type) {
        return $this->_cache_prefix_count . ":" . $target_id . ":" . $target_type;
    }

    public function add($data) {
        $target_type = $data['target_type'];
        $target_id = $data['target_id'];
        $device_id = $data['device_id'];

        assert(!empty($device_id));
        assert(!empty($target_type));
        assert(!empty($target_id));

        $cacheKey = $this->generateCacheKey($device_id, $target_type, $target_id);

        $val = RedisIO::get($cacheKey);
        if ($val) {
            // 缓存里有，这个用户已经赞过了
            return -1;
        }

        if (empty($data['create_time'])) {
            $data['create_time'] = time();
        }

        $where = 'device_id = \'' . $device_id . '\' AND target_id = ' . $target_id . ' AND target_type = ' . $target_type;
        $result = self::count($where);
        if ($result === 0) {
            // 点赞
            $this->assign($data);
            $ret = $this->save();
            // 删除count的缓存
            $countCacheKey = $this->generateCountKey($target_id, $target_type);
            RedisIO::delete($countCacheKey); # 可考虑使用incr
        } else {
            // 已经赞过
            $ret = $result;
        }
        if ($ret) {
            // 用于判断用户是否已经点过赞了
            RedisIO::set($cacheKey, 1);
        }

        return $ret;
    }

    /**
     * @param string $target_id
     * @param null|string $target_type
     * @return int 计算用户收藏视频数目
     */
    public function countLikes($target_id, $target_type) {
        $countCacheKey = $this->generateCountKey($target_id, $target_type);
        $total = RedisIO::get($countCacheKey);
        if (!$total) {
            //如果缓存没有, 查询数据库
            $total = self::count('target_id = ' . $target_id . ' AND target_type= ' . $target_type);
            if ($total) {
                RedisIO::set($countCacheKey, $total);
            }
        }

        return $total;
    }


    public function checkIsLike($data) {
        $target_type = $data['target_type'];
        $target_id = $data['target_id'];
        $device_id = $data['device_id'];

        assert(!empty($device_id));
        assert(!empty($target_type));
        assert(!empty($target_id));

        $cacheKey = $this->generateCacheKey($device_id, $target_type, $target_id);

        $val = RedisIO::get($cacheKey);
        if ($val) {
            // 缓存里有，这个用户已经赞过了
            return 1;
        } else {
            return -1;
        }
    }
}