<?php
/**
 *  工资薪金邮件通知
 *  controller Channel
 *  @author     haiquan Zhang
 *  @created    2015-11-3
 *
 */
class SalaryBillController extends \BackendBaseController {
	const EMAIL_TITLE_INDEX = 2;

	public function indexAction() {
		$salary_bill_list_key = "salary_bill_list".date("Yn");
		$salary_bill_list = Setting::getByChannel(Session::get("user")->channel_id, $salary_bill_list_key);

		View::setVars(compact('salary_bill_list'));

	}
	public function emaillistAction() {
		$salary_bill_email = Setting::getByChannel(Session::get("user")->channel_id, 'salary_bill_email');
		View::setVars(compact('salary_bill_email'));
	}

	public function emailimportAction() {
		if (Request::isPost()) {
			ini_set("memory_limit","500M");
			$path=  "abc.xsl";
			move_uploaded_file($_FILES['file']['tmp_name'], "abc.xsl");
			$excelData = F::readExcel($path);
			$members = array();
			$department = "";
			$salary_bill_email = Setting::getByChannel(Session::get("user")->channel_id, 'salary_bill_email');
			$new_list = array();
			foreach($salary_bill_email as $key=>$v) {
				$new_list[$key] =$v;
			}
			foreach($excelData as $row) {
				if(intval($row[0])>0) {
					if(trim($row[1])&&$row[1]!=$department) $department = $row[1];
					$new_list[$row[0]] = array(
						'number'=>$row[0],
						'department'=>$department,
						'name'=>$row[2],
						'email'=>$row[3],
					);
				}
			}
			$salary_bill_email = Setting::findfirst(array('conditions' => "key = 'salary_bill_email' and channel_id=".Session::get("user")->channel_id));
			$salary_bill_email->value = json_encode($new_list);
			$salary_bill_email->save();
			echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
			exit;
		}
	}


public function salaryimportAction() {
		$salary_bill_list_key = "salary_bill_list".date("Yn");
		if (Request::isPost()) {
			ini_set("memory_limit", "500M");
			$path = "abc.xsl";
			move_uploaded_file($_FILES['file']['tmp_name'], "abc.xsl");
			$excelData = F::readExcel($path);
			$salary_bill_arr = array();
			foreach($excelData as $row) {
				if (intval($row[0]) > 0) {
					$row2 = array();
					$row2['status'] = false;
					foreach($row as $key=>$vvv) {
						if (preg_match("/\d{9}$/i", $vvv)) {
							$row2[$key] = round($vvv,2);
						}
						else {
						    $row2[$key] = $vvv;
						}
					}
					$salary_bill_arr[] = $row2;
				}
			}
			$salary_bill_setting = Setting::findfirst(array('conditions' => "key = '".$salary_bill_list_key."' and channel_id=".Session::get("user")->channel_id));
			if($salary_bill_setting) {
				$salary_bill_setting->value = json_encode($salary_bill_arr);
				$salary_bill_setting->save();
			}
			else {
				$salary_bill_setting  = new Setting();
				$salary_bill_setting->save(array(
					'channel_id' => Session::get("user")->channel_id,
					'name' => date("Yn")."工资单",
					'key' => $salary_bill_list_key,
					'value' => json_encode($salary_bill_arr),
				  ));
			}
			echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
			exit;
		}
	}

	public function deleteemailAction() {
		$number = (int)Request::get('id');
		$salary_bill_email = Setting::getByChannel(Session::get("user")->channel_id, 'salary_bill_email');
		$new_list = array();
		foreach($salary_bill_email as $key=>$v) {
			if($key==$number) continue;
			$new_list[$key] =$v;
		}
		$salary_bill_email = Setting::findfirst(array('conditions' => "key = 'salary_bill_email' and channel_id=".Session::get("user")->channel_id));
		$salary_bill_email->value = json_encode($new_list);
		$salary_bill_email->save();
		echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
		exit;
	}

