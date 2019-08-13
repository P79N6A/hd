<?php

/**
 * Created by PhpStorm.
 * User: sylar
 * Date: 2015/12/15
 * Time: 10:11
 */
class VideoController extends ApiBaseController {
    private function constructInfoResult($data, $video, $files) {
        $keys = array('title' => 'title', 'intro' => 'intro', 'thumb' => 'icon'
        );
        $result = array();
        foreach($data as $key=>$value) {
            if (array_key_exists($key, $keys)) {
                $result[$keys[$key]] = $value;
            }
        }
        $keys = array('id'=>'id', 'duration' => 'duration');
        foreach($video as $key=>$value) {
            if (array_key_exists($key, $keys)) {
                $result[$keys[$key]] = intval($value);
            }
        }

        $result['files'] = array();
        foreach($files as $file) {
            array_push($result['files'], $file);
        }
        return $result;
    }
    /**
     * 视频的信息
     * @Get('/{id:[0-9]+}')
     * @param int $id
     * @return json
     */
    public function infoAction($id){
        $data = Data::apiFindBySourceId($this->channel_id, $id, 'video');
        $file = VideoFiles::apiGetFileByVideo($id)->toArray();
        $video = Videos::apiFindVideoById($id);
        $result = $this->constructInfoResult($data, $video, $file);
        $this->_json($result);
    }
}