<?php


class SupplyController extends \BackendBaseController {

    /**
     * 索贝，观止等视频推送队列
     */
    public function sobeylistAction(){
        $parcel = Supplies::findAll();
        View::setVars(compact('parcel'));
    }

    /**
     * 分类对分类
     */
    public function categoryAction() {
        $parcel = SupplyToCategory::findAll();
        View::setVars(compact('parcel'));
    }

}