<?php

class AjaxController  extends InteractionBaseController {
    /**
     * AJAX
     */
    public function ajaxPostAction(){
        $ret = array(
            'name' => $_POST['name'],
            'gender' => $_POST['gender']
        );
        header('content-type:application:json;charset=utf8');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        echo json_encode($ret);
    }

    public function videopieceAction() {
        $category_id = Request::getQuery('category', 'int', 0);
        $date  = Request::getQuery('date', 'string');
        $size = Request::getQuery('size', 'int', 10);
        $channel_id = Request::getQuery('channel_id', 'int', 6);

        $starttime = strtotime($date." 00:00:00");
        $endtime = strtotime($date." 23:59:59");

            $videos = Data::channelQuery($channel_id)
            ->andWhere('Data.created_at>'.$starttime.' AND Data.created_at<'.$endtime.' AND cd.category_id = :category_id: AND Data.status = :status: AND type="video"', ['category_id' => $category_id, 'status' => 1])
            ->leftJoin('CategoryData', 'cd.data_id = Data.id', 'cd')
            ->orderBy('created_at ASC')
            ->paginate($size, 'SmartyPagination', 1);
        $data = array();
        foreach($videos->models as $v) {
            $data[] = array(
                'url' => "/video/detail/".$v->id,
                'title' => $v->title,
                'intro' => $v->intro,
                'thumb' => $v->thumb,
                'updated_at' => date("Y-n-d H:i", $v->updated_at),
                'created_at' => date("Y-n-d H:i", $v->created_at),
              );
        }
        $this->jsonp(array('code' => 200, 'success' => 0, 'data' => $data));

    }

    public function videoinfoAction() {
        $category_id = Request::getQuery('category', 'int', 0);
        $date  = Request::getQuery('date', 'string');
        $starttime = strtotime($date." 00:00:00");
        $endtime = strtotime($date." 23:59:59");
        $size = Request::getQuery('size', 'int', 10);
        $channel_id = Request::getQuery('channel_id', 'int', 6);

        $videos = Data::channelQuery($channel_id)
            ->andWhere('Data.created_at>'.$starttime.' AND Data.created_at<'.$endtime.' AND cd.category_id = :category_id: AND Data.status = :status: AND type="video"', ['category_id' => $category_id, 'status' => 1])
            ->leftJoin('CategoryData', 'cd.data_id = Data.id', 'cd')
            ->orderBy('weight DESC, cd.sort DESC, created_at DESC')
            ->paginate($size, 'SmartyPagination', 1);
        $data = array();
        foreach($videos->models as $v) {
            $playing_file = SmartyData::getVideo($v->id, 'web');
            $data[] = array(
                'video_id' => $v->id,
                'title' => $v->title,
                'path' =>(false===stripos($playing_file['files'][0]['path'],'video.xianghunet.com'))?cdn_url("video", $playing_file['files'][0]['path']):$playing_file['files'][0]['path'],
            );
            break;
        }
        $this->jsonp(array('code' => 200, 'success' => 0, 'data' => $data));

    }


    public function datalistAction() {
        $category_id = intval(Request::getQuery('category', 'int', 0));
        $channel_id = intval(Request::getQuery('channel_id', 'int', 0));
        $page = intval(Request::getQuery('page', 'int', 0));
        $size = intval(Request::getQuery('size', 'int', 0));
        $page_size = ($size)?:10;
        $datalist = Data::channelQuery($channel_id)
            ->andWhere('cd.category_id = :category_id: AND Data.status = :status:', ['category_id' => $category_id, 'status' => 1])
            ->leftJoin('CategoryData', 'cd.data_id = Data.id', 'cd')
            ->orderBy('weight DESC, cd.sort DESC, created_at DESC')
            ->paginate($page_size, 'SmartyPagination', $page);
        $data = array();
        foreach($datalist->models as $v) {
            $data[] = array(
                'id' => $v->id,
                'title' => $v->title,
                'type' => $v->type,
                'url' => ($v->type=='news')?'/news/detail/'.$v->id.".html":'/video/detail/'.$v->id.".html",
                'image' =>  cdn_url('image', $v->thumb),
                'date' => date("Y-n-d H:s", $v->created_at),
            );
        }
        $this->jsonp(array('code' => 200, 'success' => 0, 'item' => $data));

    }
}