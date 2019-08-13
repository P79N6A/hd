<?php



class MediaController extends InteractionBaseController {

	
    public function initialize() {
        parent::initialize();
		header('Cache-Control: max-age=1');
	}

    /**
     * 获取栏目(专题栏目)接口
     */
    public function latestInIdsAction() {
    	$categoryId = Request::getQuery('category_id', 'string');
    	$specialCategoryId = Request::getQuery('special_category_id', 'string');
    	$channelId = Request::getQuery('channel_id', 'string');
    	$domainId = Request::getQuery('domain_id', 'int', 0);
    	if($channelId != '') {
	    	SmartyData::init($channelId, $domainId);
	    	if($specialCategoryId == null && $categoryId != '') {
	    		$categoryId = str_replace('，', ',',$categoryId);
	    		$categoryIds = explode(',',$categoryId);
	    		$categoryIdArr = $this->checkIsNums($categoryIds);
	    		if(count($categoryIdArr) > 0) {
	    			$this->findCategoryDataInIds($categoryIdArr);
	    		}else {
	    			$this->returnJson("null");
	    		}
	    	}else if($categoryId == null && $specialCategoryId != '') {
	    		$specialCategoryId = str_replace('，', ',',$specialCategoryId);
	    		$specialCategoryIds = explode(',',$specialCategoryId);
	    		$specialCategoryIdArr = $this->checkIsNums($specialCategoryIds);
	    		if(count($specialCategoryIdArr) > 0) {
	    			$this->findSpecialCategoryDataInIds($specialCategoryIdArr);
	    		}else {
	    			$this->returnJson("null");
	    		}
	    	}else {
	    		$this->returnJson("null");
	    	}
    	}else {
    		$this->returnJson("null");
    	}
    }
    
    /**
     * 获取栏目(专题栏目)接口
     */
    public function latestAction() {
    	
    	$categoryId = Request::getQuery('category_id', 'string');
    	$specialCategoryId = Request::getQuery('special_category_id', 'string');
    	$channelId = Request::getQuery('channel_id', 'string');
    	$domainId = Request::getQuery('domain_id', 'int', 0);
    	if($channelId != '' && $this->isNum($channelId)) {
	    	SmartyData::init((int)$channelId, $domainId);
	    	if($specialCategoryId == null && $categoryId != '' && $this->isNum($categoryId) && (int)$categoryId >= 0) {
	    		$this->findCategoryData($categoryId);
	    	}else if($categoryId == null && $specialCategoryId != '' && $this->isNum($specialCategoryId) && (int)$specialCategoryId >= 0) {
	    		$this->findSpecialCategoryData($specialCategoryId);
	    	}else{
	    		$this->returnJson("null");	
	    	}
    	}else {
    		$this->returnJson("null");
    	}
    }


    /**
     * 获取栏目(专题栏目)接口
     */
    public function latestDetailAction() {

        $categoryId = Request::getQuery('category_id', 'string');

        $channelId = Request::getQuery('channel_id', 'string');
        $domainId = Request::getQuery('domain_id', 'int', 0);
        if($channelId != '' && $this->isNum($channelId)) {
            SmartyData::init((int)$channelId, $domainId);
            $this->findCategoryData($categoryId,true);
        }

    }


    private function checkIsNums($ids) {
    	$arrId = array();
    	if(is_array($ids) && !empty($ids)) {
    		foreach ($ids as $id) {
    			if($this->isNum($id)) {
    				array_push($arrId, $id);
    			}
    		}
    	}
    	return $arrId;
    }
    
    private function isNum($id) {
    	if (preg_match("/^[0-9]*$/i", $id)) {
    		return true;
    	}
    	else {
    		return false;
    	}
    }
    
    /**
     * 获取媒资详情    新闻  接口
     */
    public function newsAction() {
    	$this->findData("news");
    }
    
    /**
     * 获取媒资详情    视频  接口
     */
    public function videoAction() {
    	$this->findData("video");
    }
    
    /**
     * 获取媒资详情    相册  接口
     */
    public function albumAction() {
    	$this->findData("album");
    }
    
    /**
     * 获取媒资详情    专题  接口
     */
    public function specialAction() {
    	$this->findData("special");
    }
    
