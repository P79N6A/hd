<?php

/**
 * @class:   AdminController
 * @author:  汤荷
 * @version: 1.0
 * @date:    2017/2/11
 */
class AdminController extends InteractionBaseController
{
    public function initialize() {
        parent::initialize();
    }

    public function listAction() {
        $category_id = Request::get("category_id","int");
        $channel_id = Request::get("channel_id","int");
        $size = Request::get("size","int");
        $page = Request::get("page","int");
        $sort = Request::get("sort", "int", 0);


        list($admins,$sum) = CategoryData::getCategoryAdminWithLiveAndGps($category_id, $channel_id, $page, $size);

        $result = [
            "list" => $admins,
            "count" => count($admins),
            "pages" => ceil($sum/$size),
            "size" => $size,
            "page" => $page,
            "channel_id" => $channel_id,
            "sort" => $sort
        ];
        $this->_json($result);
    }
}