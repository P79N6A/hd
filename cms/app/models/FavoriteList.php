<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Mvc\Model\Query;
use Phalcon\Db\Column;

class FavoriteList extends Model {
    //缓存key-value格式 "FAV:$uid:$type:$id" => 0 or 1
    //$type 'p'表示play_id,'v'表示video_id,'c'表示channel_id
    //0表示未收藏，1表示收藏
    private $_cache_expire = 2592000; // 过期时间30天
    private $_cache_prefix = 'FAV:';  // 缓存前缀

    public function initialize() {
        //使用互动数据库链接
        $this->setConnectionService('db_interactive');
    }

    public function getSource() {
        return 'favorite_list';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'user_id', 'favorite_type', 'channel_id', 'video_id', 'play_id', 'category', 'episode', 'from_type', 'product', 'follow_staus', 'end_time', 'create_time',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['user_id', 'favorite_type', 'channel_id', 'video_id', 'play_id', 'category', 'episode', 'from_type', 'product', 'follow_staus', 'end_time', 'create_time',],
            MetaData::MODELS_NOT_NULL => ['id', 'user_id', 'video_id', 'play_id', 'category', 'episode', 'from_type', 'follow_staus', 'end_time', 'create_time',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'favorite_type' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_VARCHAR,
                'video_id' => Column::TYPE_INTEGER,
                'play_id' => Column::TYPE_INTEGER,
                'category' => Column::TYPE_INTEGER,
                'episode' => Column::TYPE_INTEGER,
                'from_type' => Column::TYPE_INTEGER,
                'product' => Column::TYPE_INTEGER,
                'follow_staus' => Column::TYPE_INTEGER,
                'end_time' => Column::TYPE_INTEGER,
                'create_time' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'user_id', 'favorite_type', 'video_id', 'play_id', 'category', 'episode', 'from_type', 'product', 'follow_staus', 'end_time', 'create_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'favorite_type' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_STR,
                'video_id' => Column::BIND_PARAM_INT,
                'play_id' => Column::BIND_PARAM_INT,
                'category' => Column::BIND_PARAM_INT,
                'episode' => Column::BIND_PARAM_INT,
                'from_type' => Column::BIND_PARAM_INT,
                'product' => Column::BIND_PARAM_INT,
                'follow_staus' => Column::BIND_PARAM_INT,
                'end_time' => Column::BIND_PARAM_INT,
                'create_time' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'favorite_type' => '1',
                'channel_id' => '0',
                'video_id' => '0',
                'play_id' => '0',
                'category' => '0',
                'episode' => '0',
                'from_type' => '1',
                'product' => '0',
                'follow_staus' => '0',
                'end_time' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    private function setCache($uid, $type, $id, $value) {
        RedisIO::set($this->_cache_prefix . "$uid:$type:$id", $value, $this->_cache_expire);
    }

    private function getCache($uid, $type, $id) {
        return RedisIO::get($this->_cache_prefix . "$uid:$type:$id");
    }

    public function add($data) {
        if (1 == $data['favorite_type']) {
            //综艺和纪录片等其它频道支持收藏VID
            $result = self::findFirst('user_id = ' . $data['user_id'] . ' AND video_id = ' . $data['video_id']);
            $type = 'v';
            $id = $data['video_id'];
        } else {
            $result = self::findFirst('user_id = ' . $data['user_id'] . ' AND favorite_type = ' . $data['favorite_type'] . ' AND channel_id = ' . $data['channel_id']);
            $type = 'c';
            $id = $data['channel_id'];
        }

        if (!empty($result)) {
            $ret = $result->update($data);
        } else {
            $this->assign($data);
            $ret = $this->save();
        }
        if ($ret) {
            self::setCache($data['user_id'], $type, $id, 1);
        }
        return $ret;
    }

    public function deleteFavorite($user_id, $category, $play_id, $video_id) {
        if (!empty($video_id)) {
            //其它频道删除视频收藏记录
            //直接执行sql, 需要引入 Phalcon\Mvc\Model\Query, delete from后面写的不是数据表的名字, 而是数据表对应model的名字,
            $sql = 'DELETE FROM FavoriteList WHERE user_id = ' . $user_id . ' AND video_id = ' . $video_id;
            $query = new Query($sql, $this->getDI());
            $result = $query->execute();
            self::setCache($user_id, 'v', $video_id, 0);
            return $result;
        } else {
            return false;
        }
    }

    public function listFavorite($favorite_type = 1, $category = 0, $user_id, $page = 1, $pagesize = 10) {

        $where = 'user_id = ' . $user_id;
        if (!empty($category)) {
            $where .= ' AND category = ' . $category;
        }
        if (!empty($favorite_type)) {
            $where .= ' AND favorite_type = ' . $favorite_type;
        }
        //计算总数
        $total = self::count($where);

        //读取列表数据
        $result = self::query()
            ->columns('id AS favorite_id,favorite_type,channel_id,video_id,play_id,category,episode,product,create_time')
            ->where($where)
            ->orderBy('create_time desc')
            ->limit($pagesize, ($page - 1) * $pagesize)
            ->execute()
            ->toArray();

        return array('page' => $page, 'pagesize' => $pagesize, 'total' => $total, 'items' => $result);
    }

    /**
     *
     * 根据时间轴列出收藏, 第一次加载最近7天的, 以后加载7天之前的
     *
     * 2016-07-05 饶佳修改
     * 饶佳取消7天内的加载区别
     *
     * @param type $favorite_type
     * @param type $category
     * @param type $user_id
     * @param type $page 1:获取最近7天的,   >1:分页获取7天之前的
     * @param type $pagesize
     * @return type
     */
    public function listFavoriteTimeLine($favorite_type = 1, $category = 0, $user_id, $page = 1, $pagesize = 10) {

        $start_time = time();
        $end_time = $start_time - 7 * 86400;

        //查询限制条件
        $sql_where = 'user_id = ' . $user_id;
        if (!empty($category)) {
            //$sql_where .= ' AND category = ' . $category;
        }
        if (!empty($favorite_type)) {
            $sql_where .= ' AND favorite_type = ' . $favorite_type;
        }

        //计算总数
        $total = self::count($sql_where);

        //获取7天之前收藏的视频数量
        $sql_early_where = $sql_where . ' AND create_time <= ' . $end_time;
        $early_total = self::count($sql_early_where);

        //查询的字段
        $colums = 'id AS favorite_id,favorite_type,channel_id,video_id,play_id,category,episode,product,create_time';

        $items = array();
        //饶佳修改,取消7天内的差别

        /*if (1 == $page) {
            //第一页还需查询七天内的收藏数据
            $sql_latest_list_where = $sql_where . ' AND create_time > ' . $end_time . ' AND create_time <= ' . $start_time;
            $items = self::query()
                ->columns($colums)
                ->where($sql_latest_list_where)
                ->orderBy('create_time desc')
                ->execute()
                ->toArray();
        } else {
            //获取7天之前收藏的视频信息
            $sql_early_list_where = $sql_where . ' AND create_time <= ' . $end_time;*/
        $sql_early_list_where = $sql_where;
        $items = self::query()
            ->columns($colums)
            ->where($sql_early_list_where)
            ->orderBy('create_time desc')
            ->limit($pagesize, ($page - 1) * $pagesize)
            ->execute()
            ->toArray();
        /*}*/

        return array(
            'items' => $items,
            'page' => $page,
            'pagesize' => $pagesize,
            'total' => $total,                          //总的收藏数量
            'latest_total' => $total - $early_total,    //最近的收藏数量
            'early_total' => $early_total,              //之前的收藏数量
        );
    }

    //检测是否收藏
    public function isFavorite($user_id, $favorite_type, $play_id = 0, $video_id = 0, $channel_id = 0) {
        if (empty($user_id) || empty($play_id) && empty($video_id) && empty($channel_id)) {
            return false;
        }
        $isFavorite = false;
        $type = $id = '';
        if ($play_id) {         //电影，电视剧，动漫
            $type = 'p';
            $id = $play_id;
        } else if ($video_id) {  //综艺
            $type = 'v';
            $id = $video_id;
        } else {                //直播轮播卫视
            $type = 'c';
            $id = $channel_id;
        }
        $isFavorite = self::getCache($user_id, $type, $id);
        if ($isFavorite === false) {
            $where = 'user_id = ' . $user_id . ' AND favorite_type = ' . $favorite_type;
            if ($play_id) {
                $where .= ' AND play_id = ' . $play_id;
            }
            if ($video_id) {
                $where .= ' AND video_id = ' . $video_id;
            }
            if ($channel_id) {
                $where .= ' AND channel_id = ' . $channel_id;
            }
            $result = self::findFirst($where);
            $isFavorite = empty($result) ? 0 : 1;
            self::setCache($user_id, $type, $id, $isFavorite);
        }
        return $isFavorite == 0 ? false : true;
    }

    //批量删除
    public function multideleteFavorite($user_id, array $favorite_id_arr) {
        //将数组元素转为int型, 防止sql注入
        foreach ($favorite_id_arr as $key => $favorite_id) {
            $favorite_id_arr[$key] = intval($favorite_id);
        }

        $favorite_ids = implode(',', $favorite_id_arr);
        $where = 'user_id = ' . $user_id . ' AND id in (' . $favorite_ids . ')';
        $list = self::query()
            ->where($where)
            ->execute();

        $result = true;
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                //清除缓存
                $type = 'v';
                $id = $value->video_id;
                self::setCache($user_id, $type, $id, 0);

                //删除数据库记录, 在删除缓存之后操作, 否则取不到video_id
                $result = $value->delete();
            }
        }
        return $result;
    }

    //计算用户收藏视频数目
    public function countVideo($user_id) {
        $result = self::count('user_id = ' . $user_id);
        return $result;
    }

}