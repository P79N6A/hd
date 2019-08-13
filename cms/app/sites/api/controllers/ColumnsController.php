<?php

/**
 * @RoutePrefix("/columns")
 */
class ColumnsController extends ApiBaseController {

    /**
     * @Get('/')
     */
    public function indexAction() {
        $return = [];
        $tree = Category::getTree(self::$terminal, $this->channel_id);
        $data = Category::listCategory(self::$terminal, false, $this->channel_id);
        $top = $tree->getChild(0);
        foreach($top as $k => $v) {
            $father = $data[$v];
            $return[] = [
                'id' => $father['id'],
                'name' => $father['name'],
                'logo' => cdn_url('image',$father['logo']),
                'app_style' => $father['app_style'],
                'allow_comment' => $father['allow_comment'],
            ];
            $child = $tree->getChild($v);
            if(!empty($child)) {
                foreach($child as $v) {
                    $cc = $data[$v];
                    $return[$k]['child'][] = [
                        'id' => $cc['id'],
                        'name' => $cc['name'],
                        'logo' => cdn_url('image',$cc['logo']),
                        'app_style' => $cc['app_style'],
                        'allow_comment' => $cc['allow_comment'],
                    ];
                }
            }
        }
        $this->_json($return);
    }

    /**
     * 某分类的新闻列表
     * @Get('/{id:[0-9]+}')
     * @param itn $id
     * @return json
     */
    public function infoAction($id) {
        $id = (int)$id;
        $cat  = Category::findById($id);

        $type = Request::getQuery('type');

        $data = CategoryData::apiFindByCategory($this->channel_id, $id, $this->per_page, $this->page);
        $this->initSmartData();
        $return = [];
        if(!empty($data)) {
            foreach($data as $k => $v) {
                if($cat->app_style==Category::APP_STYLE_PICTURE) {

                    //$image = new Imagick(app_site()->cdn_url->image.$v['thumb']);
                    //$width = $image->getImageWidth();
                    //$height = $image->getImageHeight();
                    $width = 640;
                    $height = 400;
                    $return['list'][] = [
                        "id" => $v['id'],
                        "type" => $v['type'],
                        "title" => $v['title'],
                        "intro" => $v['intro'],
                        "thumb" => (false===stripos($v['thumb'], "image.xianghunet.com"))?cdn_url('image',$v['thumb']):$v['thumb'],
                        "width" => $width,
                        "height" => $height,
                        "comments" => $v['comments'],
                        'create_at' => $v['created_at'],
                        'wap_url' => $this->mediaUrl($v),
                        "thumbs" => $v['type'] == 'album'? $this->getAlbumImage($v['source_id']): [],
                    ];
                }
                else {
                $return['list'][] = [
                    "id" => $v['id'],
                    "type" => $type?$type:$v['type'],
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
        }
        $region_id = (int)Request::getQuery('region_id');
        $feature = CategoryData::apiFindByCategory($this->channel_id, $id, $this->per_page, $this->page, 1);
        if(!empty($feature)) {
            foreach($feature as $v) {

                $return['hot'][] = [
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

}