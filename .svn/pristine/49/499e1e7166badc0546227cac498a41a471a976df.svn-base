<?php

class FavoriteController extends FavoriteBaseController {

    public function indexAction() {
        echo 'ok';
    }


    /**
     * @desc 收藏
     * @param
     * video_id 视频ID
     * play_id  专辑ID
     * from_type
     * product
     * favorite_type
     * channel_id
     */
    public function addAction() {
        $video_id = intval(Request::getQuery('video_id', null, 0));
        $play_id = intval(Request::getQuery('play_id', null, 0));
        $from_type = intval(Request::getQuery('from_type', null, 1));
        $favorite_type = intval(Request::getQuery('favorite_type', null, 1));
        $channel_id = trim(Request::getQuery('channel_id', null, LETV_CHANNEL_ID));
        $user_id = My::CurrentUserUid();    //获取当前用户ID
        $callback = trim(Request::getQuery('callback', null, ''));
        if (empty($user_id)) {
            $this->favorite_json_output(403, array('msg' => '无法获取用户信息'), $callback);
        }
        $product = 0;

        if (1 == $favorite_type) {
            return $this->addPlay($user_id, $play_id, $video_id, $channel_id, $from_type, $favorite_type, $product, $callback);
        } else {
            return $this->addChannel($user_id, $channel_id, $from_type, $favorite_type, $product, $callback);
        }
    }

    private function addPlay($user_id, $play_id, $video_id, $channel_id, $from_type, $favorite_type, $product, $callback) {
        if (empty($video_id) && empty($play_id)) {
            $this->favorite_json_output(404, array('msg' => '视频ID和专辑ID不能同时为空'), $callback);
        }
        //存在视频ID逻辑
        if (!empty($video_id)) {
            /*获取视频信息*/
            $mmsModel = new FavoriteMms();
            $video_info = $mmsModel->GetVideoInfoCache(array($video_id));
            $video_info = current($video_info);
            if (empty($video_info)) {
                $this->favorite_json_output(403, array('msg' => '无效视频信息'), $callback);
            }
            /*根据频道判断是否是跟播频道，进而获取专辑跟播状态*/
            $category = current(array_keys($video_info['category']));
            $play_id = intval($video_info['pid']);
        }
        /*组装数据*/
        $data['user_id'] = $user_id;
        $data['video_id'] = $video_id;
        $data['play_id'] = $play_id;
        $data['category'] = $category;
        $data['channel_id'] = $channel_id;
        $data['from_type'] = $from_type;
        $data['product'] = $product;
        $data['favorite_type'] = $favorite_type;
        $data['follow_staus'] = 0;
        $data['end_time'] = 0;
        $data['create_time'] = time();
        $favoriteModel = new FavoriteList();
        $result = $favoriteModel->add($data);
        /*检查结果*/
        if (false == $result) {
            $this->favorite_json_output(500, array('msg' => '添加失败，稍后再试'), $callback);
        } else {
            $this->favorite_json_output(200, array('msg' => '添加成功'), $callback);
        }
    }

    private function addChannel($user_id, $channel_id, $from_type, $favorite_type, $product, $callback) {
        if (empty($channel_id)) {
            $this->favorite_json_output(404, array('msg' => '收藏对象ID不能同时为空'), $callback);
        }
        /*组装数据*/
        $data['user_id'] = $user_id;
        $data['favorite_type'] = $favorite_type;
        $data['channel_id'] = $channel_id;
        $data['from_type'] = $from_type;
        $data['product'] = $product;
        $data['create_time'] = time();
        $favoriteListModel = new FavoriteList();
        $result = $favoriteListModel->add($data);
        /*检查结果*/
        if (false == $result) {
            $this->favorite_json_output(500, array('msg' => '添加失败，稍有再试'), $callback);
        } else {
            $this->favorite_json_output(200, array('msg' => '添加成功'), $callback);
        }
    }

