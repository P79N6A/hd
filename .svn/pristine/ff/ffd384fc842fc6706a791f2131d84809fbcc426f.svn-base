<?php

class VideoController extends FavoriteBaseController {

    public function indexAction() {
        echo 'lucky!';
    }

    /**
     * 点赞
     * <em>\@param：</em>
     * @param user_id 可选，用户ID
     * @param String device_id 设备ID
     * @param target_id 视频ID或者专辑ID
     * @param target_type 1： 视频 2：专辑
     * @param callback callback
     * @return int 点赞数
     */
    public function likeAction() {
        $debug = Request::getQuery("debug", null, "");

        $target_id = intval(Request::getQuery('target_id', null, 0));
        $target_type = intval(Request::getQuery('target_type', null, 1));

        $callback = trim(Request::getQuery('callback', null, ''));
        if (empty($target_id)) {
            $this->favorite_json_output(401, array('msg' => '无法获取视频或专辑信息'), $callback);
        }

        $user_id = My::CurrentUserUid();    //获取当前用户ID
        $device_id = Request::getQuery('device_id', null, ''); # device_id可能是IMEI

        if (empty($user_id) && empty($device_id)) {
            $this->favorite_json_output(402, array('msg' => "用户未登录或者缺少设备ID"), $callback);
        }

        # 验证源的有效性
        if ($target_type === 1) {
            // 验证视频信息
            $video_id = $target_id;
            $MmsModel = new FavoriteMms();
            $video_info = $MmsModel->GetVideoInfoCache(array($video_id)); # load from cache
            $video_info = current($video_info);
            if (empty($video_info)) {
                $video_info = $MmsModel->GetVideoInfo(array($video_id)); # load from rpc
                $video_info = current($video_info);
            }

            if (empty($video_info)) {
                $this->favorite_json_output(403, array('msg' => '无效视频信息,ID=' . $video_id), $callback);
            }
        }

        # insert into db
        $videoLikeModel = new VideoLikes();
        $db_data = array();
        $db_data['device_id'] = $device_id;
        $db_data['target_type'] = $target_type;
        $db_data['target_id'] = $target_id;
        $ret = $videoLikeModel->add($db_data);
        if ($ret === -1) {
            $this->favorite_json_output(405, array('msg' => "赞过[device_id=$device_id,target_id=$target_id,
            type=$target_type]"), $callback);
        }
        // count
        $countLikes = $videoLikeModel->countLikes($target_id, $target_type);

        $data = array('device' => $device_id,
            'count' => $countLikes
        );
        if (!empty($debug)) {
            $debug = array();
            $debug['video_info'] = $video_info;
            $debug['video_id'] = $video_id;
            $debug['target_type'] = $target_type;
            $debug['target_id'] = $target_id;
            $debug['pk_id'] = $ret;
            $data['debug'] = $debug;
        }
        $this->favorite_json_output(200, $data, $callback);

    }


    /**
     * 点赞计数
     */
    public function likesAction() {
        $target_id = intval(Request::getQuery('target_id', null, 0));
        $target_type = intval(Request::getQuery('target_type', null, 1));

        $callback = trim(Request::getQuery('callback', null, ''));
        if (empty($target_id)) {
            $this->favorite_json_output(401, array('msg' => '无法获取视频或专辑信息'), $callback);
        }

        $videoLikeModel = new VideoLikes();

        $countLikes = $videoLikeModel->countLikes($target_id, $target_type);

        $this->favorite_json_output(200, array("count" => $countLikes), $callback);
    }

    /**
     * 是否点赞
     */
    public function islikeAction() {
        $target_id = intval(Request::getQuery('target_id', null, 0));
        $target_type = intval(Request::getQuery('target_type', null, 1));

        $callback = trim(Request::getQuery('callback', null, ''));
        if (empty($target_id)) {
            $this->favorite_json_output(401, array('msg' => '无法获取视频或专辑信息'), $callback);
        }

        $user_id = My::CurrentUserUid();    //获取当前用户ID
        $device_id = Request::getQuery('device_id', null, ''); # device_id可能是IMEI

        if (empty($user_id) && empty($device_id)) {
            $this->favorite_json_output(402, array('msg' => "用户未登录或者缺少设备ID"), $callback);
        }

        $videoLikeModel = new VideoLikes();
        $db_data = array();
        $db_data['device_id'] = $device_id;
        $db_data['target_type'] = $target_type;
        $db_data['target_id'] = $target_id;
        $ret = $videoLikeModel->checkIsLike($db_data);
        $data = array(
            'islike' => $ret
        );
        $this->favorite_json_output(200, $data, $callback);
    }
}