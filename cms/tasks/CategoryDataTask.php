<?php
/**
 * @class:   CategoryDataTask
 * @author:  汤荷
 * @version: 1.0
 * @date:    2016/12/2
 */

class CategoryDataTask extends Task{
    //把data 中的时间同步到CategoryData 的publist_at
    public function synctimeAction(){

        (new CategoryData())->syncTimeFromData();
    }

}