    /**
     * @desc 删除收藏
     * @param
     * video_id 视频ID
     * play_id  专辑ID
     */
    public function deleteAction() {
        $video_id = intval(Request::getQuery('video_id', null, 0));
        $play_id = intval(Request::getQuery('play_id', null, 0));
        $user_id = My::CurrentUserUid();    //获取当前用户ID
        $callback = trim(Request::getQuery('callback', null, ''));
        if (empty($user_id)) {
            $this->favorite_json_output(403, array('msg' => '无法获取用户信息'), $callback);
        }
        if (empty($video_id) && empty($play_id)) {
            $this->favorite_json_output(404, array('msg' => '视频ID和专辑ID不能同时为空'), $callback);
        }

        if (!empty($video_id)) {
            $mmsModel = new FavoriteMms();
            $video_info = $mmsModel->GetVideoInfoCache(array($video_id));
            $video_info = current($video_info);
            if (empty($video_info)) {
                $this->favorite_json_output(403, array('msg' => '无效视频信息'), $callback);
            }
            $category = current(array_keys($video_info['category']));
            $play_id = intval($video_info['pid']);
        }

        $favoriteModel = new FavoriteList();
        $result = $favoriteModel->deleteFavorite($user_id, $category, $play_id, $video_id);
        if (false === $result) {
            $this->favorite_json_output(500, array('msg' => '删除失败，稍有再试'), $callback);
        } else {
            $this->favorite_json_output(200, array('msg' => '删除成功'), $callback);
        }
    }

    /**
     * @desc 批量删除收藏
     * @param
     * favorite_id  多个收藏ID, 用逗号,隔开  1,2,3
     */
    public function multideleteAction() {
        $favorite_id = trim(Request::getQuery('favorite_id', null, ''));
        $user_id = My::CurrentUserUid();    //获取当前用户ID
        $callback = trim(Request::getQuery('callback', null, ''));
        if (empty($user_id)) {
            $this->favorite_json_output(403, array('msg' => '无法获取用户信息'), $callback);
        }
        foreach (explode(',', $favorite_id) as $key => $value) {
            $favorite_ids[] = intval($value);
        }
        $favoriteModel = new FavoriteList();
        $result = $favoriteModel->multideleteFavorite($user_id, $favorite_ids);
        if (false === $result) {
            $this->favorite_json_output(500, array('msg' => '删除失败，稍有再试'), $callback);
        } else {
            $this->favorite_json_output(200, array('msg' => '删除成功'), $callback);
        }
    }

    /**
     * @desc 收藏
     * @param
     * video_id 视频ID
     * play_id  专辑ID
     * favorite_type 收藏类型1:点播 2:直播 3:轮播 4:卫视
     */
    public function isfavoriteAction() {
        $video_id = intval(Request::getQuery('video_id', null, 0));
        $play_id = intval(Request::getQuery('play_id', null, 0));
        $channel_id = intval(Request::getQuery('channel_id', null, 1));
        $favorite_type = intval(Request::getQuery('favorite_type', null, 0));
        $user_id = My::CurrentUserUid();    //获取当前用户ID
        $callback = trim(Request::getQuery('callback', null, ''));
        if (empty($user_id)) {
            $this->favorite_json_output(403, array('msg' => '无法获取用户信息'), $callback);
        }
        if (empty($favorite_type)) {
            $this->favorite_json_output(404, array('msg' => '收藏类型不能为空'), $callback);
        }
        if (1 == $favorite_type) {
            return $this->isFavoritePlay($user_id, $favorite_type, $play_id, $video_id, $callback);
        } else {
            return $this->isFavoriteChannel($user_id, $favorite_type, $channel_id, $callback);
        }
    }

    private function isFavoriteChannel($user_id, $favorite_type, $channel_id, $callback) {
        $result = $this->model('Favorite')->isFavorite($user_id, $favorite_type, 0, 0, $channel_id);
        if ($result) {
            $this->favorite_json_output(200, array('msg' => '已经收藏'), $callback);
        } else {
            $this->favorite_json_output(201, array('msg' => '没有收藏'), $callback);
        }
    }

