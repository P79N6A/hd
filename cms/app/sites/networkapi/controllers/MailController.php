<?php
/**
 * @RoutePrefix("/mail")
 */
class MailController extends ApiBaseController {
	
	/**
	 * @Post("/send")
	 */
	public function sendReportMailAction() {		
		$uid = !empty(Request::getPost('uid')) ? Request::getPost('uid') : "";											// 用户id
		$mobile = !empty(Request::getPost('mobile')) ? Request::getPost('mobile') : "";									// 手机号码
		$reportType = !empty(Request::getPost('type')) ? Request::getPost('type') : "";									// 举报类型
		$reportUserName = !empty(Request::getPost('username')) ? Request::getPost('username') : "";						// 举报用户名
		$reportContent = !empty(Request::getPost('content')) ? Request::getPost('content') : "";						// 举报内容
		$reportDateTime = !empty(Request::getPost('datetime')) ? Request::getPost('datetime') : date("Y-n-d H:i");		// 发送时间
		$reportTitle = !empty(Request::getPost('title')) ? Request::getPost('title') : "蓝魅直播举报邮件";					// 邮件标题
		$emailAddr = !empty(Request::getPost('toemail')) ? Request::getPost('toemail') : 'iblue2015@126.com ';      	// 发送至邮件地址
		$emailTemplate = !empty(Request::getPost('template')) ? Request::getPost('template') : 'sendReportEmail';		// 邮件模板
		
		// 举报者
		$userName = '<br/> 用户名: '. $reportUserName 
				   .'<br/> 手机号: '. $mobile;
				   
		// 邮件正文
		$reportText = '<br/> 举报类型: '. $reportType
					 .'<br/> 具体内容: '. $reportContent;
		// 发送内容
		$vars = json_encode(array("to" => array($emailAddr), "sub" => array("%username%" => Array($userName), 
				"%reporttime%" => Array($reportDateTime),"%title%" => Array($reportTitle), "%content%" => Array($reportText))));
		
		// 发送
		$mail = new Mail();
		$result = $mail->sendCloudEmail($emailTemplate, $vars,'蓝魅-举报邮件');
		
		$this->_json($result);
		exit;
	}
}

?>