    /**
     * 媒资发布库
     */
    public function findCategoryData($id, $detailFlag = false) {
    	$sort = Request::getQuery('sort', 'int', 0);
    	$size = 0;
    	$page = null;
    	$this->returnPageAndSize($size, $page);
    	$author_id = Request::getQuery("author_id");
    	$data = array();
    	if($sort > 0) {
    		$data = SmartyData::getLatestWithSort($id, $size, $page,$author_id);
    	}else {
    		$data = SmartyData::getLatest($id, $size, $page,$author_id);
    	}
    	$this->returnLatestData($data, $size, $page, $sort, $detailFlag);
    }
    
    /**
     * 媒资发布库,多个id
     */
    public function findCategoryDataInIds($ids) {
    	$sort = Request::getQuery('sort', 'int', 0);
    	$size = 0;
    	$page = null;
    	$this->returnPageAndSize($size, $page);
    	$data = array();
    	if($sort > 0) {
    		$data = SmartyData::getLatestWithSortInIds($ids, $size, $page);
    	}else {
    		$data = SmartyData::getLatestInIds($ids, $size, $page);
    	}
    	$this->returnLatestData($data, $size, $page, $sort);
    }
    
    /**
     * 专题媒资发布库
     */
    public function findSpecialCategoryData($id) {
    	$sort = Request::getQuery('sort', 'int', 0);
    	$size = 0;
    	$page = null;
    	$this->returnPageAndSize($size, $page);
    	$data = array();
    	if($sort > 0) {
    		$data = SmartyData::getSpecialDataWithSortById($id, $size, $page);
    	}else {
    		$data = SmartyData::getSpecialDataById($id, $size, $page);
    	}
    	$this->returnLatestData($data, $size, $page, $sort);
    }
    
    /**
     * 专题媒资发布库,多个id
     */
    public function findSpecialCategoryDataInIds($ids) {
    	$sort = Request::getQuery('sort', 'int', 0);
    	$size = 0;
    	$page = null;
    	$this->returnPageAndSize($size, $page);
    	$data = array();
    	if($sort > 0) {
    		$data = SmartyData::getSpecialDataWithSortByIds($ids, $size, $page);
    	}else {
    		$data = SmartyData::getSpecialDataByIds($ids, $size, $page);
    	}
    	$this->returnLatestData($data, $size, $page, $sort);
    }
    
    /**
     * 获取数据（公用方法） terminalType: 'web','app','wap','wechat'
     */
    public function findData($type) {
    	$data = array();
    	$dataId  = Request::getQuery('data_id', 'string');
    	$terminalType = Request::getQuery('terminal', 'string');
    	$channelId = Request::getQuery('channel_id', 'string');
    	$domainId = Request::getQuery('domain_id', 'int', 0);
    	$bStatus = $this->checkQuerys($dataId, $terminalType, $channelId);
    	if($bStatus){
	    	SmartyData::init($channelId, $domainId);
	    	if($this->checkTerminal($terminalType)) {
	        	switch ($type) {
		    		case "news":
		    			$data = SmartyData::getNews($dataId, $terminalType);
		    			break;
		    		case "video":
		    			$data = SmartyData::getVideo($dataId, $terminalType);
		    			break;
		    		case "album":
		    			$data = SmartyData::getAlbum($dataId, $terminalType);
		    			break;
		    		case "special":
		    			$data = SmartyData::getSpecial($dataId, $terminalType);
		    			break;
		    		default:break;
		    	}
	    	}
    	}
    	$this->returnJson($data);
    }

	/**
	 * 根据部门获取媒资
	 */
	public function getDataByDeptIdAction(){
		$dept_id = intval(Request::getQuery("dept_id", "int", 0));
		$channel_id = intval(Request::getQuery("channel_id", "int", 1));
		$page = intval(Request::getQuery("page", "int", 1));
		$page_size = intval(Request::getQuery("page_size", "int", 12));
		$data = SmartyData::getDataByDeptId($dept_id, $channel_id,$page,$page_size);
		$this->returnJson($data);
	}


	/**
	 * 根据推荐位获取媒资
	 */
	public function getFeatureAction(){
		$position = intval(Request::getQuery("position","int",0));
		$region_id = intval(Request::getQuery("region_id","int",0));
		$categoryId = Request::getQuery('category_id', 'string');
		$channelId = Request::getQuery('channel_id', 'int', 1);
		$domainId = Request::getQuery('domain_id', 'int', 0);
		$count = intval(Request::getQuery("count","int",10));

		if($channelId != '' && $this->isNum($channelId)) {
			SmartyData::init((int)$channelId, $domainId);
			if($categoryId != '' && $this->isNum($categoryId) && (int)$categoryId >= 0) {
				$data = SmartyData::getFeature($position, $categoryId, $region_id, $count);
				$this->returnJson($data);
			}else{
				$this->returnJson("null");
			}
		}else {
			$this->returnJson("null");
		}

	}
    