    private function isFavoritePlay($user_id, $favorite_type, $play_id, $video_id, $callback) {

        if (empty($video_id) && empty($play_id)) {
            $this->favorite_json_output(404, array('msg' => '视频ID和专辑ID不能同时为空'), $callback);
        }

        if (!empty($video_id)) {
            /*获取视频信息*/
            $mmsModel = new FavoriteMms();
            $video_info = $mmsModel->GetVideoInfoCache(array($video_id));
            $video_info = current($video_info);
            if (empty($video_info)) {
                $this->favorite_json_output(403, array('msg' => '无效视频信息'), $callback);
            }
            /*根据频道判断是否是跟播频道，进而获取专辑跟播状态*/
            $category = current(array_keys($video_info['category']));
            $favoriteModel = new FavoriteList();
            $result = $favoriteModel->isFavorite($user_id, $favorite_type, 0, $video_id);
        }
        if ($result) {
            $this->favorite_json_output(200, array('msg' => '已经收藏'), $callback);
        } else {
            $this->favorite_json_output(201, array('msg' => '没有收藏'), $callback);
        }
    }

    /**
     * @desc  列出收藏
     * @param
     * page         第几页
     * pagesize     每页显示收藏数量
     * category     频道ID   0:全部, 1:电影, 2:电视剧 ...
     * from_type    平台ID
     * favorite_type收藏类型1:点播 2:直播 3:轮播 4:卫视
     */
    public function listfavoriteAction() {
        $page = intval(Request::getQuery('page', null, 1));
        $pagesize = intval(Request::getQuery('pagesize', null, 10));
        $category = intval(Request::getQuery('category', null, 0));
        $user_id = My::CurrentUserUid();    //获取当前用户ID
        $from_type = intval(Request::getQuery('from_type', null, 1));
        $favorite_type = intval(Request::getQuery('favorite_type', null, 1));
        $callback = trim(Request::getQuery('callback', null, ''));
        if (empty($user_id)) {
            $this->favorite_json_output(403, array('msg' => '用户ID不能为空'), $callback);
        }
        $favoriteModel = new FavoriteList();
        $result = $favoriteModel->listFavorite($favorite_type, $category, $user_id, $page, $pagesize);
        $video_ids = array();
        foreach ($result['items'] as $key => $value) {
            if (1 == $value['favorite_type']) {
                if (!empty($value['video_id'])) {
                    $video_ids[] = $value['video_id'];
                }
            }
        }
        $mmsModel = new FavoriteMms();
        $video_info = $mmsModel->GetVideoInfoCache($video_ids);
        /*平台判断当前最新一集*/
        $from_type_configs = array(1 => 420001, 2 => 420007, 3 => 420003);

        foreach ($result['items'] as $key => $value) {
            if (1 == $value['favorite_type']) {
                $result['items'][$key]['starring'] = isset($video_info[$value['video_id']]['starring']) ? $video_info[$value['video_id']]['starring'] : '';
                $result['items'][$key]['singer'] = isset($video_info[$value['video_id']]['singer']) ? $video_info[$value['video_id']]['singer'] : '';
                $result['items'][$key]['offline'] = empty($video_info[$value['video_id']]) ? 1 : 0;
                $result['items'][$key]['title'] = isset($video_info[$value['video_id']]['nameCn']) ? $video_info[$value['video_id']]['nameCn'] : '';
                $result['items'][$key]['sub_title'] = isset($video_info[$value['video_id']]['subTitle']) ? $video_info[$value['video_id']]['subTitle'] : '';
                $result['items'][$key]['category_name'] = empty($video_info[$value['video_id']]['category']) ? '' : current(array_values($video_info[$value['video_id']]['category']));
                $result['items'][$key]['sub_category'] = isset($video_info[$value['video_id']]['subCategory']) ? current(array_values($video_info[$value['video_id']]['subCategory'])) : '';
                $result['items'][$key]['platform_can_play'] = isset($video_info[$value['video_id']]['playPlatform'][$from_type_configs[$from_type]]) ? 1 : 0;
                $result['items'][$key]['pic_all'] = isset($video_info[$value['video_id']]['picAll']) ? $video_info[$value['video_id']]['picAll'] : '';
            }
            //获取产品名称
            $result['items'][$key]['product'] = intval($value['product']);
            $result['items'][$key]['productName'] = '';
        }
        $this->favorite_json_output(200, $result, $callback);
    }

