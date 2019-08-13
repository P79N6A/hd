<?php

/**
 * Created by PhpStorm.
 * User: cyc
 * Date: 2015/12/2
 * Time: 10:32
 */
class ActivityController extends \BackendBaseController
{
    public $id;
    public $picTable;

    public function initialize()
    {
        parent::initialize();
        $this->id = Request::getQuery('id');
        $this->picTable = "picTable:";

    }

    public function indexAction()
    {
        $channel_id = Session::get('user')->channel_id;
        $data = Activity::findAll($channel_id);
        View::setVars(compact('data'));
    }

    public function createAction()
    {
        $data = '';
        $messages = '';
        if ($data = Request::getPost()) {
            $data['channel_id'] = Session::get('user')->channel_id;
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']);
            if (Request::getUploadedFiles()[0]->getError() == 0) {
                if ($this->validateAndUpload($messages)) {
                    $data['thumb'] = $this->validateAndUpload($messages);
                }
            }
            $validator = Activity::makeValidator($data);
            if (!$validator->fails()) {
                $Activity = new Activity();
                if ($result = $Activity->createActivity($data)) {
                    $id = $Activity->id;
                    $data['author_name'] = Session::get('user')->name;
                    $data['author_id'] = Session::get('user')->id;
                    $data['partition_by'] = date('Y');
                    $data['created_at'] = time();
                    $data['updated_at'] = time();
                    $dModel = new Data();
                    if ($dModel->doSaveAc($data, Data::getAllowed(), 'activity', $id)) {
                        $messages[] = Lang::_('success');
                    }
                } else {
                    $messages[] = Lang::_('error');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }

        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function modifyAction()
    {
        $data = '';
        $messages = [];
        $channel_id = Session::get('user')->channel_id;
        if ($data = Request::getPost()) {
            //$data['channel_id'] = Session::get('user')->channel_id;
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']);

            if ($logo = $this->validateAndUpload($messages)) {
                $data['thumb'] = $logo;
            }
            $Activity = Activity::findOneObject($data['id']);
            $model = Data::getByMedia($Activity->id, 'activity');
            $validator = Activity::makeValidator($data);
            $data['updated_at'] = time();
            if (!$validator->fails()) {
                if ($result = $Activity->modifyActivity($data)) {
                    if ($model->update($data, Data::safeUpdateFields())) {
                        $key = D::memKey('apiGetActivityById', ['channel_id' => $channel_id, 'id' => $data['id']]);
                        MemcacheIO::delete($key);
                        $messages[] = Lang::_('success');
                        $vote_support_key = 'activity:vote:support:'.$channel_id.':'.$data['id'];
                        RedisIO::set($vote_support_key, $data['vote_support']);
                        $ip_limit_max_key = 'activity:ip_limit_max:'.$channel_id.':'.$data['id'];
                        $activity_limit_max = $data['activity_limit_max'];
                        if($activity_limit_max>1000) $activity_limit_max = 1000;
                        RedisIO::set($ip_limit_max_key, (int)$activity_limit_max);
                    } else {
                        $messages[] = Lang::_('error');
                    }
                } else {
                    $messages[] = $validator->messages()->all();
                }
            }
        }
        if (Request::getQuery()) {
            $id = Request::getQuery('id', 'int');
            $data = Activity::getOneActivity($id);
        }

        $vote_support_key = 'activity:vote:support:'.$channel_id.':'.$id;
        $vote_support = RedisIO::get($vote_support_key);
        $ip_limit_max_key = 'activity:ip_limit_max:'.$channel_id.':'.$id;
        $ip_limit_max = RedisIO::get($ip_limit_max_key);
        View::setMainView('layouts/add');
        View::setVars(compact('messages', 'data', 'vote_support', 'ip_limit_max'));

    }

    protected function validateAndUpload(&$messages, $dir = 'logos')
    {
        $path = '';
        if (Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if (!$error) {
                $ext = $file->getExtension();
                if (in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id . '/' . $dir);
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif ($error == 4) {
                $path = Request::getPost('oldlogo');
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }

    public function deleteAction()
    {
        $id = $this->request->getQuery("id", "int");
        $data['status'] = 0;
        $model = Data::getByMedia($id, 'activity');
        $return = $model->update($data);
        if ($return) {
            $arr = array('code' => 200);
        } else {
            $arr = array('msg' => Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function addPreMess()
    {
        $data = array();
        $as = new ActivitySignup();
        $result = $as->addMember($data);
        if ($result) {
            echo 'success';
            exit;
        } else {
            echo 'error';
            exit;
        }
    }

    public function listsignupAction()
    {
        $activity_id = $this->request->getQuery('id', 'int');
        $channel_id = Session::get('user')->channel_id;
        $data = ActivitySignup::getDataByActivityId($channel_id, $activity_id);
        $extfields = ActivityExtModel::getExtVisiabledFields($channel_id, $activity_id);
        View::setVars(compact(array('data', 'extfields', 'activity_id')));

    }

    public function listsignupsearchAction()
    {
        $input = Request::getQuery();
        $activity_id = $input['id'];
        $channel_id = Session::get('user')->channel_id;
        $search = $input;
        unset($search['_url']);
        unset($search['daochu']);
        unset($search['id']);
        foreach ($search as $key => $value) {
            if (!$value) {
                unset($search[$key]);
            }
        }
        if (isset($search['user_id'])) {
            if ($search['user_id'] == '男') {
                $search['user_id'] = 1;
            } elseif ($search['user_id'] == '女') {
                $search['user_id'] = 2;
            }
        }

        if (!$input['daochu']) {
            $data = ActivitySignup::getDataBySearch($channel_id, $activity_id, $search);
            $extfields = ActivityExtModel::getExtVisiabledFields($channel_id, $activity_id);
        } else {
            $data = ActivitySignup::getAllBySearch($channel_id, $activity_id, $search);
            $this->exportBrandWin($data);
        }

        View::pick('activity/listsignup');
        View::setVars(compact('data', 'extfields', 'search', 'activity_id'));
    }

    public function listfieldAction(){
        $activity_id = $this->request->getQuery('activity_id', 'int');
        $channel_id = Session::get('user')->channel_id;
        $data = ActivityExtModel::getExtModelListById($channel_id, $activity_id);
        View::setVars(compact('data', 'activity_id'));

    }

    public function createfieldAction() {
        $data = '';
        $messages = '';
        if ($data = Request::getPost()) {
            $data['activity_id'] = Request::get('activity_id');
            $data['channel_id'] = Session::get('user')->channel_id;

            $validator = ActivityExtModel::makeValidator($data);
//            var_dump($validator->fails());exit;
            if (!$validator->fails()) {
                $activity_ext = new ActivityExtModel();//写到这儿
                if ($result = $Activity->createActivity($data)) {
                    $id = $Activity->id;
                    $data['author_name'] = Session::get('user')->name;
                    $data['author_id'] = Session::get('user')->id;
                    $data['partition_by'] = date('Y');
                    $data['created_at'] = time();
                    $data['updated_at'] = time();
                    $dModel = new Data();
                    if ($dModel->doSaveAc($data, Data::getAllowed(), 'activity', $id)) {
                        $messages[] = Lang::_('success');
                    }
                } else {
                    $messages[] = Lang::_('error');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }

        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function getWordfileAction()
    {
        error_reporting(0);
        $id = Request::getQuery('id');
        $activity = Request::getQuery('activity');
        if ($id && $activity == 'blinddata') {
            $signup = ActivitySignup::findOneObject($id);
            $ext_fields = json_decode($signup->ext_fields, true);
            $data = $signup->toArray();
            $sex_text = $data['user_id'] == 1 ? '先生' : '女士';
            $wordStr = '
	<style>
		body{ font-size:20px;}
		td{ padding:3px;}
	</style>
    <table width="620" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td><img src="http://o.cztvcloud.com/xiangqinpics/logo2016.jpg" /></td>
        </tr>
		<tr>
            <td colspan="3" style="height:15px;"></td>
        </tr>
        <tr>
            <td style="text-align:left; width:75px; padding:0; font-size:17px; line-height:normal;">会员编号:<span style="padding-bottom:5px;border-bottom: solid #000 1px; width:100px; line-height:normal; padding:0;">' . $data['id'] . '</span></td>
        </tr>
    	<tr><td colspan="3" height="10"></td></tr>
    </table>
	<table width="620" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td width="275" height="101" valign="middle" style="border:solid #000 1px;">姓氏:&nbsp;' . $ext_fields['r_name'] . '' . $sex_text . '</td>
            <td width="170" valign="middle" style="border:solid #000 1px;border-left:none;">身高:&nbsp;' . $ext_fields['r_height'] . ' cm</td>
            <td width="155" rowspan="2" valign="middle" style="border-top:solid #000 1px; border-right:solid #000 1px; text-align:center;padding:0;">' . '<img src="' . cdn_url('image', $ext_fields['work_picture']) . '" width="155" height="200" />' . '</td>
        </tr>
        <tr>
            <td height="101" valign="middle" style="border-left:solid #000 1px;border-right:solid #000 1px;">出生年月:' . $ext_fields['b_year'] . '年' . $ext_fields['b_month'] . '月' . $ext_fields['b_date'] . '日' . '</td>
            <td valign="middle" style="border-right:solid #000 1px;">体重:&nbsp;' . (int)$data['ext_field1'] . ' kg</td>
        </tr>
        <tr>
            <td colspan="3" style="padding:0;">
				<table align="center" cellpadding="0" cellspacing="0" style="width:100%;">
			        <tr>
			            <td width="282" height="60" valign="middle" style="border:solid #000 1px;border-bottom:none;"><span style="float:left;">工作单位:(职业)</span>&nbsp;<span>' . $ext_fields['job'] . '</span></td>
			            <td rowspan="3" valign="top" style="border-top:solid #000 1px;border-right:solid #000 1px;">择偶要求:' . $ext_fields['standard'] . '</td>
			        </tr>
			        <tr>
			            <td height="60" valign="middle" style="border:solid #000 1px;">工作所在地:&nbsp;' . $ext_fields['work_place'] . '</td>
			        </tr>
			        <tr>
			            <td height="60" valign="middle" style="border-left:solid #000 1px;border-right:solid #000 1px;">籍贯:&nbsp;' . $ext_fields['r_title'] . '</td>
			        </tr>
			    </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding:0;">
				<table align="center" cellpadding="0" cellspacing="0" style="width:100%;">
			        <tr>
			            <td width="260" height="50" style="border:solid #000 1px;">学历:&nbsp;' . $ext_fields['r_degree'] . '</td>
			            <td width="310" style="border:solid #000 1px;border-left:none;">婚姻状况:&nbsp;' . $ext_fields['merry'] . '</td>
			        </tr>
			        <tr>
			            <td height="50" style="border:solid #000 1px;border-top:none;">年收入:&nbsp;' . $data['user_name'] . '万</td>
			            <td style="border:solid #000 1px;border-top:none;border-left:none;">微信:&nbsp;' . $ext_fields['weixin'] . '</td>
			        </tr>

			    </table>
            </td>
        </tr>
	</table>';

            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            $fileContent = MhtFileMaker::getWordDocument($wordStr);

            $fileName = iconv("utf-8", "GBK", $data['name'] . '_' . md5(str_random()));
            header("Content-Type: application/doc");
            header("Content-Disposition: attachment; filename=" . $fileName . ".doc");
            echo $fileContent;
        }elseif ($id && $activity == 'questionnaire') {
            $signup = ActivitySignup::findOneObject($id);
            $ext_fields = json_decode($signup->ext_fields, true);
            if ((int)$ext_fields['subject2']==1) {
                $ext_fields['subject2'] = '有';
            }elseif ((int)$ext_fields['subject2']==2) {
                $ext_fields['subject2'] = '没有';
            }elseif ((int)$ext_fields['subject2']==3) {
                $ext_fields['subject2'] = '近期将进行融媒体发展';
            }
            if ((int)$ext_fields['subject3']==1 && mb_strlen($ext_fields['subject3'])==1) {
                $ext_fields['subject3'] = '新闻中心';
            }elseif ((int)$ext_fields['subject3']==2 && mb_strlen($ext_fields['subject3'])==1) {
                $ext_fields['subject3'] = '新媒体部';
            }else {
            }
            $ext_fields['subject4'] = str_replace('1','微博公众号——官方微博',$ext_fields['subject4']);
            $ext_fields['subject4'] = str_replace('2','微信公众号',$ext_fields['subject4']);
            $ext_fields['subject4'] = str_replace('3','自有网站',$ext_fields['subject4']);
            $ext_fields['subject4'] = str_replace('4','手机台——移动客户端（APP）',$ext_fields['subject4']);
            if ((int)$ext_fields['subject6']==1) {
                $ext_fields['subject6'] = '自有运营';
            }elseif ((int)$ext_fields['subject6']==2) {
                $ext_fields['subject6'] = '与社会公司合作运营';
            }elseif ((int)$ext_fields['subject6']==3) {
                $ext_fields['subject6'] = '与新蓝网合作运营';
            }
            if ((int)$ext_fields['subject7']==1) {
                $ext_fields['subject7'] = '自有直播系统';
            }elseif ((int)$ext_fields['subject7']==2) {
                $ext_fields['subject7'] = '和传统媒体共用直播资源';
            }elseif ((int)$ext_fields['subject7']==3) {
                $ext_fields['subject7'] = '和其他融媒体平台共建直播系统';
            }elseif ((int)$ext_fields['subject7']==4) {
                $ext_fields['subject7'] = '第三方直播系统';
            }
            $ext_fields_arr8 = explode(',',$ext_fields['subject8']);
            $ext_fields['subject8'] = "记者编辑人数{$ext_fields_arr8[0]}人,技术人员人数{$ext_fields_arr8[1]}人";
            if ((int)$ext_fields['subject9']==1) {
                $ext_fields['subject9'] = '有';
            }elseif ((int)$ext_fields['subject9']==2) {
                $ext_fields['subject9'] = '没有';
            }elseif ((int)$ext_fields['subject9']==3) {
                $ext_fields['subject9'] = '突发事件新闻素材有需求';
            }
            if ((int)$ext_fields['subject10']==1) {
                $ext_fields['subject10'] = '支付稿酬';
            }elseif ((int)$ext_fields['subject10']==2) {
                $ext_fields['subject10'] = '以同等素材交换';
            }
            if ((int)$ext_fields['subject11']==1) {
                $ext_fields['subject11'] = '愿意';
            }elseif ((int)$ext_fields['subject11']==2) {
                $ext_fields['subject11'] = '不愿意';
            }

            $wordStr = '<style>
                            body{ font-size:20px;}
                            td{ padding:3px;}
                        </style>
<table width="620" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td width="150px" align="center" valign="middle" style="border:solid #000 1px;">贵公司名称</td>
        <td valign="middle" style="border:solid #000 1px;border-left:none;">'.$ext_fields['subject1'].'</td>
    </tr>
    <tr>
        <td width="150px" align="center" valign="middle" style="border:solid #000 1px;">贵单位有没有成体系的融媒体发展计划？</td>
        <td valign="middle" style="border:solid #000 1px;border-left:none;">'.$ext_fields['subject2'].'</td>
    </tr>
    <tr>
        <td width="150px" align="center" valign="middle" style="border:solid #000 1px;">贵单位融媒体发展的负责部门</td>
        <td valign="middle" style="border:solid #000 1px;border-left:none;">'.$ext_fields['subject3'].'</td>
    </tr>
    <tr>
        <td width="150px" align="center" valign="middle" style="border:solid #000 1px;">贵单位的融媒体平台</td>
        <td valign="middle" style="border:solid #000 1px;border-left:none;">'.$ext_fields['subject4'].'</td>
    </tr>
    <tr>
        <td width="150px" align="center" valign="middle" style="border:solid #000 1px;">贵单位融媒体平台的影响力</td>
        <td valign="middle" style="border:solid #000 1px;border-left:none;">'.$ext_fields['subject5'].'</td>
    </tr>
    <tr>
        <td width="150px" align="center" valign="middle" style="border:solid #000 1px;">贵单位融媒体平台的运营机制</td>
        <td valign="middle" style="border:solid #000 1px;border-left:none;">'.$ext_fields['subject6'].'</td>
    </tr>
    <tr>
        <td width="150px" align="center" valign="middle" style="border:solid #000 1px;">贵单位融媒体平台的直播能力</td>
        <td valign="middle" style="border:solid #000 1px;border-left:none;">'.$ext_fields['subject7'].'</td>
    </tr>
    <tr>
        <td width="150px" align="center" valign="middle" style="border:solid #000 1px;">贵单位融媒体团队</td>
        <td valign="middle" style="border:solid #000 1px;border-left:none;">'.$ext_fields['subject8'].'</td>
    </tr>
    <tr>
        <td width="150px" align="center" valign="middle" style="border:solid #000 1px;">贵单位融媒体平台是否有意愿与兄弟台实现新闻素材共享共用？</td>
        <td valign="middle" style="border:solid #000 1px;border-left:none;">'.$ext_fields['subject9'].'</td>
    </tr>
    <tr>
        <td width="150px" align="center" valign="middle" style="border:solid #000 1px;">贵单位愿意以何种方式和兄弟台共享新闻素材？</td>
        <td valign="middle" style="border:solid #000 1px;border-left:none;">'.$ext_fields['subject10'].'</td>
    </tr>
    <tr>
        <td width="150px" align="center" valign="middle" style="border:solid #000 1px;">浙江广电集团“云”平台即将对外启用，实现多端上载、素材共用，贵单位是否愿意入驻云平台？</td>
        <td valign="middle" style="border:solid #000 1px;border-left:none;">'.$ext_fields['subject11'].'</td>
    </tr>
    <tr>
        <td colspan="2" align="center" valign="middle" style="border:solid #000 1px;">对于合作共建融媒体中心，贵单位有哪些需求？有什么建议？</td>
    </tr>
    <tr>
        <td colspan="2" align="center" valign="middle" style="border:solid #000 1px;">'.$ext_fields['subject12'].'</td>
    </tr>
</table>';

            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            $fileContent = MhtFileMaker::getWordDocument($wordStr);

            $fileName = iconv("utf-8", "GBK", '新蓝网问卷调查_' . md5(str_random()));
            header("Content-Type: application/doc");
            header("Content-Disposition: attachment; filename=" . $fileName . ".doc");
            echo $fileContent;
        }
        View::disable();
    }

    /*
     * 交友会的导出方法
     */
    private function exportBrandWin($data)
    {
        error_reporting(0);
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        $exportData = [];
        if (!empty($data)) {
            foreach ($data as $signup) {
                $signup_array = json_decode($signup->ext_fields, true);
                $signup_array['mobile'] = $signup->mobile;
                $signup_array['name'] = $signup->name;
                $signup_array['user_id'] = $signup->user_id;
                $signup_array['user_mobile'] = $signup->user_mobile;
                $signup_array['status'] = $signup->status;
                $signup_array['ext_field1'] = $signup->ext_field1;
                $signup_array['ext_field2'] = $signup->ext_field2;

                $exportData[] = $signup_array;
            }
        }
        \F::createExcelSimple($exportData, Lang::_('blind data result'));
        View::disable();
    }

    public function listvediosAction()
    {
        $redios_vediolist_key = 'DgsAviVedioList';
        $vlist = RedisIO::lRange($redios_vediolist_key, 0, -1);
        $data = array();
        foreach ($vlist as $data_id) {
            $redios_dgsfile_key = D::redisKey('DgsVedioFile', $data_id);
            $data[$data_id] = json_decode(RedisIO::get($redios_dgsfile_key), true);
        }
        View::setVars(compact('data'));
    }

    public function auditSignupAction()
    {
        if ($data = Request::getPost()) {
            $signup_id = Request::getPost('id');
            $model = ActivitySignup::findOneObject($data['id']);
            $exfieldvals = json_decode($model->ext_values, true);
            $ext_fields = json_decode($model->ext_fields, true);
            foreach ($ext_fields as $key => $value) {
                if (isset($data[$key])) {
                    $ext_fields[$key] = $data[$key];
                }
            }
            $logo = $this->validateAndUpload($messages);
            if ($logo){
                $ext_fields['work_picture'] = $logo;
            }
            $model->ext_fields = json_encode($ext_fields);
            $data['update_at'] = time();
            $ret = $model->update($data);
            if ($ret) {
                if(isset($data['ext_field1'])) {
                    $work_vote_key = 'activity:vote:list:'.$model->channel_id.':'.$model->activity_id.':'.$data['id'];//作品投票计数
                    if((int)$data['ext_field1']) {
                        RedisIO::set($work_vote_key, (int)$data['ext_field1']);
                        $key = D::memKey('getWorkList', ['channel_id' => $model->channel_id , 'activity_id' => $model->activity_id , 'page' => 1 , 'pagenum' => 0 , 'work_type' => 1, 'work_source' => 0]);
                        MemcacheIO::delete($key);
                    }
                }
                $messages[] = Lang::_('success');
            } else {
                $messages[] = Lang::_('error');
            }
        }

        $signup_id = Request::getQuery('id');

        $data = ActivitySignup::getExtFieldsValueById($signup_id);
        $fieldsvalue = json_decode($data->ext_values, true);
        if (!$data->ext_values) {
            $ext_fields = json_decode($data->ext_fields, true);
            if (isset($ext_fields['work_ranking'])) {
                $work_ranking = ActivitySignup::getWorkRanking($data->channel_id, $data->activity_id, $data->user_id);
                $ranking = array_search($signup_id, $work_ranking) + 1;
                $ranking = $ranking ?: '未知';
            }
        }
        $vote_support_key = 'activity:vote:support:'.$data->channel_id.':'.$data->activity_id;
        $vote_support = RedisIO::get($vote_support_key);

        View::setMainView('layouts/add');
        View::setVars(compact('fieldsvalue', 'signup_id', 'ext_fields', 'messages', 'ranking' ,'data', 'vote_support'));
    }

    /*
     * 活动通知统一发送,不同活动发送方法不同
     */
    public function noticeAction() {
        $input = Request::get();
        if (isset($input['mobile']) && isset($input['activity_id']) && $input['mobile'] && preg_match("/^1[34578]\d{9}$/i", $input['mobile'])){
            $arr = array('msg' => Lang::_('活动不存在短信模板'));

            if ($input['activity_id'] == 35) {//年味儿活动短信通知
                $send_return = Message::sendYearTasteNotice($input['mobile']);
                if($send_return=='success'){
                    $arr = array('code' => 200);
                }else{
                    $arr = array('msg' => Lang::_('短信发送失败'));
                }
            }

        }else{
            $arr = array('msg' => Lang::_('电话号码不存在或格式错误'));
        }
        echo json_encode($arr);
        exit;
    }

    public function exportAction()
    {
        $activity_id = $this->request->getQuery('id', 'int');
        $channel_id = Session::get('user')->channel_id;
    }

    public function deletedgsAction()
    {
        $data_id = Request::getQuery('id');
        $keylist = 'DgsAviVedioList';
        $itemlist = D::redisKey('DgsVedioFile', $data_id);
        $ret = false;
        if ($data_id) {
            $ret = RedisIO::lRem($keylist, $data_id, 0);
            $ret = RedisIO::del($itemlist, $data_id);
        }
        if ($ret) {
            $arr = array('code' => 200);
        } else {
            $arr = array('msg' => Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }


    public function editVideoDgsAction()
    {
        $data_id = Request::get('id', 'int', 0);
        if (Request::isPost()) {
            $data = Request::getPost();
            if (Request::getUploadedFiles()[0]->getError() == 0) {
                if ($this->validateAndUpload($messages)) {
                    $data['thumb'] = $this->validateAndUpload($messages);
                }


            }
            $redios_data = array(
                'data_id' => $data_id,
                'duration' => $data['duration'],
                'title' => $data['title'],
                'vediourl' => $data['path'],
                'thumb' => $data['thumb']
            );
            $this->updateRedisQuee($redios_data, 'update');
            $messages[] = Lang::_('success');
        }
        $key = D::redisKey('DgsVedioFile', $data_id);
        $model = json_decode(RedisIO::get($key), true);
        View::setMainView('layouts/add');
        View::setVars(compact('model'));
    }


    public function addVedioDgsAction()
    {
        $data = '';
        $messages = '';
        if ($data = Request::getPost()) {
            $data['channel_id'] = Session::get('user')->channel_id;
            if (Request::getUploadedFiles()[0]->getError() == 0) {
                if ($this->validateAndUpload($messages)) {
                    $data['thumb'] = $this->validateAndUpload($messages);
                }
            }
            $_data['channel_id'] = $vdata['channel_id'] = $data['channel_id'];
            $vdata['duration'] = $data['duration'];
            $vdata_id = $this->createVideo($vdata);
            $vfdata['path'] = $data['path'];
            $vfdata['format'] = $data['format'];
            $this->cpVideoFile($vdata_id, $vfdata);
            $_data['type'] = 'video';
            $_data['source_id'] = $vdata_id;
            $_data['title'] = $data['title'];
            $_data['intro'] = '大歌神WAP端视频';
            $_data['thumb'] = $data['thumb'];
            $_data['author_name'] = Session::get('user')->name;
            $_data['author_id'] = Session::get('user')->id;
            $data['data_id'] = $this->createData($_data);
            if (!$data['isclose'] && $data['data_id']) {
                $redios_data = array(
                    'data_id' => $data['data_id'],
                    'duration' => $data['duration'],
                    'title' => $data['title'],
                    'vediourl' => $data['path'],
                    'thumb' => $data['thumb']
                );
                $this->updateRedisQuee($redios_data);
                $messages[] = Lang::_('success');
            } else {
                $messages[] = Lang::_('unknown error');
            }

        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    protected function createData($_data)
    {
        $model = new Data();
        $data['type'] = $_data['type'];
        $data['channel_id'] = $_data['channel_id'];
        $data['source_id'] = $_data['source_id'];
        $data['title'] = $_data['title'];
        $data['intro'] = $_data['intro'];
        $data['thumb'] = $_data['thumb'];
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $data['author_id'] = 1;
        $data['author_name'] = 'system';
        $data['hits'] = 0;
        $data['data_data'] = '[]';
        $data['status'] = 0;
        $data['partition_by'] = date("Y", time());
        $data_id = $model->saveGetId($data);
        if ($data_id) {
            $data['data_id'] = $data_id;
        } else {
            return false;
        }
        return $data_id;
    }

    protected function createVideo($v)
    {
        $model = new Videos();
        $data = [
            'keywords' => '',
            'channel_id' => $v['channel_id'],
            'collection_id' => 0,
            'supply_id' => 0,
            'duration' => $v['duration'],
            'created_at' => time(),
            'updated_at' => time(),
            'partition_by' => date('Y', time()),
        ];

        $id = $model->saveGetId($data);

        return $id;
    }

    protected function cpVideoFile($video_id, $vfdata)
    {
        $model = new VideoFiles();
        $model->save([
            'video_id' => $video_id,
            'path' => $vfdata['path'],
            'rate' => 500,
            'format' => $vfdata['format'],
            'width' => 640,
            'height' => 480,
            'partition_by' => date("Y", time())
        ]);
        return true;
    }

    protected function updateRedisQuee($data, $type = 'insert')
    {
        $redios_dgsfile_key = D::redisKey('DgsVedioFile', $data['data_id']);
        $redios_vediolist_key = 'DgsAviVedioList';
        if ($type == 'insert')
            RedisIO::lPush($redios_vediolist_key, $data['data_id']);

        RedisIO::set($redios_dgsfile_key, json_encode($data));
    }

    /**
     * 添加视频到redis链表
     */
    public function addVideoAction()
    {

        if ($data = Request::getPost()) {
            if (Request::getUploadedFiles()[0]->getError() == 0) {
                if ($this->validateAndUpload($messages)) {
                    $data['thumb'] = $this->validateAndUpload($messages);
                }
            } else {
                $data['thumb'] = '';
            }

            $listName = 'videoListId:' . Request::getQuery('id','int'); //视频redis链表名
            $rqListName = 'rqVideoList';                             //热门视频redis链表名
            $rmListName = 'rmVideoList';                             //热门视频redis链表名
            $ycListName = 'ycVideoList';                             //原创视频redis链表名


            //RedisIO::delete($listName);

            $videoId = RedisIO::incr('videoId');  //redis自增长视频id

            $data['id'] = $videoId;     //赋值ID
            $data['comments'] = 0;      //赋值ID
            $data['y_good'] = 0;      //赋值ID
            $data['w_good'] = 0;      //赋值ID
            $type = $data['type'];
            $json = json_encode($data);
            //存入redis list表
            RedisIO::lPush($listName, $videoId);

            //存入人气视频
            if (isset($data['type']['rq'])) {
                RedisIO::lPush($rqListName, $videoId);
            }

            //存入原创视频
            if (isset($data['type']['rm'])) {
                RedisIO::lPush($rmListName, $videoId);
            }
            //存入热门视频
            if (isset($data['type']['yc'])) {
                RedisIO::lPush($ycListName, $videoId);
            }

            //存入redis
            $res = RedisIO::set('videoId:' . $videoId, $json);

            //如果不是原创视频,存入有序集合
            if (!isset($type['yc'])) {
                RedisIO::zAdd('pkHsySort', 0, $videoId);                      //总排序有序集合
                RedisIO::zAdd('pkHsySort:' . date('W'), 0, $videoId);         //周排序有序集合
            }

            if ($res) {
                $messages = array('messages' => Lang::_('success'));
            } else {
                $messages = array('messages' => Lang::_('error'));
            }
            View::setVars(compact('messages'));
        }


    }

    /**
     * 从Redis获取视频列面
     */
    public function listVideoAction()
    {
        $listName = 'videoListId:' . Request::getQuery('id');   //视频redis链表名

        $vlist = RedisIO::lRange($listName, 0, -1);    //获取视频
        $data = array();
        foreach ($vlist as $value) {
            $data[$value] = json_decode(RedisIO::get('videoId:' . $value), true);
        }

        View::setVar('data', $data);
    }

    /**
     * 修改视频信息
     */
    public function editVideoAction()
    {
        if ($data = Request::getPost()) {
            //上传图片
            if (Request::getUploadedFiles()[0]->getError() == 0) {
                if ($this->validateAndUpload($messages)) {
                    $data['thumb'] = $this->validateAndUpload($messages);
                }
            }

            $res = json_decode(RedisIO::get('videoId:' . $data['id']), true);    //获取原数据
            $datas = array_merge($res, $data);  //合并数组
            $ret = RedisIO::set('videoId:' . $data['id'], json_encode($datas));   //存入新数据
            if ($ret) {
                $messages = array('messages' => Lang::_('success'));
            } else {
                $messages = array('messages' => Lang::_('error'));
            }
            View::setVars(compact('data'));
            View::setVars(compact('messages'));
        } else {
            $id = Request::getQuery('id');
            $data = json_decode(RedisIO::get('videoId:' . $id), true);
            View::setVars(compact('data'));
        }
    }

    /**
     * 评论管理
     */
    public function listCmtAction()
    {
        $id = Request::getQuery("vid", "int");
        $arr = RedisIO::lRange("comMentList" . $id, 0, -1);
        $data = array();
        foreach ($arr as $val) {
            $data[] = json_decode(RedisIO::get('pkhsy_cid_' . $val), true);
        }
        View::setVars(compact("data"));
    }

    /**
     * 删除评论
     */
    public function delCmtAction()
    {
        $vid = Request::getQuery("vid", "int");
        $id = Request::getQuery("id", "int");
        RedisIO::lRem("comMentList" . $vid, $id, 0);       //删除队列链表
        $res = RedisIO::delete("pkhsy_cid_" . $id);       //删除评论信息
        if ($res) {
            $message = array('code' => 200);
        } else {
            $message = array('msg' => Lang::_('failed'));
        }
        echo json_encode($message);
        exit;
    }

    /**
     * 评论审核
     */
    public function statusAction()
    {
        $id = Request::getQuery('id', 'int');
        $vid = Request::getQuery('vid', 'int');
        $status = Request::getQuery('status');

        //更新通过评论数
        $this->upComMent($vid);

        //修改redis记录
        $data = json_decode(RedisIO::get('pkhsy_cid_' . $id), true);
        $data['status'] = $status;
        $data = json_encode($data);
        $res = RedisIO::set('pkhsy_cid_' . $id, $data);
        if ($res) {
            $arr = array('code' => 200);
        } else {
            $arr = array('msg' => Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 删除视频
     */
    public function delVideoAction()
    {
        $listName = 'videoListId:' . Request::getQuery('pid','int');   //视频redis链表
        $rqListName = 'rqVideoList';                             //热门视频redis链表名
        $rmListName = 'rmVideoList';                             //热门视频redis链表名
        $ycListName = 'ycVideoList';                             //原创视频redis链表名

        $id = Request::getQuery('id','int');
        $res = RedisIO::delete('videoId:' . $id);
        $res = RedisIO::lRem($listName, $id, 0);
        RedisIO::zRem('pkHsySort', $id);                      //删除年有序集合
        RedisIO::ZRem('pkHsySort:' . date('W'), $id);         //删除周有序集合
        RedisIO::lRem($rqListName, $id, 0);                      //删除人气有序集合
        RedisIO::lRem($rmListName, $id, 0);                      //删除热门有序集合
        RedisIO::lRem($ycListName, $id, 0);                      //删除原创有序集合


        if ($res) {
            $arr = array('code' => 200);
        } else {
            $arr = array('msg' => Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * @param $vid
     * @return array
     */
    public function upComMent($vid)
    {
        //修改评论记录
        $info = json_decode(RedisIO::get('videoId:' . $vid), true);
        $arr = RedisIO::lRange("comMentList" . $vid, 0, -1);   //获取评论总数

        //计算通过审核的评论数
        if (false !== $arr) {
            $comments = array();
            foreach ($arr as $val) {
                $comments[$val] = json_decode(RedisIO::get('pkhsy_cid_' . $val), true);
                //过虑已核的评论
                if ($comments[$val]['status'] != 1) {
                    unset($comments[$val]);
                }
            }
        }
        //获取评论总数
        $info['comments'] = count($comments);
        $res = RedisIO::set('videoId:' . $vid, json_encode($info));
        return $res;
    }

    /**
     * brand管理
     */
    public function listImagesAction()
    {
        $thumbListName = "thumbListName";
        $res = RedisIO::lRange($thumbListName, 0, -1);
        //var_dump($res);die;

        $data = array();
        foreach ($res as $value){
            //RedisIO::lRem($thumbListName,$value);
            $data[$value] = RedisIO::get("pkhsy_thumb:{$value}");
        }
        //var_dump($data);die;
        View::setVars(compact('data'));
    }

    /**
     * 添加brand
     */
    public function addImagesAction(){
        $thumbListName = "thumbListName";
        if(Request::isPost()) {
            //上传图片
            if (Request::getUploadedFiles()[0]->getError() == 0) {
                if ($this->validateAndUpload($messages)) {
                    $thumb = $this->validateAndUpload($messages);
                    $thumb_id = RedisIO::incr("thumb_id");
                    RedisIO::set("pkhsy_thumb:" . $thumb_id, $thumb);
                    $res = RedisIO::lpush($thumbListName, $thumb_id);
                }
            }
            if ($res) {
                $messages = array('messages' => Lang::_('success'));
            } else {
                $messages = array('messages' => Lang::_('error'));
            }
            View::setVars(compact('messages'));
        }
    }

    /**
     * 修改brand
     */
    public function editImagesAction(){
        $id = Request::getQuery('pic_id','int');
        if(Request::isPost()) {
            //上传图片
            if (Request::getUploadedFiles()[0]->getError() == 0) {
                if ($this->validateAndUpload($messages)) {
                    $thumb = $this->validateAndUpload($messages);
                    $res = RedisIO::set("pkhsy_thumb:" . $id, $thumb);
                }
            }
            if ($res) {
                $messages = array('messages' => Lang::_('success'));
            } else {
                $messages = array('messages' => Lang::_('error'));
            }
            View::setVars(compact('messages'));
        }
    }

    /**
     * 删除brand
     */
    public function delImagesAction(){
        $thumbListName = "thumbListName";
        $id = Request::getQuery('pic_id','int');
        RedisIO::lRem($thumbListName,$id);
        $res = RedisIO::delete("pkhsy_thumb:{$id}");
        if($res){
            $arr = array('code'=>200);
        } else {
            $arr = array('msg' => Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
   }

    /**
     * 删除开发
     */
    public function delListAction()
    {
        $pwd = Request::getQuery('pwd','string');
        $listname = Request::getQuery('listname','string');
        if($pwd == '18968111180') {
            if($listname){
                RedisIO::delete($listname);
                echo "{$listname} delete success";
            } else {
                RedisIO::delete('pkHsySort:' . date('W'));  //获取周排行;
                RedisIO::delete('pkHsySort');  //获取周排行;
                echo "all delete success";
            }
        }
    }
}
