<?php

/**
 * @RoutePrefix("/albums")
 */
class AlbumsController extends ApiBaseController {

    /**
     * @Get('/')
     */
    public function indexAction() {
        $return = [];
        $tree = Category::getTree(self::$terminal,$this->channel_id);
        $data = Category::listCategory(self::$terminal,false,$this->channel_id);
        $top = $tree->getChild(0);
        foreach($top as $k=>$v){
            $return[] = $this->parseFields($data[$v]);
            $child = $tree->getChild($v);
            if(!empty($child)){
                foreach($child as $v){
                    $return[$k]['child'][] = $this->parseFields($data[$v]);
                }
            }
        }
        $this->_json($return);
    }

    private function constructInfoResult($favorites, $comment, $data, $images) {
        $result = array();
        $result['favorites'] = $favorites;
        $result['comment'] = $comment;
        $keys = array('title' => 'name', 'intro' => 'intro');
        foreach($data as $key=>$value) {
            if (array_key_exists($key, $keys)) {
                $result[$keys[$key]] = $value;
            }
        }
        $keys = array('id' => 'id', 'intro' => 'intro', 'path' => 'thumb');
        $result['thumbs'] = array();

        foreach($images as $image) {
            $imageArr = array();
            foreach($image as $key=>$value) {
                if (array_key_exists($key, $keys)) {
                    if($keys[$key]=='intro'&&$value=="") $value =$result['intro'];                    
					if($keys[$key]=='thumb') {
                        $imageArr[$keys[$key]] = (false===stripos($value, "image.xianghunet.com"))?cdn_url('image', $value):$value;
                    }
                    else {
                        $imageArr[$keys[$key]] = $value;
                    }
                }
            }
            array_push($result['thumbs'], $imageArr);
        }
        return $result;
    }
    /**
     * 相册的信息
     * @Get('/{id:[0-9]+}')
     * @param int $id
     * @return json
     */
    public function infoAction($id){
        $data = Data::apiGetDataById($this->channel_id, $id);
        $images = AlbumImage::apiFindByData($data['source_id']);
        //$favorites = Favorites::apiCountFavorites($this->channel_id, $data->id);
        $favorites = 300;
        $comment = UserComments::apiCountComment($this->channel_id, $data['id']);
        $result = $this->constructInfoResult($favorites, $comment, $data, $images);

        $this->_json($result);
    }



    /**
     * 推荐相册
     * @Get('/recommend')
     * @param itn $id
     * @return json
     */
    public function recommendAction() {
        $parameters = array();
        $parameters['conditions'] = "channel_id=".$this->channel_id." and code='推荐图集'";
        $cat = Category::findFirst($parameters);
        $return = [];
        $feature = Features::apiGetFeatures($this->channel_id, $cat->id);
        if(!empty($feature)) {
            foreach($feature as $v) {
                $return[] = [
                    "id" => $v['id'],
                    "type" => $v['type'],
                    "title" => $v['title'],
                    "intro" => $v['intro'],
                    "thumb" => (false===stripos($v['thumb'], "image.xianghunet.com"))?cdn_url('image',$v['thumb']):$v['thumb'],
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
     * 过滤必需字段
     * @param array $data
     * @return array
     */
    private function parseFields($data) {
        $fields  = ['id','name','logo','app_style'];
        foreach($data as $k => $v) {
            if(!in_array($k, $fields)) {
                unset($data[$k]);
            } else if($data[$k] == null) {
                $data[$k] = "";
            }
        }
        return $data;
    }

    /**
     * 新建相册
     * @Post('/add')
     * @param itn $id
     * @return json
     */
    public function addAction() {


    }
}