    /**
     * @desc  按时间轴列出收藏
     * @param
     * page         第几页
     * pagesize     每页显示收藏数量
     * category     频道ID   0:全部, 1:电影, 2:电视剧 ...
     * from_type    平台ID
     * favorite_type收藏类型1:点播 2:直播 3:轮播 4:卫视
     */
    public function listfavoritetimelineAction() {
        $page = intval(Request::getQuery('page', null, 1));
        $pagesize = intval(Request::getQuery('pagesize', null, 10));
        $category = intval(Request::getQuery('category', null, 0));
        $user_id = My::CurrentUserUid();    //获取当前用户ID
        $from_type = intval(Request::getQuery('from_type', null, 1));
        $favorite_type = intval(Request::getQuery('favorite_type', null, 1));
        $callback = trim(Request::getQuery('callback', null, ''));
        if (empty($user_id)) {
            $this->favorite_json_output(403, array('msg' => '用户ID不能为空'), $callback);
        }
        $favoriteModel = new FavoriteList();
        $result = $favoriteModel->listFavoriteTimeLine($favorite_type, $category, $user_id, $page, $pagesize);
        $video_ids = array();
        foreach ($result['items'] as $key => $value) {
            if (1 == $value['favorite_type']) {
                if (!empty($value['video_id'])) {
                    $video_ids[] = $value['video_id'];
                }
            }
        }
        $mmsModel = new FavoriteMms();
        $video_info = $mmsModel->GetVideoInfoCache($video_ids);
        /*平台判断当前最新一集*/
        $from_type_configs = array(1 => 420001, 2 => 420007, 3 => 420003);

        foreach ($result['items'] as $key => $value) {
            if (1 == $value['favorite_type']) {
                $result['items'][$key]['starring'] = $video_info[$value['video_id']]['starring'];
                $result['items'][$key]['singer'] = $video_info[$value['video_id']]['singer'];
                $result['items'][$key]['offline'] = empty($video_info[$value['video_id']]) ? 1 : 0;
                $result['items'][$key]['title'] = isset($video_info[$value['video_id']]['nameCn']) ? $video_info[$value['video_id']]['nameCn'] : '';
                $result['items'][$key]['sub_title'] = isset($video_info[$value['video_id']]['subTitle']) ? $video_info[$value['video_id']]['subTitle'] : '';
                $result['items'][$key]['category_name'] = empty($video_info[$value['video_id']]['category']) ? '' : current(array_values($video_info[$value['video_id']]['category']));
                $result['items'][$key]['sub_category'] = isset($video_info[$value['video_id']]['subCategory']) ? current(array_values($video_info[$value['video_id']]['subCategory'])) : '';
                $result['items'][$key]['platform_can_play'] = isset($video_info[$value['video_id']]['playPlatform'][$from_type_configs[$from_type]]) ? 1 : 0;
                $result['items'][$key]['pic_all'] = isset($video_info[$value['video_id']]['picAll']) ? $video_info[$value['video_id']]['picAll'] : '';
            }
            //获取产品名称
            $result['items'][$key]['product'] = intval($value['product']);
            $result['items'][$key]['productName'] = '';
        }
        $this->favorite_json_output(200, $result, $callback);
    }

    /**
     * @desc 获取登录用户收藏视频数目
     * @param
     */
    public function countAction() {
        $user_id = My::CurrentUserUid();    //获取当前用户ID
        $callback = trim(Request::getQuery('callback', null, ''));
        $favoriteModel = new FavoriteList();
        $result = $favoriteModel->countVideo($user_id);
        if (empty($user_id)) {
            $this->favorite_json_output(403, array('msg' => '用户ID不能为空'), $callback);
        }
        $this->favorite_json_output(200, $result, $callback);
    }

}