	public function deleteemailsAction() {
		$numbers = Request::getPost('id');
		$salary_bill_email = Setting::getByChannel(Session::get("user")->channel_id, 'salary_bill_email');
		$new_list = array();
		foreach($salary_bill_email as $key=>$v) {
			if(in_array($key, $numbers)) continue;
			$new_list[$key] =$v;
		}
		$salary_bill_email = Setting::findfirst(array('conditions' => "key = 'salary_bill_email' and channel_id=".Session::get("user")->channel_id));
		$salary_bill_email->value = json_encode($new_list);
		$result = $salary_bill_email->save();
		// 返回结果
		if($result) {
			echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
		}
		else {
			echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
		}
		exit;
	}

	public function sendByTemplate($email, $title, $sendtime, $salarybilllist) {
		$url = 'http://sendcloud.sohu.com/webapi/mail.send_template.json';
		$vars = json_encode(array(
			"to" => array($email),
			"sub" => array(
				"%title%" => Array($title),
				"%sendtime%" => Array($sendtime),
				"%salarybilllist%" => Array($salarybilllist),
			  )
		  ));
		$API_USER = app_site()->email_sendcloud->email_api_user;
		$API_KEY = app_site()->email_sendcloud->email_api_key;
		$param = array(
			'api_user' => $API_USER, # 使用api_user和api_key进行验证
			'api_key' => $API_KEY,
			'from' => app_site()->email_sendcloud->email_from, # 发信人，用正确邮件地址替代
			'fromname' => '新蓝网',
			'substitution_vars' => $vars,
			'template_invoke_name' => 'sendSalaryBillEmail',//对应模板
			'resp_email_id' => 'true'
		);
		$data = http_build_query($param);
		$result_info = F::curlRequest($url, 'post', $data);
		$result = json_decode($result_info);
		return ($result->message=="success")?true:false;
	}

	public function sendSalaryAction() {
		$channel_id =  Session::get("user")->channel_id;
		$salary_bill_list_key = "salary_bill_list".date("Yn");
		$numbers = Request::getPost('id');
		$email_list = array();

		$salary_bill_list = Setting::getByChannel(Session::get("user")->channel_id, $salary_bill_list_key);

		foreach($salary_bill_list as $v) {
			if(in_array($v[0], $numbers)) {
				$email = $v[1];
				if(F::isemail($email)) {
				    $email_list[] =$v;
				}
			}
		}
		$salary_bill_status = array();
		foreach($email_list as $v) {
			$email = $v[1];
			$title =  preg_replace("/(\w+)/", "", $v[2]).date("Y年n月")."工资、";
			if(date("Y")==date("Y", time()-2592000)) {
				$title .=  date("n月", time()-2592000)."奖金";
			}
			else {
				$title .=  date("Y年n月", time()-2592000)."奖金";
			}
			$sendtime = date("Y年n月d日 H:i:s");
			$salarybilllist = "";
			foreach( $v as $key=>$column) {
				if (in_array($column, ['基本工资', '岗位津贴', '通讯补贴', '补发', '工资小计', '奖金', '当月奖罚', '应发合计', '公积金', '养老', '医疗', '门诊', '失业', '工会', '个税', '实发'])) {
					$salarybilllist .= "  ".$column.":".$v[$key+1]."<br />";
				}
			}
			$send_result = $this->sendByTemplate($email, $title, $sendtime, $salarybilllist);
			$salary_bill_status[$v[0]] = $send_result;
		}

		foreach($salary_bill_list as $key=>$v) {
			$salary_bill_list[$key]['status'] = isset($salary_bill_status[$v[0]])?$salary_bill_status[$v[0]]:false;
		}
		$salary_bill_setting = Setting::getByKey($salary_bill_list_key, $channel_id);
		if($salary_bill_setting) {
			$salary_bill_setting->value = json_encode($salary_bill_list);
			$salary_bill_setting->save();
		}
		exit;
	}


}
