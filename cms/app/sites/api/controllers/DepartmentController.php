<?php

/**
 * @RoutePrefix("/department")
 */
class DepartmentController extends ApiBaseController {
	
	static $public_actions = array('getbranchs');
	
	public function initialize()
	{
		parent::initialize();
	}
	
	/**
	 * @Get('/getbranchs')
	 */
	public function getbranchsAction()
	{
		$this->checkToken();
		$admin = $this->user;
		$channelId = $admin->channel_id;
		if($admin != null && isset($channelId)) {
			$values = Department::apiGetDepartment(-1,$channelId);
			$this->returnJson($values);
		}
		else{
			$this->_json(array("department"=>0), '404', 'Not Found');
		}
	}
	
	/**
	 * 返回json格式
	 * @param unknown $values
	 */
	private function returnJson($values) {
		if(isset($values) && !empty($values)) {
			$this->_json($values);
		}
		else {
			$this->_json(array("admin_id"=>$channelId), '404', 'Not Found');
		}
	}
}

?>