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
            if (time()>=strtotime('2017-3-1')){
                $this->jsonp(array('code'=>204,'msg'=>'报名已结束'));
            }

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

    /**
     * 作品列表接口 完成
     */
    public function getWorkListAction() {
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $page = Request::getQuery('page','int');
        $pagenum = Request::getQuery('pagenum','int');
        $work_type = Request::getQuery('work_type','int');//类型
        $order_by = Request::getQuery('order_by','string');//排序字段
        if($channel_id && $activity_id){
            $work_count = ActivitySignup::getWorkCount($channel_id,$activity_id,$work_type);
            $work_arr = ActivitySignup::getWorkList($channel_id,$activity_id,$page,$pagenum,$work_type,$order_by);
            $work_list = array();
            foreach ($work_arr as $signup) {
                $ext_fields = json_decode($signup['ext_fields'],true);
                $arr = array();
                $arr['work_id'] = $signup['id'];//作品id
                $arr['work_type'] = $signup['ext_field2'];//类型
                $arr['work_title'] = $signup['user_name'];
                $arr['work_author'] = $signup['name'];
                $arr['vote_number'] = $signup['ext_field1']?:0;//票数
                $arr['work_picture'] = strpos($ext_fields['work_picture'],'http')!==false?$ext_fields['work_picture']:cdn_url('image',$ext_fields['work_picture']);
                $arr['work_intro'] = isset($ext_fields['work_intro'])?$ext_fields['work_intro']:'';
                $arr['update_at'] = $signup['update_at'];
                $work_list[] = $arr;
            }

            $this->jsonp(array('code'=>200,'msg'=>'成功','sum'=>$work_count,'data'=>$work_list));
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }


    /**
     * 作品展示接口 完成
     */
    public function getWorkAction() {
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $work_id = Request::getQuery('work_id','int');
        if($channel_id && $activity_id){
            $signup = ActivitySignup::getWorkById($work_id);
            if($signup) {
                $ext_fields = json_decode($signup['ext_fields'], true);
                $work_ranking = ActivitySignup::getWorkRanking($channel_id, $activity_id);
                $ranking = array_search($work_id, $work_ranking);
                if($ranking===0){
                    $work_ranking = 1 ;
                }elseif($ranking == false){
                    $work_ranking = '未知';
                }else{
                    $work_ranking = $ranking + 1;
                }
                $attachment = ActivitySignupAttachment::getOneByExtId($ext_fields['movie_id']);
                $work = array();
                $work['work_picture'] = strpos($ext_fields['work_picture'],'http')!==false?$ext_fields['work_picture']:cdn_url('image', $ext_fields['work_picture']);
                if ($attachment){
                    $work['movie_mp4_url'] = isset($ext_fields['movie_url'])?$ext_fields['movie_url']:'';
                    $work['movie_m3u8_url'] = 'http:/'.$attachment->url;
                }
                $work['work_type'] = $signup['ext_field2'];//类型
                $work['work_author'] = $signup['name'];
                $work['vote_number'] = $signup['ext_field1'] ?: 0;//票数
                $work['work_number'] = isset($ext_fields['work_number'])?$ext_fields['work_number']:'';//作品编号
                $work['work_intro'] = $ext_fields['work_intro'];
                $work['work_title'] = $signup['user_name'];
                $work['update_at'] = $signup['update_at'];
                $work['work_ranking'] = $work_ranking;//排名
                $this->jsonp(array('code' => 200, 'msg' => '成功', 'data' => $work));
            }else{
                $this->jsonp(array('code' => 202, 'msg' => '作品不存在' ));
            }
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }

    /**
     * 作品展示接口,通过名称查询
     */
    public function getWorkByNameAction() {
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $name = Request::getQuery('name','string');
        if($channel_id && $activity_id){
            $signup = ActivitySignup::apiGetActivitySignupByParameter($channel_id, $activity_id, 'name', $name);
            if($signup) {
                $work = array();
                if (isset($signup['movie_id'])){
                    $attachment = ActivitySignupAttachment::getOneByExtId($signup['movie_id']);
                    if ($attachment){
                        $work['movie_mp4_url'] = isset($signup['movie_url'])?$signup['movie_url']:'';
                        $work['movie_m3u8_url'] = 'http:/'.$attachment->url;
                    }
                }
                $work['work_picture'] = strpos($signup['work_picture'],'http')!==false?$signup['work_picture']:cdn_url('image', $signup['work_picture']);
                $work['work_type'] = $signup['ext_field2'];//类型
                $work['work_author'] = $signup['name'];
                $work['vote_number'] = $signup['ext_field1'] ?: 0;//票数
                $work['work_number'] = isset($signup['work_number'])?$signup['work_number']:'';//作品编号
                $work['work_intro'] = $signup['work_intro'];
                $work['work_title'] = $signup['user_name'];
                $work['update_at'] = $signup['update_at'];
                $this->jsonp(array('code' => 200, 'msg' => '成功', 'data' => $work));
            }else{
                $this->jsonp(array('code' => 202, 'msg' => '作品不存在' ));
            }
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }

    /**
     * 作品展示接口,多id查询
     */
    public function getWorkByIdsAction() {
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $work_ids = Request::getQuery('work_ids','string');
        if($channel_id && $activity_id){
            $id_arr = explode(',',$work_ids);
            $work_arr = array();
            foreach ($id_arr as $key => $work_id){
                if ($work_id == null) break;
                $signup = ActivitySignup::getWorkById($work_id);
                if($signup){
                    $ext_fields = json_decode($signup['ext_fields'], true);
                    $attachment = ActivitySignupAttachment::getOneByExtId($ext_fields['movie_id']);
                    $work = array();
                    $work['work_picture'] = strpos($ext_fields['work_picture'],'http')!==false?$ext_fields['work_picture']:cdn_url('image', $ext_fields['work_picture']);
                    if ($attachment){
                        $work['movie_mp4_url'] = isset($ext_fields['movie_url'])?$ext_fields['movie_url']:'';
                        $work['movie_m3u8_url'] = 'http:/'.$attachment->url;
                    }
                    $work['work_type'] = $signup['ext_field2'];//类型
                    $work['work_author'] = $signup['name'];
                    $work['vote_number'] = $signup['ext_field1'] ?: 0;//票数
                    $work['work_number'] = isset($ext_fields['work_number'])?$ext_fields['work_number']:'';//作品编号
                    $work['work_intro'] = $ext_fields['work_intro'];
                    $work['work_title'] = $signup['user_name'];
                    $work['update_at'] = $signup['update_at'];
                    $work_arr[] = $work;
                }
            }
            if (empty($work_arr)){
                $this->jsonp(array('code' => 202, 'msg' => '作品不存在' ));
            }else{
                $this->jsonp(array('code' => 200, 'msg' => '成功', 'data' => $work_arr));
            }
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }

    /**
     * 投票接口 完成
     */
    public function upWorkAction() {//需要获取ip
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $work_id = Request::getQuery('work_id','int');
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
            $key = 'cztv::activity_signup::ip::'.$channel_id.'::'.$activity_id.'::'.$ip;
            $up_times = RedisIO::get($key);
            if(!$up_times){
                $time = strtotime(date("Y-m-d 00:00:00",strtotime("+1 day")))-time();
                RedisIO::set($key,1,$time);
            }elseif($up_times>=10){
                $this->jsonp(array('code'=>202,'msg'=>'此ip今天已经投过10票'));
            }else{
                RedisIO::incr($key);
            }
            $signup = ActivitySignup::upWorkById($work_id);
            $this->jsonp(array('code'=>200,'msg'=>'成功'));
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }

    /**
     * 搜索接口
     */
    public function searchWorkAction() {//可能需要新的搜索引擎
        $channel_id = Request::get('channel_id','int');
        $activity_id = Request::get('activity_id','int');
        $page = Request::get('page','int');
        $pagenum = Request::get('pagenum','int');
        $search_post = Request::getPost();
        if($channel_id && $activity_id){
            $key_words = isset($search_post['key_words'])?$search_post['key_words']:'';
            $key_words = SolrEngine::f($key_words);
            if($key_words) {
                $solr = $this->getDI()->getShared('solr.activitysignup');
                $data = SolrEngine::searchActivity($solr, $channel_id, $activity_id, $key_words, $page , $pagenum);
                $work_list = array();
                foreach ($data['rs'] as $k=>$signup) {
                    $ext_fields = json_decode($signup['ext_fields'],true);
                    $arr = array();
                    $arr['work_id'] = $signup['id'];//作品id
                    $arr['work_type'] = $signup['ext_field2'];//类型
                    $arr['work_title'] = $signup['user_name'];
                    $arr['work_author'] = $signup['author_name'];
                    $arr['vote_number'] = $signup['ext_field1']?:0;//票数
                    $arr['work_picture'] = strpos($ext_fields['work_picture'],'http')!==false?$ext_fields['work_picture']:cdn_url('image',$ext_fields['work_picture']);
                    $work_list[] = $arr;
                }
                $this->jsonp(array('code'=>200,'msg'=>'成功','sum'=>$data['count'],'data'=>$work_list));
            }else{
                $this->jsonp(array('code'=>201,'msg'=>'搜索内容为空'));
            }
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }

    public function commitBlinddataAction() {
        $channel_id = Request::getPost('channel_id','int');
        $activity_id = Request::getPost('activity_id','int');
        if($channel_id && $activity_id){
            $return = $this->saveBlinddata($channel_id, $activity_id);
            if($return){
                $key = 'cztv::activity_signup::blindid::'.$channel_id.'::'.$activity_id.'::'.$return->id;
                $times = RedisIO::get($key);
                if(!$times){
                    $activity = Activity::apiGetActivityById($channel_id,$activity_id);
                    $send_return = Message::sendCodeBlind($return->mobile,$return->id, json_decode($activity['message_template_params'],true) );
                    if($send_return=='success'){
                        RedisIO::set($key , 1 , strtotime(date("Y-m-d 00:00:00",strtotime("+1 day")))-time());
                    }
                }elseif($times>10){

                }else{
                    $activity = Activity::apiGetActivityById($channel_id,$activity_id);
                    $send_return = Message::sendCodeBlind($return->mobile,$return->id, json_decode($activity['message_template_params'],true));
                    if($send_return=='success'){
                        RedisIO::incr($key);
                    }
                }
                $this->jsonp(array('code'=>200,'msg'=>'成功' , 'number'=>$return->id));
            }else{
                $this->jsonp(array('code'=>202,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }

    public function uploadBlinddateAction() {
        $channel_id = Request::getPost('channel_id','int');
        $activity_id = Request::getPost('activity_id','int');
        if($channel_id && $activity_id){
            $return = $this->saveBlinddata($channel_id, $activity_id);
            if($return){
                $this->jsonp(array('code'=>200,'msg'=>'成功'));
            }else{
                $this->jsonp(array('code'=>202,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }

    public function xqWinAction() {
        $channel_id = Request::getPost('channel_id','int');
        $activity_id = Request::getPost('activity_id','int');
        if($channel_id && $activity_id) {
            $input = Request::getPost();

            $json = array();//额外字段
            $json['birth_year'] = $input['birth_year'];//出生年份
            $json['birth_month'] = $input['birth_month'];//出生月份
            $json['birth_day'] = $input['birth_day'];//出生日期
            $json['height'] = $input['height'];//身高
            $json['srecord'] = $input['srecord'];//学历
            $json['job'] = $input['job'];//职业 这个需要对应，如果可以改为文字
            $json['position'] = $input['position'];//职务
            $json['constellation'] = $input['constellation'];//星座
            $json['hhouse'] = $input['hhouse'];//住房
            $json['isheathy'] = $input['isheathy'];//健康状况 0健康 1不健康
            $json['body'] = $input['body'];//体型 这个需要对应，如果可以改为文字
            $json['marriage'] = $input['marriage'];//婚姻状况 这个需要对应，如果可以改为文字
            $json['csex'] = $input['csex'];//子女性别 0男 1女
            $json['cbelong'] = $input['cbelong'];//子女归属 这个需要对应，如果可以改为文字
            $json['work_place'] = $input['workProvince'].$input['workCity'].$input['workArea'];//工作地
            $json['home_place'] = $input['hometownProvince'].$input['hometownCity'].$input['hometownArea'];//户口所在地
            $json['home'] = $input['home'];//住家地点
            $json['intro'] = $input['intro'];//自我介绍
            $json['sage'] = $input['sage1'].'~'.$input['sage2'];//年龄范围
            $json['sheight'] = $input['sheight'];//身高范围
            $json['ssrecord'] = $input['ssrecord'];//学历要求 这个需要对应，如果可以改为文字
            $json['sjob'] = $input['sjob'];//职业要求 这个需要对应，如果可以改为文字
            $json['spay'] = $input['spay'];//收入要求 这个需要对应，如果可以改为文字
            $json['shouse'] = $input['shouse'];//住房要求 这个需要对应，如果可以改为文字
            $json['schildren'] = $input['schildren'];//子女要求 1:无2:有
            $json['splace'] = $input['splace'];//户口要求
            $json['smarriage'] = $input['smarriage'];//婚姻要求 这个需要对应，如果可以改为文字
            $json['sother'] = $input['sother'];//其它要求
            $json['sn'] = $input['sn'];//身份证号
            $json['company'] = $input['company'];//工作单位
            $json['faddress'] = $input['faddress'];//家庭住址
            $json['graduate'] = $input['graduate'];//毕业院校
            $json['profession'] = $input['profession'];//专业
            $json['email'] = $input['email'];//E-MALL
            $json['qq'] = $input['qq'];//QQ
            $json['laddress'] = $input['laddress'];//通信地址
            $json['hcar'] = $input['hcar'];//备车情况 这个需要对应，如果可以改为文字
            $json['fmember'] = $input['fmember'];//家庭成员
            $message=[];
            $json['work_picture'] = $this->validateAndUpload($message);//照片上传 $input['img']
            $json = json_encode($json);

            $data = array();//主要字段
            $data['channel_id'] = $channel_id;
            $data['activity_id'] = $activity_id;
            $data['mobile'] = $input['tel'];//手机号码
            $data['name'] = $input['name'];//姓名
            $data['user_id'] = $input['sex'];//性别
            $data['user_name'] = $input['pay'];//年收入 0:保密,1:3万以下 等等
            $data['create_at'] = time();
            $data['update_at'] = time();
            $data['ext_field1'] = $input['weight'];//体重
            $data['ext_field2'] = (int)date('Y')-(int)$input['birth_year']+1;//年龄
            $data['ext_fields'] = $json;

            $activity_signup = ActivitySignup::findOneByMobile($channel_id,$activity_id,$data['mobile']);
            if(!$activity_signup){
                $activity_signup = new ActivitySignup();
                $return = $activity_signup->createActivitySignup($data);
            }else{
                $activity_signup->assign();
                $return = $activity_signup->update();
            }
            if($return){
                $this->jsonp(array('code'=>200,'msg'=>'成功'));
            }else{
                $this->jsonp(array('code'=>202,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }

    /**
     * 微电影大赛接口
     */
    public function microFilmAction() {
        $channel_id = Request::getPost('channel_id','int');
        $activity_id = Request::getPost('activity_id','int');
        if($channel_id && $activity_id) {
            $input = Request::getPost();

            $json = array();//额外字段
            $json['Unit'] = $input['Unit'];//申报单位
            $json['video_scale'] = $input['logo']==3?'16:9':'4:3';//视频比例
            $json['work_picture'] = $input['img_url'];//上传剧照
            $json['movie_url'] = $input['movie_url'];//上传视频
            $json['daoyan'] = $input['daoyan'];//导演
            $json['bianju'] = $input['bianju'];//编剧
            $json['sheying'] = $input['sheying'];//摄影
            $json['nan'] = $input['nan'];//男主角
            $json['nv'] = $input['nv'];//女主角
            $json['intro'] = $input['intro'];//内容简介
            $json = json_encode($json);

            $data = array();//主要字段
            $data['channel_id'] = $channel_id;
            $data['activity_id'] = $activity_id;
            $data['mobile'] = $input['mobile'];//联系人电话
            $data['name'] = $input['username'];//联系人姓名
            $data['user_id'] = null;
            $data['user_name'] = $input['title'];//作品名称
            $data['create_at'] = time();
            $data['update_at'] = time();
            $data['ext_field1'] = null;
            $data['ext_field2'] = null;
            $data['ext_fields'] = $json;

            $activity_signup = new ActivitySignup();
            $return = $activity_signup->createActivitySignup($data);
            if($return){
                $this->jsonp(array('code'=>200,'msg'=>'上传成功'));
            }else{
                $this->jsonp(array('code'=>202,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>201,'msg'=>'参数为空'));
        }
    }

    /**
     * 我的年味儿报名接口
     */
    public function yearTasteAction() {
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $input = Request::getPost();
        if(!empty($input) && $channel_id && $activity_id){
            $input = $this->xss_filter($input);
            if(!$this->filterInput($input)){
                $this->jsonp(array('code'=>2004,'msg'=>'作品报名信息不全'));
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

            $json = array();//额外字段
            $message = [];
            $json['movie_url'] = $input['movie_url']?'http://yf.ugc.v.cztv.com'.$input['movie_url']:'';//上传视频相对路径
            $json['movie_id'] = $input['movie_id'];//上传视频附件id
            $json['work_intro'] = $input['work_intro'];//作品简介
            $json = json_encode($json);

            $data = array();
            $data['channel_id'] = $channel_id;
            $data['activity_id'] = $activity_id;
            $data['mobile'] = intval($input['mobile']);//mobile
            $data['name'] = $input['work_author'];//name
            $data['user_name'] = $input['work_title'];//user_name
            $data['create_at'] = time();
            $data['update_at'] = time();
            $data['ext_field1'] = 0;
            $data['ext_field2'] = 1;
            $data['ext_fields'] = $json;

            $activity_signup = new ActivitySignup();
            $return = $activity_signup->createActivitySignup($data);
            if($return){
                $key = 'cztv::activity_signup::work_ranking::' . $channel_id . '::' . $activity_id . '::' . 0;
                MemcacheIO::delete($key);
                $this->jsonp(array('code'=>200,'msg'=>'成功'));
            }else{
                $this->jsonp(array('code'=>2002,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'频道或活动参数为空'));
        }
    }

    /**
     * 一封家书报名接口
     */
    public function homeLetterAction() {
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $input = Request::getPost();
        if(!empty($input) && $channel_id && $activity_id){
            $input = $this->xss_filter($input);
            if(!$this->filterInput($input)){
                $this->jsonp(array('code'=>2004,'msg'=>'作品报名信息不全'));
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

            if(isset($input['work_type']) && $input['work_type']) {
                if ($input['work_type']==1 && !isset($input['work_picture'])) {//全家福
                    $this->jsonp(array('code'=>2006,'msg'=>'全家福图片地址未提交'));
                }
                if ($input['work_type']==2 && !isset($input['work_intro']) && strlen($input['work_intro'])<=400*3) {//家书
                    $this->jsonp(array('code'=>2007,'msg'=>'家书内容未提交或过长'));
                }
            }else {
                $this->jsonp(array('code'=>2005,'msg'=>'必须选择作品类型'));
            }

            $json = array();//额外字段
            $message = [];
            $json['work_picture'] = isset($input['work_picture'])?$input['work_picture']:'';//作品上传地址
            $json['work_intro'] = $input['work_intro'];
            $json = json_encode($json);

            $data = array();
            $data['channel_id'] = $channel_id;
            $data['activity_id'] = $activity_id;
            $data['mobile'] = intval($input['mobile']);//mobile
            $data['name'] = $input['work_author'];//name
            $data['user_id'] = $input['work_type'];//类型：1为全家福，2为家书
            $data['user_name'] = $input['work_title'];//user_name
            $data['create_at'] = time();
            $data['update_at'] = time();
            $data['ext_field2'] = $input['work_type'];//类型：1为全家福，2为家书
            $data['ext_fields'] = $json;

            $activity_signup = new ActivitySignup();
            $return = $activity_signup->createActivitySignup($data);
            if($return){
                $key = 'cztv::activity_signup::work_ranking::' . $channel_id . '::' . $activity_id . '::' . 0;
                MemcacheIO::delete($key);
                $this->jsonp(array('code'=>200,'msg'=>'成功'));
            }else{
                $this->jsonp(array('code'=>2002,'msg'=>'保存失败'));
            }
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'频道或活动参数为空'));
        }
    }

    /**
     * 视频回调接口
     */
    public function getVideoFileCallbackAction(){
        header('Content-type: application/json');
        $data_stream = file_get_contents('php://input', 'r');
        $msg = json_decode($data_stream,true);
        $data = array();
        $data['ext_id'] = $msg['result'][0]['user_token'];//视频唯一id
        $data['url'] = $msg['result'][0]['publish_url'];//视频地址
        $attachment = new ActivitySignupAttachment();
        $attachment->createActivitySignupAttachment($data);
        exit;
    }

    /**
     * 文件上传接口
     */
    public function uploadPictureAction() {
        $message=[];
        $path = $this->validateAndUpload($message);
        if($path!=''){
            $this->jsonp(array('code'=>200,'msg'=>'上传成功','url'=>$path));
        }else{
            $this->jsonp(array('code'=>201,'msg'=>$message[0]));
        }
    }

    /**
     * 获取活动编号
     */
//    public function getNumberAction() {
//        $number = RedisIO::get(self::AcivityNumber);
//        if(!$number){
//            $number = 1;
//            RedisIO::set(self::AcivityNumber,$number+1);
//        }else{
//            RedisIO::incr(self::AcivityNumber);
//        }
//        $this->jsonp(array('code'=>200,'number'=>date('Ymd').$number));
//    }

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
     * @param $channel_id
     * @param $activity_id
     */
    public function saveBlinddata($channel_id, $activity_id)
    {
        $input = Request::getPost();

        $json = array();//额外字段
        $json['r_name'] = $input['r_name'];//姓
        $json['r_nickname'] = $input['r_nickname'];//名
        $json['b_year'] = $input['year'];//年份
        $json['b_month'] = $input['month'];//月份
        $json['b_date'] = $input['date'];//日期
        $json['r_title'] = $input['r_title'];//籍贯
        $json['r_height'] = $input['r_height'];//身高
        $json['r_degree'] = $input['r_degree'];//学历
        $json['job'] = $input['r_param5'];//职业
        $json['work_place'] = $input['r_param2'];//工作地
        $json['merry'] = $input['r_param4'];//婚姻状况
        $json['weixin'] = $input['r_msn'];//微信号
        $json['standard'] = $input['r_param6'];//择偶标准
        $json['introduce'] = $input['r_content'];//自我介绍
        $json['pay_number'] = $input['r_pay'];//消费额度
        $message = [];
//        $json['work_picture'] = $this->validateAndUpload($message);//照片上传
        $json['work_picture'] = $input['imgs'];
        $json = json_encode($json);

        $data = array();//主要字段
        $data['channel_id'] = $channel_id;
        $data['activity_id'] = $activity_id;
        $data['mobile'] = $input['r_phone'];//手机号码
        $data['name'] = $input['r_name'] . $input['r_nickname'];//姓名
        $data['user_id'] = $input['r_sex'];//性别 1为男 2为女
        $data['user_name'] = $input['r_param1'];//年收入
        $data['create_at'] = time();
        $data['update_at'] = time();
        $data['ext_field1'] = $input['r_weight'];//体重
        $data['ext_field2'] = (int)date('Y') - (int)$input['year'] + 1;//年龄
        $data['ext_fields'] = $json;

        $activity_signup = ActivitySignup::findOneByMobile($channel_id, $activity_id, $data['mobile']);
        if (!$activity_signup) {
            $activity_signup = new ActivitySignup();
            $return = $activity_signup->createActivitySignup($data);
        } else {
            $activity_signup->assign($data);
            $return = $activity_signup->update();
        }

        if($return) {
            return $activity_signup;
        }else{
            return false;
        }
    }

    public function uploadGameScoreAction() {
        $channel_id = Request::getQuery('channel_id');
        $activity_id = Request::getQuery('activity_id');
        $input = Request::getPost();
        if(!empty($input) && $channel_id && $activity_id && $this->validatorGameScore($input)){
            $activity = Activity::apiGetActivityById($channel_id,$activity_id);
            if($activity){
                if($activity['start_time']<=time()&&$activity['end_time']>=time()){
                    //唯一性验证,通用方法加参数解决
                    if ($activity['params1'] >= $input['ext_field1']) {//额外活动条件
                        $input['channel_id'] = $channel_id;
                        $input['activity_id'] = $activity_id;
                        $input['create_at'] = time();
                        $input['update_at'] = time();
                        $input['status'] = 1;//默认是否通过，以后要后台配置
                        $return = $this->uploadActivitySignup($input,'user_name');
                        if ($return) {
                            if($return!='update') {
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

                }else{
                    $this->jsonp(array('code'=>2002,'msg'=>'不在活动时间内'));
                }
            }
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'参数为空'));
        }
    }

    private function validatorGameScore($input){
        if(!isset($input['mobile']) || !preg_match("/^1[34578]\d{9}$/i", $input['mobile'])){
            return false;
        }
        if(!isset($input['name']) || !$input['name']){
            return false;
        }
        if(!isset($input['user_name']) || !$input['user_name']){
            return false;
        }
        if(!isset($input['ext_field1']) || !is_numeric($input['ext_field1'])){
            return false;
        }

        return true;
    }

    public function getRankingListAction() {
        $channel_id = Request::getQuery('channel_id');
        $activity_id = Request::getQuery('activity_id');
        $number = Request::getQuery('number')?:50;
        $page = Request::getQuery('page')?:1;
        $parameter_name = Request::getQuery('parameter_name')?:'ext_field1';
        if($channel_id && $activity_id){
            $activity_arr = explode(',', $activity_id);
            foreach ($activity_arr as $key=>$a_id) {
                if (!is_numeric($a_id)) {
                    unset($activity_arr[$key]);
                }
            }
            $data = ActivitySignup::apiGetActivitySignupRankingListByParameter($channel_id,$activity_arr,$parameter_name,$number, $page);
            $this->jsonp(array('code' => 200, 'msg' => '成功', 'data' => $data));
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'参数为空'));
        }
    }

    /**
     * 尝试通用接口
     * 获取活动接口，活动id可为多个,用户id可不传
     * @author 张亦弛
     * @param channel_id
     * @param activity_id
     * @param user_id
     */
    public function getActivityAction(){
        $channel_id = Request::getQuery('channel_id');
        $activity_id = Request::getQuery('activity_id');
        $open_id = Request::getQuery('open_id');
        if($channel_id && $activity_id) {
            $activity_arr = explode(',', $activity_id);
            $message_arr = [];
            foreach ($activity_arr as $a_id) {
                if (is_numeric($a_id)) {
                    $message_arr[$a_id] = [];
                    $activity = Activity::apiGetActivityById($channel_id, $a_id);
                    $message_arr[$a_id]['activity'] = $activity;
                    if ($activity && $open_id) {
                        $sign_up = ActivitySignup::apiGetActivitySignupByParameter($channel_id, $a_id, 'user_name', $open_id);
                        $message_arr[$a_id]['sign_up'] = $sign_up ?: [];
                    }

                }
            }

            $this->jsonp(array('code' => 200, 'msg' => '成功', 'data' => $message_arr));
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'参数为空'));
        }
    }

    /**
     * 通用报名单保存接口，其余活动限制在对应活动中添加
     * @author 张亦弛
     * @param array $input 提交数组
     * @param string $parameter_name 限制唯一性字段
     * @return bool
     */
    private function uploadActivitySignup($input ,$parameter_name=null) {
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');

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

    /**
     * 公益短片详情获取接口
     */
    public function getmicroFilmAction() {
        $channel_id = Request::getQuery('channel_id','int');
        $activity_id = Request::getQuery('activity_id','int');
        $work_id = Request::getQuery('work_id','int');
        if($channel_id && $activity_id){
            // 2019-07-05 修改 60-11条，51与27-6条。
            if (in_array($activity_id, [51,27,59])) {
                switch ($activity_id)
                {
                    case 27:
                        $work_arr = ActivitySignup::getWorkList($channel_id,$activity_id,1,6,null, null);
                        break;
                    case 51:
                        $work_arr = ActivitySignup::getWorkList($channel_id,$activity_id,1,6,null, null);
                        break;
                    default:
                        $this->jsonp(array('code' => 2002, 'msg' => '作品不存在' ));
                }
                $ids = [];
                foreach ($work_arr as $signup) {
                    $ids[] = $signup['id'];
                }
                if (in_array($work_id, $ids)) {
                    $signup = ActivitySignup::getWorkById($work_id);
                    if($signup) {
                        $ext_fields = json_decode($signup['ext_fields'], true);

                        $attachment = ActivitySignupAttachment::getOneByExtId($ext_fields['movie_id']);
                        $work = array();
                        $work['work_picture'] = strpos($ext_fields['work_picture'],'http')!==false?$ext_fields['work_picture']:cdn_url('image', $ext_fields['work_picture']);
                        $work['movie_mp4_url'] = isset($ext_fields['movie_url'])?$ext_fields['movie_url']:'';
                        if ($attachment){
                            $work['movie_m3u8_url'] = 'http:/'.$attachment->url;
                        }
                        $work['title'] = $signup['user_name'];//作品名称
                        $work['unit'] = $ext_fields['unit'];//申报单位
                        $work['director'] = $signup['name'];//导演
                        $work['screenwriter'] = $ext_fields['screenwriter'];
                        $work['cameraman'] = $ext_fields['cameraman'];
                        $work['actor'] = $ext_fields['actor'];
                        $work['actress'] = $ext_fields['actress'];
                        $work['work_intro'] = $ext_fields['intro'];
                        $this->jsonp(array('code' => 200, 'msg' => '成功', 'data' => $work));
                    }else{
                        $this->jsonp(array('code' => 2002, 'msg' => '作品不存在' ));
                    }
                } else {
                    $this->jsonp(array('code' => 2002, 'msg' => '作品不存在' ));
                }
            } else {
                $signup = ActivitySignup::getWorkById($work_id);
                if($signup) {
                    $ext_fields = json_decode($signup['ext_fields'], true);

                    $attachment = ActivitySignupAttachment::getOneByExtId($ext_fields['movie_id']);
                    $work = array();
                    $work['work_picture'] = strpos($ext_fields['work_picture'],'http')!==false?$ext_fields['work_picture']:cdn_url('image', $ext_fields['work_picture']);
                    $work['movie_mp4_url'] = isset($ext_fields['movie_url'])?$ext_fields['movie_url']:'';
                    if ($attachment){
                        $work['movie_m3u8_url'] = 'http:/'.$attachment->url;
                    }
                    $work['title'] = $signup['user_name'];//作品名称
                    $work['unit'] = $ext_fields['unit'];//申报单位
                    $work['director'] = $signup['name'];//导演
                    $work['screenwriter'] = $ext_fields['screenwriter'];
                    $work['cameraman'] = $ext_fields['cameraman'];
                    $work['actor'] = $ext_fields['actor'];
                    $work['actress'] = $ext_fields['actress'];
                    $work['work_intro'] = $ext_fields['intro'];
                    $this->jsonp(array('code' => 200, 'msg' => '成功', 'data' => $work));
                }else{
                    $this->jsonp(array('code' => 2002, 'msg' => '作品不存在' ));
                }
            }
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'频道号或活动id为空'));
        }
    }
}

