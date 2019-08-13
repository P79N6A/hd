<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 2015/9/24
 * Time: 14:50
 */

Class WxbirthdaysupportController extends \BackendBaseController{

    public function indexAction(){
        $birthday = WxBirthdaySupport::query()->paginate(50, 'Pagination');
        View::setVars(compact('birthday'));
    }

}