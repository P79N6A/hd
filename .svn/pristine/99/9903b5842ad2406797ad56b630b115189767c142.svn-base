<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/9/4
 * Time: 13:47
 */



class SpecialColumnController extends InteractionBaseController{
    public function infoAction(){
        $params = Request::get();
        $spec_cat_id = Request::get("id",'int',0);
        $page   = isset($params['page'])?$params['page']:1;
        $pagesize = isset($params['pagesize'])?$params['pagesize']:10;
        $sort = isset($params['sort'])?$params['sort']:'sort';
        if($sort!='sort')
            $sort = 'DataStatistics.'.$sort;
        $direct = Request::has('direct')?($params['direct']=='ASC'?$params['direct']:'DESC'):'ASC';
        if($spec_cat_id==0)
        {
            $this->_json([],401,$msg='参数ID缺少');
        }
        if(!$this->sortParam($sort)){
            $this->_json([],402,$msg='排序字段不正确');
        }
        if(!SpecialCategory::findFirst($spec_cat_id)){
            $this->_json([],403,$msg='专题栏目不存在');
        }
        $total = SpecialCategoryData::columnCount($spec_cat_id);
        if($total<=0){
            $this->_json([],404,$msg='没有找到栏目条目');
        }

        $page = $page>ceil($total/$pagesize)?ceil($total/$pagesize):$page;
        $order = "$sort $direct";
        $offset = ($page-1)*$pagesize;
        $result = SpecialCategoryData::listInfo($spec_cat_id,$order,$offset,$pagesize);
        $this->_json($result);
    }


    protected function _json($data, $code = 200, $msg = "success")
    {
        header('Content-type: application/json');
        $resp = json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        if($callback = Request::get('callback')){
            echo htmlspecialchars($callback) . "({$resp});";
        }else{
            echo $resp;
        }
        exit;
    }
    private function sortParam($sort){
        return preg_match("/^sort|(likes|hits|shares|comments)(_fake)?$/",$sort,$match)>0?true:false;
    }
}