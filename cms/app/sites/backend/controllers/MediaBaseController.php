<?php

class MediaBaseController extends \BackendBaseController {

    protected $urlName = '';
    protected $type = '';
    protected function preProcessData($data) {
        $processes = [
            'title' => ['strip_tags'],
            'sub_title' => ['strip_tags'],
            'keywords' => ['strip_tags'],
            'intro' => ['strip_tags'],
            'referer_id' => ['intval'],
        ];
        foreach($processes as $key => $ps) {
            if(isset($data[$key])) {
                foreach($ps as $p) {
                    $data[$key] = call_user_func_array($p, [$data[$key]]);
                }
            }
        }
        if($data["detail_lnglat"] != ""){
            list($data["longitude"],$data["latitude"]) = explode(",",$data["detail_lnglat"]);
        }
        return $data;
    }

    /**
     * @throws \Phalcon\Mvc\Model\Exception
     */
    public function indexAction() {
        $private_category_id = Request::getQuery("private_category_id", "int");
        if(isset($private_category_id)&&$private_category_id) {
            $parcel = PrivateCategoryData::findAll();
        }
        else {
        $parcel = Data::channelQuery(Auth::user()->channel_id, 'Data')
            ->columns(['Data.*', 'PrivateCategory.*'])
            ->andCondition('type', $this->type)
            ->leftJoin("PrivateCategoryData", "Data.id = PrivateCategoryData.data_id")
            ->leftJoin("PrivateCategory", "PrivateCategory.id = PrivateCategoryData.category_id")
            ->orderBy('created_at desc')
            ->paginate(50, 'Pagination');
        }
        View::setVars(compact('parcel'));
    }

    /**
     * @return bool
     */
    protected function denySystemAdmin() {
        $r = false;
        $is_admin = Auth::user()->channel_id;
        if($is_admin == "0") {
            $r = true;
            $this->alert('系统管理员, 请勿直接新增/编辑媒资数据.');
        }
        return $r;
    }

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
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/thumbnails');
                } else {
                    $messages[] = Lang::_('please upload valid index image');
                }
            } elseif($error == 4) {
                $path = Request::getPost('thumb', null, '');
                if(!$path) {
                    $messages[] = Lang::_('please choose upload index image');
                }
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload poster image');
        }
        return $path;
    }
    public function searchAction(){
        $user = Session::get('user')->toarray();
        if($data = Request::getQuery()){
            $parcel = Data::search($data,$user['channel_id']);
            View::pick($this->urlName.'/index');
            View::setVars(compact('parcel','data'));
        }
    }
    
    
    /**
     * 初始化加载地区数据
     * @return unknown
     */
    protected  function initRegionData(){
    	$channel_id = Session::get('user')->channel_id;
    	 
    	// 初始化，根据频道获取地区id
    	$fetch_id = RegionDefault::fetchByChannelId($channel_id);
    	// 防止channel表中region_id与regions表id不符报错
    	if($fetch_id['region_id'] < intval(Regions::queIdByLevel())){
    		$fetch_id['region_id'] = intval(Regions::queIdByLevel());
    	}
    	$region = Regions::fetchById($fetch_id['region_id']);			// 获取id地区内容
    	
    	//生成上级地区
	    $parents=$region->getParents();
	    unset($parents[count($parents)-1]);
	    foreach($parents as $p){
	    	$level_id=($p->level).'_id';
	    	$region->$level_id=$p->id;
	    }
	    $regionData = $region;
    	
    	
    	return $regionData;
    }
    
    /**
     * 修改页面，初始化加载部门数据
     * @param unknown $data_id	媒资Id
     * @return unknown	部门数据
     */
    protected  function  initDepartmentData($data_id){
    	$government_id = null;
    	$governmentData = GovernmentDepartmentData::fetchGovernmentDepartmentId($data_id);
    	if(isset($governmentData)){
    		foreach ($governmentData as $v){
    			$government_id = $v['government_department_id'];
    		}
    	}
    	$government = GovernmentDepartment::fetchById($government_id);
    	return $government;
    }

    /*
     * @desc 初始化数据
     * */
    protected function initPublishPageData($data_id = 0) {
        $category_id = Request::getQuery('category_id', 'int');
        $spec_publish = Request::getQuery('spec_publish', 'int', 0);
        $spec_category_id = Request::getQuery('spec_category_id', 'int', 0);
        $category = '';
        if($category_id) {
            $category = Category::findById($category_id);
        }
        $datas = [];
        $token = md5(uniqid(str_random()));
        $editAction = false;
        $model_cal = null;
        $cateogrywrited = null;
        $author = Session::get("user")->name;
        $region = "";
        if($category_id) {
            $cateogrywrited = $this->formatcateogry([$category_id]);
        }
        if($data_id) {
            $model_cal = DataStatistics::query()->where("data_id={$data_id}")->first();
            $data = Data::findFirstOrFail($data_id);
            $category_ids = CategoryData::getIdByData($data_id);

            $region = RegionData::findRegionData($data_id);
            
            $cateogrywrited = $this->formatcateogry($category_ids);
            $datas = Data::queryByIds(Auth::user()->channel_id, json_decode($data->data_data,true));
            $editAction = true;
            $author = $data->author_name;
        }
        $government = GovernmentDepartment::findList(Auth::user()->channel_id);
        $data_id = $data_id?$data_id:0;
        return compact("datas", "token", "model_cal", "government", "data_id", "spec_category_id", "spec_publish", "category",
            "category_id","cateogrywrited","editAction",'author','feature_id','feature_cat_id','region');
    }


    private function formatcateogry($category_ids)
    {
        $cateogrywrited = [];
        foreach($category_ids  as $key =>$id)
        {
            $_cateogrywrited = Category::findFirstOrFail($id)->toarray();
            $cateogrywrited[$key] = ['id'=>$_cateogrywrited['id'],'text'=>$_cateogrywrited['name'],'terminal'=>$_cateogrywrited['terminal']];
        }
        return json_encode($cateogrywrited);
    }

    
}