<?php

/**
 * @RoutePrefix("/activity")
 */
class ActivityController extends ApiBaseController {

    /**
     * @Get("/")
     */
    public function indexAction() {
        $type = Request::getQuery('type');
        $data = Activity::apiGetActivity($this->channel_id,$type,$this->per_page,$this->page);
        if(!empty($data)){
            foreach($data as $v){
                $return['list'][] = [
                    "id" => $v['id'],
                    "type" => $v['type'],
                    "title" => $v['title'],
                    "intro" => $v['intro'],
                    "thumb" => $v['thumb'],
                    "comments" => $v['comments'],
                    'create_at' => $v['created_at'],
                    'wap_url' => $this->mediaUrl($v),
                    "thumbs" => $v['type'] == 'album'? $this->getAlbumImage($v['source_id']): [],
                ];
            }
        }
        $this->_json($return);
    }

    /**
     * @Get("/{id:[0-9]+}")
     * @param int $id
     * @return json
     */
    public function infoAction($id) {
        $id = (int)$id;
        $data = [
            "type" => 'activity',
            "title" => '测试',
            "intro" => '',
            "thumb" => '',
            "comments" => '',
            'create_at' => 123123123,
        ];
        $data = Activity::apiGetActivityById($this->channel_id,$id);
        $data['wap_url'] = 'http://m.baidu.com/?id='.$data['id'];
        $this->_json($data);
    }

    




}