<?php
/**
 * Created by PhpStorm.
 * User: zhangyichi
 * Date: 2016/6/17
 * Time: 15:57
 */
class ActivityController extends InteractionBaseController{

    const AcivityNumber='ayun_activity_number';

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * 上传接口 完成
     */
    public function uploadWorkAction() {
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $input = Request::getPost();
        if(!empty($input) && $channel_id && $activity_id){
            $input = $this->xss_filter($input);
            if(!$this->filterInput($input)){
                $this->jsonp(array('code'=>204,'msg'=>'作品参数不全'));
            }

            $ip = F::getRealIp();
            $key = 'cztv::activity_signup::ip::'.$channel_id.'::'.$activity_id.'::'.$ip;
            $up_times = RedisIO::get($key);
            if(!$up_times){
                $time = strtotime(date("Y-m-d 00:00:00",strtotime("+1 day")))-time();
                RedisIO::set($key,1,$time);
            }elseif($up_times>=50){
                $this->jsonp(array('code'=>203,'msg'=>'此ip今天已经提交过50个作品'));
            }else{
                RedisIO::incr($key);
            }

            $key = 'cztv::activity_signup::work_number::'.$channel_id.'::'.$activity_id;
            $work_number = RedisIO::get($key);
            if(!$work_number){
                RedisIO::set($key,0);
            }

            $json = array();//额外字段
            $message = [];
            $json['work_ranking'] = '以前台为准';//是否需要排名
            $json['work_picture'] = $this->validateAndUpload($message);//作品上传
            $json['work_intro'] = $input['work_intro'];
            $json['work_number'] = RedisIO::incr($key);//作品编号
            $json = json_encode($json);

            $data = array();
            $data['channel_id'] = $channel_id;
            $data['activity_id'] = $activity_id;
            $data['mobile'] = intval($input['mobile']);//mobile
            $data['name'] = $input['work_author'];//name
            $data['user_id'] = $input['work_source'];//来源：1为pc上传，2为h5上传
            $data['user_name'] = $input['work_title'];//user_name
            $data['create_at'] = time();
            $data['update_at'] = time();
            $data['ext_field2'] = $input['work_type'];//类型
            $data['ext_fields'] = $json;

            $activity_signup = new ActivitySignup();//创建使用队列,未完成
            $return = $activity_signup->createActivitySignup($data);
            if($return){
                $key = 'cztv::activity_signup::work_ranking::' . $channel_id . '::' . $activity_id . '::' . 0;
                MemcacheIO::delete($key);
                $this->jsonp(array('code'=>200,'msg'=>'成功'));
            }else{
                $this->jsonp(array('code'=>202,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }

    protected function filterInput($input) {
        if(!isset($input['mobile']) || !preg_match("/^1[34578]\d{9}$/i", $input['mobile'])){
            return false;
        }
        if(!isset($input['work_author']) || !$input['work_author']){
            return false;
        }
        if(!isset($input['work_title']) || !$input['work_title']){
            return false;
        }
        return true;
    }

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
                    $path = Oss::uniqueUpload($ext, $file->getTempName(), 'ppzjm_work');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif($error == 4) {
                $messages[] = Lang::_('unknown error');
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }

    /**
     * 公益广告大赛接口
     */
    public function microFilmAction() {
        $channel_id = Request::getPost('channel_id','int');
        $activity_id = Request::getPost('activity_id','int');
        if($channel_id && $activity_id) {
            $input = Request::getPost();

            if ($return_msg = $this->confirmActivity($channel_id,$activity_id)){
                $this->jsonp(array('code'=>2005,'msg'=>$return_msg));
            }

            $input = $this->xss_filter($input);
            if($return_msg = $this->filterInputForMicroFilm($input)){//必要字段判断
                $this->jsonp(array('code'=>2004,'msg'=>$return_msg));
            }

            $ip = F::getRealIp();
            $key = 'cztv::activity_signup::ip::'.$channel_id.'::'.$activity_id.'::'.$ip;
            $up_times = RedisIO::get($key);
            if(!$up_times){
                $time = strtotime(date("Y-m-d 00:00:00",strtotime("+1 day")))-time();
                RedisIO::set($key,1,$time);
            }elseif($up_times>=50){
                $this->jsonp(array('code'=>2003,'msg'=>'此ip今天已经提交过50个作品'));
            }else{
                RedisIO::incr($key);
            }

            $key = 'cztv::activity_signup::work_number::'.$channel_id.'::'.$activity_id;
            $work_number = RedisIO::get($key);
            if(!$work_number){
                RedisIO::set($key,0);
            }

            $json = array();//额外字段
            $json['unit'] = $input['unit'];//申报单位
            $json['contacts_person'] = $input['contacts_person'];//联系人
            $json['movie_url'] = $input['movie_url']?'http://yf.ugc.v.cztv.com'.$input['movie_url']:'';//上传视频相对路径
            $json['movie_id'] = $input['movie_id'];//上传视频附件id
            $json['work_picture'] = $input['work_picture'];//上传剧照
            $json['director'] = $input['director'];//导演
            $json['screenwriter'] = $input['screenwriter'];//编剧
            $json['cameraman'] = $input['cameraman'];//摄影
            $json['actor'] = $input['actor'];//男主角
            $json['actress'] = $input['actress'];//女主角
            $json['intro'] = $input['intro'];//内容简介
            $json = json_encode($json);

            $data = array();//主要字段
            $data['channel_id'] = $channel_id;
            $data['activity_id'] = $activity_id;
            $data['mobile'] = $input['mobile'];//联系人电话
            $data['name'] = $input['director'];//导演
            $data['user_id'] = null;
            $data['user_name'] = $input['title'];//作品名称
            $data['create_at'] = time();
            $data['update_at'] = time();
            $data['status'] = $input['status']?:0;
            $data['ext_field1'] = null;
            $data['ext_field2'] = $input['ext_field2']?:1;//多用于分类
            $data['ext_fields'] = $json;

            $activity_signup = new ActivitySignup();
            $return = $activity_signup->createActivitySignup($data);
            if($return){
                $key = 'cztv::activity_signup::work_number::'.$channel_id.'::'.$activity_id;
                RedisIO::incr($key);
                $key = 'cztv::activity_signup::work_count::' . $channel_id . '::' . $activity_id . '::' . $input['ext_field2'];
                RedisIO::incr($key);
                $this->jsonp(array('code'=>200,'msg'=>'上传成功','work_id'=>$activity_signup->id));
            }else{
                $this->jsonp(array('code'=>2002,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'频道或活动参数为空'));
        }
    }

    protected function confirmActivity($channel_id,$activity_id) {
        $activity = Activity::apiGetActivityById($channel_id,$activity_id);
        if (!$activity) {
            return '活动不存在';
        }
        if ($activity['start_time']>time()) {
            return '活动未开始';
        }
        if ($activity['end_time']<time()) {
            return '活动已结束';
        }
        return false;
    }

    protected function filterInputForMicroFilm($input) {
        if (!isset($input['title']) || !$input['title']) {
            return '标题未填';
        }
        if (!isset($input['unit']) || !$input['unit']) {
            return '申报单位未填';
        }
        if (!isset($input['contacts_person']) || !$input['contacts_person']) {
            return '联系人未填';
        }
        if (!isset($input['mobile']) || !preg_match("/^1[0123456789]\d{9}$/i", $input['mobile'])) {
            return '联系电话未填或格式错误';
        }
        if (!isset($input['director']) || !$input['director']) {
            return '导演未填';
        }
        return false;
    }

    /**
     * 图片上传接口
     */
    public function uploadPictureAction() {
        $message=[];
        $path = $this->validateAndUpload($message);
        if($path!=''){
            $this->jsonp(array('code'=>200,'msg'=>'上传成功','url'=>cdn_url('image',$path)));
        }else{
            $this->jsonp(array('code'=>201,'msg'=>$message[0]));
        }
    }

    /**
     * 共享单车活动报名接口
     */
    public function uploadWorkForShareBikeAction() {
        $channel_id = Request::getPost('channel_id','int');
        $activity_id = Request::getPost('activity_id','int');
        if($channel_id && $activity_id) {
            $input = Request::getPost();

            if ($return_msg = $this->confirmActivity($channel_id,$activity_id)){
                $this->jsonp(array('code'=>2005,'msg'=>$return_msg));
            }

            $input = $this->xss_filter($input);
            if($return_msg = $this->filterInputForShareBike($input)){//必要字段判断
                $this->jsonp(array('code'=>2004,'msg'=>$return_msg));
            }

            $ip = F::getRealIp();
            $key = 'cztv::activity_signup::ip::'.$channel_id.'::'.$activity_id.'::'.$ip;
            $up_times = RedisIO::get($key);
            if(!$up_times){
                $time = strtotime(date("Y-m-d 00:00:00",strtotime("+1 day")))-time();
                RedisIO::set($key,1,$time);
            }elseif($up_times>=50){
                $this->jsonp(array('code'=>2003,'msg'=>'此ip今天已经提交过50个作品'));
            }else{
                RedisIO::incr($key);
            }

            $key = 'cztv::activity_signup::work_number::'.$channel_id.'::'.$activity_id;
            $work_number = RedisIO::get($key);
            if(!$work_number){
                RedisIO::set($key,0);
            }

            $json = array();//额外字段
            $json['work_picture'] = $input['work_picture'];//图片地址
            $json['work_intro'] = $input['work_intro'];//图片描述
            $json = json_encode($json);

            $data = array();//主要字段
            $data['channel_id'] = $channel_id;
            $data['activity_id'] = $activity_id;
            $data['mobile'] = '13000000000';//此次固定
            $data['name'] = $input['name'];//姓名
            $data['user_id'] = null;
            $data['user_name'] = $input['name'];//姓名
            $data['create_at'] = time();
            $data['update_at'] = time();
            $data['ext_field1'] = null;
            $data['ext_field2'] = 1;
            $data['ext_fields'] = $json;

            $activity_signup = new ActivitySignup();
            $return = $activity_signup->createActivitySignup($data);
            if($return){
                $key = 'cztv::activity_signup::work_number::'.$channel_id.'::'.$activity_id;
                $work_sum = RedisIO::incr($key);
                $this->jsonp(array('code'=>200,'msg'=>'上传成功','sum'=>$work_sum));
            }else{
                $this->jsonp(array('code'=>2002,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'频道或活动参数为空'));
        }
    }

    protected function filterInputForShareBike($input) {
        if (!isset($input['name']) || !$input['name']) {
            return '姓名未填';
        }
        if (!isset($input['work_picture']) || !$input['work_picture']) {
            return '图片内容未填';
        }
        if (!isset($input['work_intro']) || !$input['work_intro']) {
            return '图片描述未填';
        }
        if (mb_strlen($input['name'],'UTF-8') > 24){
            return '姓名长度过长';
        }
        if (mb_strlen($input['work_intro'],'UTF-8') > 100){
            return '图片描述长度过长';
        }
        return false;
    }

    /**
     * 健康树和2018跨年活动
     * 可用于微信端用户报名，且用户报名信息唯一覆盖
     */
    public function uploadWorkForWeiXinCoverAction() {
        $channel_id = Request::getPost('channel_id','int');
        $activity_id = Request::getPost('activity_id','int');
        if($channel_id && $activity_id) {
            $input = Request::getPost();

            if ($return_msg = $this->confirmActivity($channel_id,$activity_id)){
                $this->jsonp(array('code'=>2005,'msg'=>$return_msg));
            }

            $input = $this->xss_filter($input);
            if($return_msg = $this->filterInputForWeiXinCover($input)){//必要字段判断
                $this->jsonp(array('code'=>2004,'msg'=>$return_msg));
            }

            $user_token = Request::getPost('open_id');
            $user = RedisIO::get('interaction::vote::upwork::' . $user_token);
            if (!$user || !$user_token) {
                $this->jsonp(array('code'=>2003,'msg'=>'请先关注微信公众号'));
            }

            $work_number_key = 'cztv::activity_signup::work_number::'.$channel_id.'::'.$activity_id;
            $work_number = RedisIO::get($work_number_key);
            if(!$work_number){
                RedisIO::set($work_number_key,0);
            }

            $activity_signup = ActivitySignup::apiGetActivitySignupByName($channel_id, $activity_id, $input['name']);
            if ($activity_signup) {
                $data = $activity_signup;
                $json = json_decode($data['ext_fields'],true);

                if ($json['work_update']>=$input['work_update']){
                    $this->jsonp(array('code'=>200,'msg'=>'上传成功','sum'=>$work_number));
                }

                $json['work_picture'] = $input['work_picture']?:$json['work_picture'];//图片地址
                $json['work_intro'] = $input['work_intro']?:$json['work_intro'];//图片描述
                $json['work_update'] = $input['work_update']?:$json['work_update'];//作品更新使用字段
                $json = json_encode($json);
                $data['ext_fields'] = $json;

                $data['name'] = $input['name'];//姓名
                $data['user_name'] = $input['user_name'];//姓名
                $data['ext_field1'] = $input['ext_field1']?:$data['ext_field1'];
                $data['update_at'] = time();
            }else {
                $json = array();//额外字段
                $json['work_picture'] = $input['work_picture'];//图片地址
                $json['work_intro'] = $input['work_intro'];//图片描述
                $json['work_update'] = $input['work_update'];//作品更新使用字段
                $json = json_encode($json);

                $data = array();//主要字段
                $data['channel_id'] = $channel_id;
                $data['activity_id'] = $activity_id;
                $data['mobile'] = '13000000000';//此次固定
                $data['name'] = $input['name'];//姓名
                $data['user_id'] = null;
                $data['user_name'] = $input['user_name'];//姓名
                $data['create_at'] = time();
                $data['update_at'] = time();
                $data['status'] = 1;
                $data['ext_field1'] = $input['ext_field1']?:0;
                $data['ext_field2'] = 1;
                $data['ext_fields'] = $json;

                $key = 'cztv::activity_signup::work_number::'.$channel_id.'::'.$activity_id;
                $work_number = RedisIO::incr($key);
            }
            $activity_signup = new ActivitySignup();
            $return = $activity_signup->createActivitySignup($data);
            $mem_key = D::memKey('apiGetActivitySignupByParameter', ['channel_id' => $channel_id, 'activity_id' => $activity_id, 'name' => $input['name']]);
            MemcacheIO::delete($mem_key);

            if($return){
                $this->jsonp(array('code'=>200,'msg'=>'上传成功','sum'=>$work_number));
            }else{
                $this->jsonp(array('code'=>2002,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'频道或活动参数为空'));
        }
    }

    protected function filterInputForWeiXinCover($input) {
        if (!isset($input['name']) || !$input['name']) {
            return '姓名未填';
        }
        if (!isset($input['user_name']) || !$input['user_name']) {
            return '姓名未填';
        }
        if (!isset($input['open_id']) || !$input['open_id']) {
            return '用户open_id未填';
        }
        if (!isset($input['work_intro']) || !$input['work_intro']) {
            return '报名内容未填';
        }

        return false;
    }

    /**
     * 调查文卷提交接口
     */
    public function uploadWorkForQuestionnaireAction() {
        $input = file_get_contents("php://input");
        $input = json_decode($input,true);
        $channel_id = $input['channel_id'];
        $activity_id = $input['activity_id'];
        if($channel_id && $activity_id) {
            if ($return_msg = $this->confirmActivity($channel_id,$activity_id)) {
                $this->jsonp(array('code'=>2005,'msg'=>$return_msg));
            }

            $input = $this->xss_filter($input);
            if($return_msg = $this->filterInputForQuestionnaire($input)) {//必要字段判断
                $this->jsonp(array('code'=>2004,'msg'=>$return_msg));
            }

            $ip = F::getRealIp();
            $key = 'cztv::activity_signup::ip::'.$channel_id.'::'.$activity_id.'::'.$ip;
            $up_times = RedisIO::get($key);
            if(!$up_times){
                $time = strtotime(date("Y-m-d 00:00:00",strtotime("+1 day")))-time();
                RedisIO::set($key,1,$time);
            }elseif($up_times>=100){
                $this->jsonp(array('code'=>2003,'msg'=>'此ip今天已经提交过100个作品'));
            }else{
                RedisIO::incr($key);
            }

            $key = 'cztv::activity_signup::work_number::'.$channel_id.'::'.$activity_id;
            $work_number = RedisIO::get($key);
            if(!$work_number){
                RedisIO::set($key,0);
            }

            $json = array();//额外字段
            foreach ($input as $key => $value) {
                $json[$key] = $value;
            }
            $json = json_encode($json);

            $data = array();//主要字段
            $data['channel_id'] = $channel_id;
            $data['activity_id'] = $activity_id;
            $data['mobile'] = '13000000000';//此次固定
            $data['name'] = '新蓝网问卷';//姓名
            $data['user_id'] = null;
            $data['user_name'] = '新蓝网问卷';//姓名
            $data['create_at'] = time();
            $data['update_at'] = time();
            $data['ext_field1'] = null;
            $data['ext_field2'] = 1;
            $data['ext_fields'] = $json;

            $activity_signup = new ActivitySignup();
            $return = $activity_signup->createActivitySignup($data);
            if($return){
                $key = 'cztv::activity_signup::work_number::'.$channel_id.'::'.$activity_id;
                RedisIO::incr($key);
                $this->jsonp(array('code'=>200,'msg'=>'上传成功'));
            }else{
                $this->jsonp(array('code'=>2002,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'频道或活动参数为空'));
        }
    }

    protected function filterInputForQuestionnaire($input) {
        for ($i=1 ; $i<=12 ; $i++){
            if (!isset($input['subject'.$i]) || !$input['subject'.$i]) {
                return "第{$i}题未填写";
            }
        }
        return false;
    }
	
	
    /**
     * 投票接口 完成
     */
    public function upWorkAction() {//需要获取ip
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $work_id = Request::getQuery('work_id','int');
        if(isset(app_site()->memprefix)&&"nhudong_product"!=app_site()->memprefix) {
            $queueName = app_site()->memprefix . "actvotemns";
        }
        else {
            $queueName = "actvotemns";
        }
        if($channel_id && $activity_id){
            $activity = Activity::apiGetActivityById($channel_id,$activity_id);
            if ($activity && isset($activity['end_time'])){
                if ($activity['end_time']<time()){
                    $this->jsonp(array('code'=>201,'msg'=>'投票活动已结束'));
                }
            }else{
                $this->jsonp(array('code'=>201,'msg'=>'活动不存在'));
            }
            $ip = F::getRealIp();
            $ip_limit_max = RedisIO::get('activity:ip_limit_max:'.$channel_id.':'.$activity_id);
            if(!$ip_limit_max) {
                $ip_limit_max = 10;
            }
            $key = 'cztv::activity_signup::ip::'.$channel_id.'::'.$activity_id.'::'.$ip;
            $up_times = RedisIO::get($key);
            if(!$up_times){
                $time = strtotime(date("Y-m-d 00:00:00",strtotime("+1 day")))-time();
                RedisIO::set($key,1,$time);
            }elseif($up_times>=$ip_limit_max){
                $this->jsonp(array('code'=>202,'msg'=>'此ip今天已经投过'.$ip_limit_max.'票'));
            }else{
                RedisIO::incr($key);
            }
            $work_vote_key = 'activity:vote:list:'.$channel_id.':'.$activity_id.':'.$work_id;//作品投票计数
            $work_vote = RedisIO::get($work_vote_key);
            if(!$work_vote) {
                $work = ActivitySignup::getWorkById($work_id);
                $work_vote = $work->ext_field1;
                RedisIO::set($work_vote_key, $work_vote);
            }
            RedisIO::incr($work_vote_key);//增加一票
            $work_vote++;
            //加入队列
            $this->queue->sendMessage(json_encode(['work_id'=>$work_id, 'work_vote'=>$work_vote]), $queueName);
            $this->jsonp(array('code'=>200,'msg'=>'成功'));
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }

    /*
     * 获取投票数
     * */
    public function getWorkVoteAction() {
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $work_id = Request::getQuery('work_id','int');
        $work_vote_key = 'activity:vote:list:'.$channel_id.':'.$activity_id.':'.$work_id;//作品投票计数
        $work_vote = RedisIO::get($work_vote_key);
        if(!$work_vote) {
            $work = ActivitySignup::getWorkById($work_id);
            $work_vote = $work->ext_field1;
            RedisIO::set($work_vote_key, $work_vote);
        }
        $result = [
            "work_id" => $work_id,
            "work_vote" => $work_vote
        ];
        $this->_json($result);
    }

    /**
     * 中奖奖品进行投票接口
     */
    public function lotteryWinningsVoteAction() {
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $work_id = Request::getQuery('work_id','int');
        $client_id = Request::getQuery('client_id','string');
        $lottery_id = Request::getQuery('lottery_id','string');

        $page = Request::getQuery('page','int');
        $pagenum = Request::getQuery('pagenum','int');
        $work_type = Request::getQuery('work_type','int');
        $work_source = Request::getQuery('work_source','int');
        if($channel_id && $activity_id){
            $activity = Activity::apiGetActivityById($channel_id,$activity_id);
            if ($activity && isset($activity['end_time'])){
                if ($activity['end_time']<time()){
                    $this->jsonp(array('code'=>203,'msg'=>'投票活动已结束'));
                }
            }else{
                $this->jsonp(array('code'=>202,'msg'=>'活动不存在'));
            }

            $lottery_winnings = LotteryWinnings::getNotRealForClientAndLottery($lottery_id, $client_id);
            if ($lottery_winnings == null) {
                $this->jsonp(array('code'=>203,'msg'=>'票数不足'));
            }
            if ($lottery_winnings->contacts_token == null) {
                $lottery_winnings->sum = 0;
                $lottery_winnings->update();
                $this->jsonp(array('code'=>204,'msg'=>'兑换码不存在'));
            }else{
                DB::begin();
                $lottery_winnings->sum = 0;
                $lottery_winnings->update();
                $contact = LotteryContacts::getOneByToken($lottery_winnings->contacts_token);
                if ($contact == null){
                    DB::rollback();
                    $this->jsonp(array('code'=>204,'msg'=>'兑换码不存在'));
                }
                $contact->status = 2;
                $contact->update();

                $up_return = ActivitySignup::upWorkById($work_id);
                if ($up_return == false) {
                    DB::rollback();
                    $this->jsonp(array('code'=>205,'msg'=>'投票失败'));
                }

                DB::commit();
                $key = D::memKey('getWorkList', ['channel_id' => $channel_id , 'activity_id' => $activity_id , 'page' => $page?:1 , 'pagenum' => $pagenum?:50 , 'work_type' => $work_type?:1, 'work_source' => $work_source?:0]);
                MemcacheIO::delete($key);
                $this->jsonp(array('code'=>200,'msg'=>'投票成功'));
            }
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'频道或活动id为空'));
        }
    }

    /**
     * 简单报名提交接口
     */
    public function simplesubmitAction() {
        $channel_id = Request::getPost('channel_id','int');
        $activity_id = Request::getPost('activity_id','int');
        if($channel_id && $activity_id) {
            $input = Request::getPost();

            if ($return_msg = $this->confirmActivity($channel_id,$activity_id)){
                $this->jsonp(array('code'=>2005,'msg'=>$return_msg));
            }

            $input = $this->xss_filter($input);
//            if($return_msg = $this->filterInputForMicroFilm($input)){//必要字段判断
//                $this->jsonp(array('code'=>2004,'msg'=>$return_msg));
//            }

            $ip = F::getRealIp();
            $key = 'cztv::activity_signup::ip::'.$channel_id.'::'.$activity_id.'::'.$ip;
            $up_times = RedisIO::get($key);
            if(!$up_times){
                $time = strtotime(date("Y-m-d 00:00:00",strtotime("+1 day")))-time();
                RedisIO::set($key,1,$time);
            }elseif($up_times>=50){
                $this->jsonp(array('code'=>2003,'msg'=>'此ip今天已经提交过50个作品'));
            }else{
                RedisIO::incr($key);
            }

            $key = 'cztv::activity_signup::work_number::'.$channel_id.'::'.$activity_id;
            $work_number = RedisIO::get($key);
            if(!$work_number){
                RedisIO::set($key,0);
            }

            $json = array();//额外字段
            foreach ($input as $key => $value) {
                $json[$key] = $value;
            }
            $json = json_encode($json);

            $data = array();//主要字段
            $data['channel_id'] = $channel_id;
            $data['activity_id'] = $activity_id;
            $data['mobile'] = $input['mobile'];//联系人电话
            $data['name'] = $input['name'];//导演
            $data['user_id'] = null;
            $data['user_name'] = $input['name'];//作品名称
            $data['create_at'] = time();
            $data['update_at'] = time();
            $data['status'] = $input['status']?:0;
            $data['ext_field1'] = null;
            $data['ext_field2'] = $input['ext_field2']?:1;//多用于分类
            $data['ext_fields'] = $json;

            $activity_signup = new ActivitySignup();
            $return = $activity_signup->createActivitySignup($data);
            if($return){
                $key = 'cztv::activity_signup::work_number::'.$channel_id.'::'.$activity_id;
                $work_number = RedisIO::incr($key);
                $activity = Activity::apiGetActivityById($channel_id,$activity_id);
                $number = $activity['params1']?:0;
                $this->jsonp(array('code'=>200,'msg'=>'上传成功','work_number'=> $number + $work_number));
            }else{
                $this->jsonp(array('code'=>2002,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'频道或活动参数为空'));
        }
    }

    public function uploadNewYearAction() {
        $channel_id = Request::getPost('channel_id','int');
        $activity_id = Request::getPost('activity_id','int');
        $input = Request::getPost();
        if($channel_id && $activity_id) {

            if ($return_msg = $this->confirmActivity($channel_id,$activity_id)){
                $this->jsonp(array('code'=>2005,'msg'=>$return_msg));
            }

            $input = $this->xss_filter($input);
            if($return_msg = $this->validatorGameScore($input)){//必要字段判断
                $this->jsonp(array('code'=>2004,'msg'=>$return_msg));
            }

            $user_token = Request::getPost('open_id');
            $user = RedisIO::get('interaction::vote::upwork::' . $user_token);
            if (!$user || !$user_token) {
                $this->jsonp(array('code'=>2003,'msg'=>'请先关注微信公众号'));
            }
            $activity = Activity::apiGetActivityById($channel_id,$activity_id);
            if($activity) {
                if ($activity['params1'] >= $input['ext_field1']) {//额外活动条件
                    $input['channel_id'] = $channel_id;
                    $input['activity_id'] = $activity_id;
                    $input['create_at'] = time();
                    $input['update_at'] = time();
                    $input['status'] = 1;//默认是否通过，以后要后台配置
                    $return = $this->uploadActivitySignup($input, 'user_name');
                    if ($return) {
                        if ($return != 'update') {
                            $activity['singup_count']++;
                            $new_acitivity = new Activity();
                            $new_acitivity->assign($activity);
                            $new_acitivity->update();
                        }
                        $this->jsonp(array('code' => 200, 'msg' => '报名成功'));
                    } else {
                        $this->jsonp(array('code' => 2004, 'msg' => '报名表保存失败'));
                    }
                } else {
                    $this->jsonp(array('code' => 2003, 'msg' => '分数超过活动上限'));
                }
            }
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'频道或活动参数为空'));
        }
    }

    private function validatorGameScore($input){
        if (!isset($input['name']) || !$input['name']) {
            return '姓名未填';
        }
        if (!isset($input['user_name']) || !$input['user_name']) {
            return '姓名未填';
        }
        if (!isset($input['open_id']) || !$input['open_id']) {
            return '用户open_id未填';
        }
        if(!isset($input['ext_field1']) || !is_numeric($input['ext_field1'])){
            return '分数为上报';
        }
        if(!isset($input['mobile']) || !preg_match("/^1[34578]\d{9}$/i", $input['mobile'])){
            return false;
        }
        return false;
    }

    private function uploadActivitySignup($input ,$parameter_name=null) {
        $channel_id = Request::getPost('channel_id','int');
        $activity_id = Request::getPost('activity_id','int');

        if(isset($input) && !empty($input) && $channel_id && $activity_id){
            $sign_up = new ActivitySignup();
            $ext_fields = array();

            foreach ($input as $key => $value ){
                $allow = in_array($key,ActivitySignup::$main_parameter);
                if($allow){
                    $sign_up->$key = $value;
                }else{
                    $ext_fields[$key] = $value;
                }
            }
            $ext_fields_json = json_encode($ext_fields);
            $sign_up->ext_fields = $ext_fields_json;

            try{
                if($parameter_name!==null && isset($input[$parameter_name])){
                    $once_sign_up = ActivitySignup::apiGetActivitySignupByParameter($channel_id, $activity_id, $parameter_name, $input[$parameter_name]);
                    if(is_array($once_sign_up) ){//更新操作
                        if ($once_sign_up['ext_field1']<=$sign_up->ext_field1 && $sign_up->mobile!=13800000000) {
                            $sign_up->id = $once_sign_up['id'];
                            $sign_up->update();
                            $key = D::memKey('apiGetActivitySignupByParameter', ['channel_id' => $channel_id, 'activity_id' => $activity_id, $parameter_name => $input[$parameter_name]]);
                            MemcacheIO::delete($key);
                        }
                        //声音的战争中，通过特殊手机码不修改姓名和手机号
                        if ($once_sign_up['ext_field1']<=$sign_up->ext_field1 && $sign_up->mobile==13800000000){
                            $sign_up->id = $once_sign_up['id'];
                            $sign_up->name = $once_sign_up['name'];
                            $sign_up->mobile = $once_sign_up['mobile'];
                            $sign_up->update();
                            $key = D::memKey('apiGetActivitySignupByParameter', ['channel_id' => $channel_id, 'activity_id' => $activity_id, $parameter_name => $input[$parameter_name]]);
                            MemcacheIO::delete($key);
                        }
                        return 'update';
                    }else{
                        $sign_up->create();
                    }

                }else {
                    $sign_up->create();
                }
            }catch (Exception $e){
                return false;
            }
        }else{
            return false;
        }
        return true;
    }
}

