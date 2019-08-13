<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class PushMsg extends Model {

	const PAGE_SIZE = 50;
	const PUSH_DONE = 1;
	const PUSH_FILED = 3;
	const PUSH_BYTIME = 4;
	
	const ANDRIOD = 1;
	const IOS = 2;
	
    public function getSource() {
        return 'push_msg';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'push_content', 'push_mode', 'push_id', 'ac_code', 'push_url', 'push_image', 'push_channelid', 'push_ablumid', 'push_terminal', 'push_single', 'push_single_client', 'push_type', 'push_timestamp', 'created_at', 'status', 'remark', 'admin_id', 'share_url', 'video_date',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['push_content', 'push_mode', 'push_id', 'ac_code', 'push_url', 'push_image', 'push_channelid', 'push_ablumid', 'push_terminal', 'push_single', 'push_single_client', 'push_type', 'push_timestamp', 'created_at', 'status', 'remark', 'admin_id', 'share_url', 'video_date',],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'push_content' => Column::TYPE_VARCHAR,
                'push_mode' => Column::TYPE_INTEGER,
                'push_id' => Column::TYPE_VARCHAR,
                'ac_code' => Column::TYPE_VARCHAR,
                'push_url' => Column::TYPE_VARCHAR,
                'push_image' => Column::TYPE_VARCHAR,
                'push_channelid' => Column::TYPE_INTEGER,
                'push_ablumid' => Column::TYPE_INTEGER,
                'push_terminal' => Column::TYPE_INTEGER,
                'push_single' => Column::TYPE_INTEGER,
                'push_single_client' => Column::TYPE_VARCHAR,
                'push_type' => Column::TYPE_INTEGER,
                'push_timestamp' => Column::TYPE_VARCHAR,
                'created_at' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
                'remark' => Column::TYPE_TEXT,
                'admin_id' => Column::TYPE_INTEGER,
                'share_url' => Column::TYPE_VARCHAR,
                'video_date' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'push_mode', 'push_channelid', 'push_ablumid', 'push_terminal', 'push_single', 'push_type', 'status', 'admin_id', 'video_date',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'push_content' => Column::BIND_PARAM_STR,
                'push_mode' => Column::BIND_PARAM_INT,
                'push_id' => Column::BIND_PARAM_STR,
                'ac_code' => Column::BIND_PARAM_STR,
                'push_url' => Column::BIND_PARAM_STR,
                'push_image' => Column::BIND_PARAM_STR,
                'push_channelid' => Column::BIND_PARAM_INT,
                'push_ablumid' => Column::BIND_PARAM_INT,
                'push_terminal' => Column::BIND_PARAM_INT,
                'push_single' => Column::BIND_PARAM_INT,
                'push_single_client' => Column::BIND_PARAM_STR,
                'push_type' => Column::BIND_PARAM_INT,
                'push_timestamp' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'remark' => Column::BIND_PARAM_STR,
                'admin_id' => Column::BIND_PARAM_INT,
                'share_url' => Column::BIND_PARAM_STR,
                'video_date' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'push_terminal' => '0',
                'push_single' => '0',
                'push_single_client' => '0',
                'push_type' => '1',
                'status' => '0',
                'admin_id' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    
    /**
     * 判断空值
     * @param unknown $input
     */
    public static function makeValidator($input) {
    	return Validator::make(
    			$input,
    			[
    			'push_content' => 'required|max:1024',
    			'push_terminal_temp' => 'required',
    			
    			'push_mode' => 'required',
    			],
    			[
    			'push_content.required' => '内容必填',
     			'push_content.max' => '内容最长1024字符',
    			'push_id.max' => '内容最长50字符',
    			'push_terminal_temp.required' => '请选择发送至的客户端类型',
    		
    			'push_mode.required' => '推送类型必选',
    			]
    	);
    }

    /**
     * 查询所有数据
     * @return unknown
     */
	public static function findAll() {
		$result = self::query()
			->columns(array('PushMsg.*'))
			->orderBy('PushMsg.created_at desc')
			->paginate(self::PAGE_SIZE, 'Pagination');
		return $result;
	}

	/**
	 * 获取一条数据
	 * @param unknown $id
	 * @return \Phalcon\Mvc\ModelInterface
	 */
 	public static function getOne($id) {
        return self::query()
            ->andCondition('id', $id)
            ->first();
    }

    /**
     * 新增数据
     * @param unknown $data
     */
    public function createPushMsg($data,$pushResult) {
    	// 判断推送状态
    	if(isset($pushResult) && !empty($pushResult)) {
		    $remark = "";
		    foreach ($pushResult as $value) {
		    	foreach ($value as $v) {
		    		$v = stripslashes($v);
		    		if(strpos($v,"result")) {
		    		$resultValue = json_decode($v);
		    		$remark .= json_encode($v);
		    		if($resultValue != null) {
						if($resultValue->result != "ok"){
							$data['status'] = PushMsg::PUSH_FILED;
						}else{
							$data['status'] = PushMsg::PUSH_DONE;
							}
		    			}
		    		}
		    	}
		    }
		    $remark = stripslashes($remark);
		    $data['remark'] = $remark;
	    }
    	$this->assign($data);
        return $this->save();
    }
   
    /**
     * 搜索数据
     * @param unknown $data
     */
    public static function searchPushMsg($data) {
    	$push_content = $data['pushTitle'];
    	$push_terminal = $data['pushTerminal'] ?: '';
    	$push_mode = $data['pushMode'] ?: '';
    	$status = $data['status'] ? (int)$data['status']-1 : '-1';
    	
    	$query = self::query();
    	if ($push_content) {
    		$query = $query->orWhere("PushMsg.push_content like '%$push_content%'");
    	}
    	
    	if ($push_mode) {
    		$query = $query->orWhere("PushMsg.push_mode = '{$push_mode}'");
    	}
    	
    	if($push_terminal) {
    		$query = $query->orWhere("PushMsg.push_terminal = '{$push_terminal}'");
    	}
    	
    	if($status >= 0) {
    		$query = $query->orWhere("PushMsg.status = '{$status}'");
    	}
    	
    	return $query->paginate(self::PAGE_SIZE, 'Pagination');
    }
  
    /**
     * 查询定时或及时数据
     * @param unknown $timeTemp
     * @param unknown $pushType
     * @return unknown
     */
    public static function findMsgByType($timeTemp,$pushType,$status) {
   	    $query = self::query()
   	    		->andWhere("PushMsg.push_type = '{$pushType}'");
   	    if($timeTemp > 0) {
   	    	$query = $query->andWhere("PushMsg.push_timestamp <= '{$timeTemp}'");
   	    }
   	    if($status) {
   	    	$query = $query->andWhere("PushMsg.status = '{$status}'");
   	    }
   	    $query = $query->execute()->toArray();
   	    
   	    return $query;
    }

    /**
     * 提给客户端显示
     * @return unknown
     */
    public static function apiFindAll($page, $per_page) {
    	return self::find(array(
    			'columns' => "id,push_content,push_mode,push_image,push_timestamp",
    			'limit' => $per_page,
    			'order' => "push_timestamp desc",
    			'offset' => ($page - 1) * $per_page
    	));
    }
}
