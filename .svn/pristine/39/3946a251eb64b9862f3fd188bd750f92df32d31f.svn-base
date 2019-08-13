<?php

/**
 * 聚合媒资管理
 *
 * @author     Zhang haiquan
 * @created    2015-11-10
 */
class MultimediaController extends MediaBaseController {

    protected $urlName = 'multimedia';

    protected $type = 'multimedia';

    public function indexAction() {
        $private_category_id = Request::getQuery("private_category_id", "int");
        if(isset($private_category_id)&&$private_category_id) {
            $parcel = PrivateCategoryData::findAll();
        }
        else {
            $parcel = Data::channelQuery(Auth::user()->channel_id, 'Data')
                ->columns(['Data.*', 'PrivateCategory.*'])
                ->leftJoin("PrivateCategoryData", "Data.id = PrivateCategoryData.data_id")
                ->leftJoin("PrivateCategory", "PrivateCategory.id = PrivateCategoryData.category_id")
                ->andWhere("Data.type= '{$this->type}'")
                ->andWhere("Data.status <> 3")
                ->orderBy('created_at desc')
                ->paginate(50, 'Pagination');
        }
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
    

    public function recycleAction() {
        $data_id = Request::getQuery("data_id", "int");
        $modifyRes = false;
        $rs = false;
        $data =  CategoryData::getRedisCategoryName($data_id);

        if(isset($data) && !empty($data)) {
            foreach ($data as $v) {
                $category_id = $v['category_id'];
                CategoryData::deleteRedisCache($category_id, $data_id);
                $categoryData = new CategoryData();
                $categoryData->updatePublishStatus($v['id'], Data::STATE_RECYCLE);
            }
            $d = new Data();
            $modifyRes = $d->modifyStatus($data_id, Data::STATE_RECYCLE);

        }
        if($modifyRes) {
            $dataJson = json_encode($data);
            $saveData = array(
                "channel_id" => Auth::user()->channel_id,
                "data_id" => $data_id,
                "publish_info" => $dataJson,
                "user_id" => Auth::user()->id,
                "created_at" => time(),
                'partition_by' => date("Y")
            );
            $recycle = new Recycle();
            $rs = $recycle->createRecycleData($saveData);
        }
        if ($rs) {
            $arr = array('code' => 200);
        } else {
            $arr = array('msg' => Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

}