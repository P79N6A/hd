<?php

/**
 * @RoutePrefix("/news")
 */
class NewsController extends ApiBaseController {

    /**
     * @Get("/{id:[0-9]+}")
     * @param int $id
     * @return json
     */
    public function viewAction($id) {
        $id = (int)$id;
        $data = Data::apiGetDataById($this->channel_id, $id);
        if(!$data) {
            $this->_json([], 404, 'Not Found');
        }
        $video_templete = '<video id="videoPlay1" width="320px" height="200px" src="***videopath***"  poster="http://o.cztvcloud.com/static/videoplay.png" loop="loop" x-webkit-airplay="true" webkit-playsinline="true" controls="controls"></video>';
        $data_data_string = [];
        if($data['type']=='multimedia') {
            $data['type']='news';
        }
        foreach($data['quote'] as $k=>$v) {
            if($v['type']=="video") {
                $path = "";
                $rate = 0;
                foreach($v['files'] as $file) {
                    if($file['rate']>$rate) {
                        $path = $file['path'];
                    }
                }
                $data_data_string[$k] = str_replace("***videopath***", $path, $video_templete);
            }

        }
        $video_cdn = cdn_url('video');
        $image_cdn = cdn_url('image');
        $referer = Referer::apiListReferer($this->channel_id);
        $this->initSmartData();
        $content_string = SmartyData::getNewsContent($data['content'], $data_data_string);
        $content_string = str_ireplace("http://cloudimg.cztv.com", $image_cdn."oldcloudimg", $content_string);
        $content_string = str_ireplace("http://oyun.cztv.com", $image_cdn, $content_string);
        $content_string = str_ireplace("https://oyun.cztv.com", $image_cdn, $content_string);
        $return = [
            'id' => $data['id'],
            'type' => $data['type'],
            'content' => isset($data['content'])? $content_string: '',
            'thumb' => (false===stripos($data['thumb'], "image.xianghunet.com"))?cdn_url('image',$data['thumb']):$data['thumb'],
            'author_name' => $data['author_name'],
            'hits' => $data['hits'],
            'title' => $data['title'],
            'comments' => $data['comments'],
            'intro' => $data['intro'],
            'source' => isset($referer[$data['referer_id']])? ((false===stripos($referer[$data['referer_id']]['name'], "未知网站"))?$referer[$data['referer_id']]['name']:""): '',
            'thumbs' => $data['type'] == 'album'? $this->getAlbumImage($data['source_id']): [],
            'videos' => isset($data['videos']) ? $data['videos'] :'',
            'duration' => isset($data['duration']) ? $data['duration'] :'',
            'video_cdn' => ($this->channel_id==6&&$data['videodomain'])? $data['videodomain']:$video_cdn,
            // TODO 评论算法未定
            'no_comment' => 0,
            'created_at' => $data['created_at'],
            'wap_url' =>  ($data['redirect_url']=="")?$this->mediaUrl($data):$data['redirect_url'],
            'is_favorite' => 0,
            'usewap' => ($data['redirect_url']=="")?0:1
        ];
        $this->tryToken();
        if(!empty($this->user)) {
            $return['is_favorite'] = Favorites::apiIsFavorite($data['id'], $this->user->uid)? 1: 0;
        }
        $this->_json($return);
    }

}

