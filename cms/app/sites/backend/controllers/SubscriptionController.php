<?php
/**
 *  电视广播台管理
 *  controller stations
 *  @author     Zhangyichi
 *  @created    2015-9-16
 *
 *  @param id,is_system,channel_id,code,name,type,logo,channel_name,customer_name,epg_path
 */


class SubscriptionController extends \BackendBaseController {
    
    public function indexAction() {
        $data = SubscriptionSet::findAll();
        View::setVars(compact('data'));
    }

    public function detailsAction() {
        $set_id = Request::get('set_id');
        $data = Subscription::findBySetid($set_id);
        View::setVars(compact('data'));
    }

    public function modifyAction() {
        $messages = [];
        if (Request::isPost()) {
            $inputs=Request::getPost();

            if(isset($inputs['id'])&&isset($inputs['set_id'])) {
                $return = SubscriptionSetInfo::modifySubscription($inputs['set_id'],$inputs);
                if ($return){
                    $messages[] = Lang::_('成功');
                }else{
                    $messages[] = Lang::_('权重修改失败');
                }
            }
        }

        $set_id = Request::getQuery('set_id');
        $data = SubscriptionSetInfo::findOneBySetId($set_id);

        if (!$data) {
            abort(404 , '专辑信息不存在');
        }

        View::setMainView('layouts/add');
        View::setVars(compact('messages','data'));
    }

    public function setkeywordAction() {
        $set_id=$this->request->getQuery("set_id","int");
        $return=SubscriptionSetInfo::modifyKeyword($set_id);
        if($return){
            //添加缓存删除的地方
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function searchAction() {
        $data = array();
        if(true == ($keyword = Request::getPost('keyword'))){
            $data = SubscriptionSet::searchByName($keyword);
            View::pick('subscription/index');
            View::setVars(compact('keyword','data'));
        }
    }

    public function getsetAction() {

        if (RedisIO::get(SubscriptionSetInfo::$get_status_key)===false){
            RedisIO::set(SubscriptionSetInfo::$get_status_key , 0);
        }
        $status = RedisIO::get(SubscriptionSetInfo::$get_status_key);
        if ($status == 0){
            RedisIO::set(SubscriptionSetInfo::$get_status_key , 1);
            $arr=array('code'=>200);
        }elseif ($status == 1 || $status == 2){
            $arr=array('code'=>203,'msg'=>'正在同步中');
        }

        echo json_encode($arr);
        exit;
    }

    /**
     * 热词排行榜的保存和修改，暂时只保存缓存
     * @auth zhangyichi
     */
    public function editkeywordAction() {
        $messages = [];
        if (Request::isPost()) {
            $inputs=Request::getPost();

            $keyword_arr = array();
            $keyword_index = array();
            $keyword_arr_before = array();
            for ($i=1 ; $i<=10 ; $i++){
                if (!$inputs['keyword'.$i]){
                    continue;
                }
                $inputs['sort'.$i] = ((int)$inputs['sort'.$i])?:1;
                $keyword_index[$i] = $inputs['sort'.$i];
                $keyword_arr_before[$i] = array(
                    'name' => $inputs['keyword'.$i],
                    'sort' => $inputs['sort'.$i],
                    'set_id' => $inputs['relationship_id'.$i],
                );
            }
            arsort($keyword_index);
            foreach ($keyword_index as $index => $sort){
                $keyword_arr[] = $keyword_arr_before[$index];
            }

            $data = $keyword_arr;
            $keyword_arr = json_encode($keyword_arr);
            $return = RedisIO::set(SubscriptionSetInfo::$get_keywork_key , $keyword_arr);
            if ($return){
                $messages[] = Lang::_('成功');
            }else{
                $messages[] = Lang::_('权重修改失败');
            }

        }else{
            $keyword_json = RedisIO::get(SubscriptionSetInfo::$get_keywork_key);
            $data = json_decode($keyword_json,true);
        }

        View::setMainView('layouts/add');
        View::setVars(compact('messages','data'));
    }

    //文件上传的方法
    /**
     * @param $messages
     * @return string
     */
    protected function validateAndUpload(&$messages) {
        $path = '';
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/logos');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif($error == 4) {
                $path = Request::getPost('oldlogo');
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }

    protected function deleteMemcache($site_id, $type){
        $key = D::memKey('apiGetStationsByType', ['site_id' => $site_id, 'type' => $type]);
        MemcacheIO::delete($key);
    }
}