<?php

class FavoriteMms {
    private $_video_cache_prefix = 'USER_FAV_VIDEO:';
    private $_video_cache_expire = 1800;
    private $_play_cache_prefix = 'USER_FAV_PLAY:';
    private $_play_cache_expire = 1800;

    /**
     * 从缓存中获取视频信息
     * @param  array $video_ids 视频ID
     * @return array
     */
    public function GetVideoInfoCache(array $video_ids) {
        if (empty($video_ids)) {
            return array();
        }
        foreach ($video_ids as $key => $value) {
            $video_cache_ids[$key] = $this->_video_cache_prefix . $value;
        }
        $video_info = RedisIO::mGet($video_cache_ids);
        $result_data = array();
        if (!empty($video_info)) {
            foreach ($video_info as $key => $value) {
                $value = json_decode($value, true);
                if (isset($value['id']) && !empty($value['id'])) {
                    $result_data[$value['id']] = $value;
                }
            }
        }

        $hit_keys = array_keys($result_data);
        $miss_keys = array_diff($video_ids, $hit_keys);

        if (!empty($miss_keys)) {
            $miss_video_info = $this->GetVideoInfo($miss_keys);
            if (!empty($miss_video_info)) {
                foreach ($miss_video_info as $key => $value) {
                    RedisIO::set($this->_video_cache_prefix . $key, json_encode($value), $this->_video_cache_expire);
                }
                $result_data += $miss_video_info;
            }
        }
        return $result_data;
    }

    /**
     * 获取视频信息
     * @param  array $video_ids 视频ID
     * @return array
     */
    public function GetVideoInfo(array $video_ids) {
        if (empty($video_ids)) {
            return false;
        }
        $request_url = 'http://api.cms.cztv.com/mms/inner/video/get?id=' . implode(',', $video_ids) . '&type=2&vmode=0&token=' . md5(implode(',', $video_ids) . 'usercenterzxcvbnm') . '&platform=usercenter';
        $request_result = curl_request($request_url);
        $request_result = json_decode($request_result, true);
        $request_data = $result_data = array();
        1 == count($video_ids) ? $request_data = array($request_result['data']) : $request_data = $request_result['data'];
        foreach ($request_data as $key => $value) {
            $result_data[$value['id']] = $value;
        }
        foreach ($video_ids as $key => $value) {
            if (!isset($result_data[$value])) {
                $result_data[$value] = array();
            }
        }
        return $result_data;
    }

}
