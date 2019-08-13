<?php

/**
 * 回收站管理
 *
 * @author     Zhang haiquan
 * @created    2015-11-10
 */
class RecycleController  extends MediaBaseController {

    protected $urlName = 'recycle';



    /**
     * @throws \Phalcon\Mvc\Model\Exception
     */
    public function indexAction() {
        $parcel = Recycle::channelQuery(Auth::user()->channel_id, 'Recycle')
            ->columns(['Recycle.*', 'Data.*'])
            ->leftJoin("Data", "Data.id = Recycle.data_id")
            ->orderBy('Recycle.created_at desc')
            ->paginate(50, 'Pagination');
        View::setVars(compact('parcel'));
    }


    //媒资编辑
    public function editAction() {
        if ($this->denySystemAdmin()) {
            return true;
        }
        $this->initFormView();
        $type = Request::getQuery("type", "string");
        $id = Request::getQuery("id", "int");
        $inint = $this->initPublishPageData($id);
        $channel_id = Auth::user()->channel_id;
        $channelId = $channel_id;
        $model = $this->getQuoteMedia($id, $channel_id, "Multimedia");
        //获取包含媒资
        $news = null;
        $signal = null;
        $special = null;
        if($model->type=="news") {
            $model = Data::findFirstOrFail($id);
            $news = News::channelQuery(Auth::user()->channel_id)->andCondition('id', $model->source_id)->first();
            $model->assignToMedia($news);
        }
        else if($model->data_data_ext) {
            foreach(json_decode($model->data_data_ext) as $type=>$items) {
                switch($type) {
                    case 'news':
                        foreach($items as $d) { $news = $this->getQuoteMedia($d->data_id, $channel_id, "News"); $news->data_id =$d->data_id; }
                        break;
                    case 'video':
                        foreach($items as $d) { $video = $this->getQuoteMedia($d->data_id, $channel_id, "Videos"); $video->data_id =$d->data_id; }
                        break;
                    case 'signal':
                        foreach($items as $d) { $signal = $this->getQuoteMedia($d->data_id, $channel_id, "Signals"); $signal->data_id =$d->data_id; }
                        $signalJson = Signals::getTVJsonByRedis($signal->id);
                        break;
                    case 'special':
                        foreach($items as $d) { $special = $this->getQuoteMedia($d->data_id, $channel_id, "Specials"); $special->data_id =$d->data_id; }
                        break;
                }
            }
        }
        $messages = Request::has("messages")?explode(",",Request::get("messages")):[];

        $secret_key = $model->secret_key;
        $secret_url = $model->secret_url;
        $template = $this->initTemplate();
        $channelData = Channel::findFirst($channelId);
        $channelAddress = $channelData->address;
        $editFlag = true;
        $inint = array_merge($inint, compact("model", "messages", "template", "news","secret_key","secret_url", "signalJson", "signal","channelAddress","editFlag"));
        View::setVars($inint);
    }

    public function getQuoteMedia($data_id, $channel_id, $modelname='Multimedia') {
        $key = Data::data_detail_key .":". $data_id;
        if(!RedisIO::exists($key)) {
            $r = Data::findFirstOrFail($data_id);
            if($modelname!='Multimedia') {
                $model = $modelname::channelQuery($channel_id)
                    ->andCondition('id', $r->source_id)
                    ->first();
                if(!$model) {
                    abort(404);
                }
                if($r->type=="news") {
                    $r->assignToMedia($model);
                }
                $result = json_encode($model);
            }
            else {
                $result = json_encode($r);
            }
            RedisIO::set($key, $result, 86400);
        }
        else {
            $result = RedisIO::get($key);
        }
        $model = json_decode($result);
        return $model;
    }

    protected function initTemplate() {
        return array(array('id' => 1, 'name' => '模板一'), array('id' => 2, 'name' => '模板'), array('id' => 3, 'name' => '模板三'));
    }

    /**
     * 恢复操作
     */
    public function recoveryAction() {
        $data_id = Request::getQuery("id", "int");
        $recoveryData = Recycle::getDataByDataId($data_id);
        $publish_info  = json_decode($recoveryData->publish_info, true);
        $rs = false;
        DB::begin();
        if(isset($publish_info) && !empty($publish_info)) {
            $rs = true;
            foreach ($publish_info as $v) {
                $id = $v["id"];
                $category_id = $v["category_id"];
                $publish_status = $v["publish_status"];
                // 恢复category_data表
                $categoryData = new CategoryData();
                $rs = $categoryData->updatePublishStatus($id, $publish_status);
                if (!$rs) {
                    DB::rollback();
                    break;
                }
                CategoryData::deleteRedisCache($category_id, $data_id);
            }
        }

        if($rs) {
            // 恢复data表
            $d = new Data();
            $rs = $d->modifyStatus($data_id, Data::STATE_APPROVE);
            if(!$rs){
                DB::rollback();
                $arr = array('msg' => Lang::_('failed'));
                echo json_encode($arr);
                exit;
            }
            $rs = Recycle::delRecycleData($data_id);
            DB::commit();
            $arr = array('code' => 200);
            echo json_encode($arr);
            exit;
        }
        else{
            DB::rollback();
            $arr = array('msg' => Lang::_('failed'));
            echo json_encode($arr);
            exit;
        }

    }

    /**
     * 删除操作
     */
    public function deleteAction() {
        $data_id = Request::getQuery("data_id", "int");
        $rs = Data::delMultimeAndNewsData($data_id);
        if ($rs) {
            CategoryData::delRedisCategoryName($data_id);
            $arr = array('code' => 200);
        } else {
            $arr = array('msg' => Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }
   
}