    private function checkQuerys($dataId, $terminalType, $channelId) {
    	$bResult = true;
    	$bResult = ($bResult && $dataId != '' && $this->isNum($dataId)) ? true : false;
    	$bResult = ($bResult && $terminalType != '') ? true : false; 
    	$bResult = ($bResult && $channelId != '' && $this->isNum($channelId)) ? true : false;  
    	return $bResult;
    }
    
    private function checkTerminal($terminalType) {
    	$terminals = array(
    		"web" => 0,
    		"app" => 1,
    		"wap" => 2,
    		"wechat" => 3,
    	);
     	return array_key_exists($terminalType, $terminals) ? true : false;
    }
    
    /**
     * 返回 总记录， 总页数， 请求行数， 请求页数
     * @param unknown $data
     * @param unknown $size
     * @param unknown $page
     * @para bool $detailFlag
     */
    private function returnLatestData($data, $size, $page, $sort, $detailFlag = false) {
    	$pages = ceil($data["count"] / $size);
    	$resData = array(
    			"list" => $data["models"],		// 数据
    			"count" => $data["count"],			// 总记录
    			"pages" => $pages,					// 总页数
    			"size"  => $size,					// 一页行数
    			"page"  => $page,					// 当前页
    			"channel_id" => $this->channel_id,  // 频道号
    			"sort"  => $sort,					// 是否排序，0：不排序， 1：排序
    	);
    	if($detailFlag){
    	    //todo::获取详情
            $this->getDetail($resData,$data);
        }
    	$this->returnJson($resData);
    }
    
    /**
     * 获取 行数和页号
     * @param unknown $size
     * @param unknown $page
     */
    private function returnPageAndSize(&$size, &$page) {
    	$getSize = Request::getQuery('size', 'string');
    	if($getSize != '' && $this->isNum($getSize)) {
    		$size = (int)$getSize;
    		if($size > 1000) {
    			$size = 1000;
    		}
    	}
    	$page = Request::getQuery('page', 'string');
    	if($page != '' && $this->isNum($page)) {
    		$page =(int)$page;
    	}else {
    		$page = 1;
    	}
    }
    
    /**
     * 返回json格式
     * @param unknown $values
     */
    private function returnJson($values) {
    	
    	if(isset($values) && !empty($values)) {
    		if($values == "null") {
    			$this->jsonp(array('code' => 404, 'msg' => "params error", 'data' => 'Not Found'));
    		}
    		else {
    			$this->jsonp(array('code' => 200, 'msg' => "success", 'data' => $values));
    		}
    	}
    	else if(empty($values)) {
    		$this->jsonp(array('code' => 200, 'msg' => "success", 'data' => ''));
    	}
    }

    /**获取详情
     * @function getDetail
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $resData
     * @param $data
     */
    private function getDetail(&$resData,&$data){
        $detailData = [];

        foreach ($data["models"] as  $model){
            $tmpData = $model;
            $tmpData["detail"] = [];
            $tmpData["thumb"] = cdn_url("image",$tmpData["thumb"]);
            // 直播或点播
            $playUrls = Data::getSignalPlayUrl($model["id"]);
            if(strpos($model["redirect_url"],"m3u8") !== false){ //点播
                $tmpData["detail"]["video"]=[
                    "m3u8"=>  $model["redirect_url"]
                ];

            }else if (count($playUrls) > 0 ){ //直播
                $tmpData["detail"]["live"]=[];
                foreach ($playUrls as $urls){
                    if(strpos($urls["play_url"],"m3u8") !== false){
                        $tmpData["detail"]["live"]["m3u8"] = $urls["play_url"];
                    }else if (strpos($urls["play_url"],"rtmp") !== false){
                        $tmpData["detail"]["live"]["rtmp"] = $urls["play_url"];
                    }
                }

            }else{ //图集
                $tmpData["detail"]["image"] = Data::getAlbumImageFiles($model["id"]);
            }
            $detailData[] = $tmpData;
        }

        $resData["list"] = $detailData;
    }

}