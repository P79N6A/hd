<?php

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/5/17
 * Time: 9:18
 */
class UgcLiveRoomController extends InteractionBaseController
{
    public function getRoomInfoAction()
    {
        $room_id = Request::getQuery('room_id','int','0');
        $model = new UgcLiveRoom();
        $room = $model->findFirst($room_id);
        $ret = array();
        if ($room) {
            $anchorModel = new Admin();
            $anchor = $anchorModel->findFirst($room->admin_id);
            $ret = array(
                'thumb' => $room->thumb,
                'name' => $room->roomname,
                'anchor_id' => $anchor->id,
                'anchor_name' => $anchor->name,
                'intro' => $room->intro,
                'anchor_avatar' => $anchor->avatar);
        }
        $this->jsonp($ret);
    }

    public function getRoomStreamAction()
    {
        $ret = array();
        if ($room_id = Request::getQuery('room_id', 'int', 0)) {
            $model_room = new UgcLiveRoom();
            $model_stream = new UgcLive();
            $room = $model_room->findFirst($room_id);
            $data = $model_stream->find(
                array('conditions' => " stream_event = :event: AND admin_id = :admin_id:",
                    'bind' => array('event' => 'start', 'admin_id' => $room->admin_id)))->toArray();

            foreach ($data as $stream) {
                array_push($ret, array(
                    'rtmp_url' => $stream['rtmp_url'],
                    'cdn_url1' => $stream['cdn_url1'],
                    'cdn_url2' => $stream['cdn_url2'],
                    'cdn_url3' => $stream['cdn_url3'],
                    'terminal' => $stream['terminal'],
                ));
            }
        }
        $this->jsonp($ret);
    }

    public function getRoomListAction()
    {
        $model = new UgcLiveRoom();
        $list = $model->find(
            array(
                'conditions' => "runstatus = :runstatus:",
                'bind' => array('runstatus' => '1'),
                'columns' => 'id,roomname,thumb'))->toArray();
        $this->jsonp($list);
    }
    
    
    
    
}


