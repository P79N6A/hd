<?php

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/6/20
 * Time: 19:42
 */
class LtvugcLiveController extends \BackendBaseController
{
    const ANDROID = 2;
    const IOS = 1;
    
    /*
     * @desc 用户支付记录表信息
     *
     * */
    public function listPaymentAction()
    {
        $conditions = array();
        if($mess = Request::getPost())
        {
            if(isset($mess['keyword']) && $mess['keyword'])
            {
                $c = "Payment.order_no like '%{$mess['keyword']}%'";
                array_push($conditions,$c);
            }
            if(isset($mess['type']) && intval($mess['type'])>=0)
            {

                $c = "Payment.state = '{$mess['type']}'";
                array_push($conditions,$c);
            }
        }
        $chargeway = array('weixinpay','alipay','applepay');
        $data = Payment::findAll($conditions);
        View::setVars(compact('data','mess','chargeway'));
    }
    
    /*
     * @desc 主播点播文件列表
     * */
    public function anchorMediaFileListAction()
    {
        $stream_id = Request::getQuery('stream_id','int');
        $show_all = Request::getQuery('show_all','int',0);
        $show_all = $show_all == 1?true:false;
        $conditions = array();
        if(!$show_all)
        {
            $c = "UgcVideoFile.video_url != ''";
            array_push($conditions,$c);
        }
        $mess = [];
        if($stream_id)
        {
            $data = UgcVideoFile::findByStreamId($stream_id);
        }
        else
        {
            if($mess = Request::getPost())
            {
                if(isset($mess['keyword']) && $mess['keyword'])
                {
                    $c = "Admin.mobile like '%{$mess['keyword']}%' ";
                    $c .= " or Admin.name like '%{$mess['keyword']}%' ";
                    $c .= " or UgcStream.stream = '{$mess['keyword']}'";
                    array_push($conditions,$c);
                }
            }

            $data = UgcVideoFile::findAll($conditions);
        }
        View::setVars(compact('data','mess','show_all'));
    }

    /*
     * @desc 主播详情
     * */
    public function anchorInfoAction()
    {
        $admin_id = Request::getQuery('id');
        $anchor = Admin::getAnchorInfo($admin_id);

        $groupInfo = AdminGroup::findOne($anchor->adminExt->ugc_group_id);
        View::setMainView('layouts/add');
        View::setvars(compact('anchor','groupInfo'));
    }



    /*
     * @desc 主播列表
     *
     * */
    public function anchorListAction()
    {
        $conditions = array();
        if($mess = Request::getPost())
        {
            if(isset($mess['keyword']) && $mess['keyword'])
            {
                $c = "Admin.mobile like '%{$mess['keyword']}%' ";
                $c .= " or Admin.mobile like '%{$mess['keyword']}%' ";
                $c .= " or UgcStream.stream = '{$mess['keyword']}'";
                array_push($conditions,$c);
            }
        }
        $data = AdminExt::findAnchor($conditions);
        View::setVars(compact('data','mess'));
    }
    /*
     * @desc 主播开通
     * */
    public function editAction()
    {
        $messages = [];
        if(Request::isPost())
        {
            //配置信息
            $ltvUgcConfig = app_site()->ltvugc_config;
            //修改主播信息
            $admin_id = Request::getQuery('admin_id','int',0);
            $stream = UgcStream::getOneByAdminId($admin_id);
            if(!empty($stream))
            {
                $messages[] = "主播直播流已经存在不能重复生成";

            }else{
                $postData = Request::getPost();
                if($postData['ugc_group_id'] && $postData['is_anchor']) {
                    $anchor = AdminExt::findFirstOrFail($postData['admin_id']);
                    $anchor->is_anchor = $postData['is_anchor'];
                    $anchor->ugc_group_id = $postData['ugc_group_id'];
                    $anchor->save();
                    //读取UGC分组配置信息
                    $rate = 540;
                    //添加流信息
                    $admin_id = $postData['admin_id'];
                    $stream_name = $this->generalStream($admin_id);
                    $streamData = $this->generalStreamData($admin_id,$stream_name,$rate,$ltvUgcConfig);
                    $modelUgcStream = new UgcStream();
                    $streamid = $modelUgcStream->saveGetId($streamData);
                    if ($streamid) {
                        $messages[] = Lang::_('success');
                    } else {
                        $messages[] = $modelUgcStream->getMessage();
                    }
                }else{
                    $messages[] = Lang::_('invalid params');
                }
            }
        }
        $grouplist = AdminGroup::getAll();
        $adminlist = AdminExt::findUnAnchor()->toArray();

        View::setMainView('layouts/add');
        View::setvars(compact('messages','grouplist','adminlist'));
    }

    public function auditAction()
    {
        $admin_id=$this->request->getQuery("admin_id","int");
        $is_anchor = $this->request->getQuery('is_anchor','int');
        $modelAdminExt = AdminExt::findFirstOrFail(array("admin_id=$admin_id"));
        $arr=array('code'=>'error','msg'=>Lang::_('failed'));
        if($modelAdminExt)
        {
            $modelAdminExt->is_anchor = $is_anchor;
            $modelAdminExt->save();
            /*更新主播Redis键值对*/
            $redis_anchor_key = D::redisKey('ltv_anchor',$admin_id);
            if(RedisIO::exists($redis_anchor_key))
            {
                $arr_anchor = json_decode(RedisIO::get($redis_anchor_key),true);
            }else{
                $arr_anchor = Admin::getAnchorInfo($admin_id);
            }
            $arr_anchor['is_anchor'] = $is_anchor;
            RedisIO::set($redis_anchor_key,json_encode($arr_anchor));
            $arr=array('code'=>200,'msg' => Lang::_('success'));
        }
        echo json_encode($arr);
        exit;
    }

    /*
     * @desc 禁流
     * */
    public function pausestreamAction()
    {
        $ugcConfig = app_site()->ugc_config;
        $stream = Request::getQuery('stream');   //流名称
        $model_stream = UgcStream::getStreamByname($stream);
        $starttime = time();
        $access_key = $ugcConfig['ugc_yf_api_accesskey'];
        $secret_key = $ugcConfig['ugc_yf_api_secretkey'];
        $auth = new YfAuth($access_key, $secret_key);
        $path = '/api/ban_rtmp_url/set';
        $rtmpUrl = $ugcConfig['ugc_yf_rtmp_host']."/live/{$stream}_540p";
        $endtime = strtotime('2026-01-01 00:00:00');
        $body = array(
            'RtmpUrl' => $rtmpUrl,
            'StartBanSec' => $starttime,
            'EndBanSec' => $endtime);
        $token = $auth->getAccessToken($path, $body);
        $headers = [
            "accessToken:$token",
            "Connection:keep-alive",
            "Cache-Control:max-age=0"
        ];
        $url = $ugcConfig['ugc_yf_api_host'].$path;
        $strret = F::crulYFApiRequest($url,$headers,$body);
        $ret = json_decode($strret,true);
        if(boolval($ret['Ok']))
        {
            $model_stream->is_pause = 1;
            $model_stream->save();
            $arr=array('code'=>200,'msg' => Lang::_('success'));
        }
        else{
            $arr=array('code'=>'error','msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
    }

    public function gtTaskListAction(){
        $channel_id = Session::get('user')->channel_id;
        $data = TaskGetui::findAll($channel_id);
        View::setVars(compact('data'));
    }

    public function addTaskAction()
    {
        $channel_id = Session::get('user')->channel_id;
        if(Request::isPost())
        {
            $gtData = Request::getPost();

            $gtData['channel_id'] = $channel_id;
            $gtData['admin_id'] = 0;
            $gtData['getui_range'] = '1'; //全量推送;


            $ugc_config = app_site()->ugc_config;
            $gt_params = array(
                'gt_ios_key'=>$ugc_config['ugc_gt_ios_appkey'],
                'gt_ios_secrect'=>$ugc_config['ugc_gt_ios_secret'],
                'gt_ios_appid'=>$ugc_config['ugc_gt_ios_appid'],
                'gt_android_key'=>$ugc_config['ugc_gt_android_appkey'],
                'gt_android_secrect'=>$ugc_config['ugc_gt_android_secret'],
                'gt_android_appid'=>$ugc_config['ugc_gt_android_appid'],
            );
            $title = Request::getPost('mess_title');
            $push_data = array(
                'enterType' => Request::getPost('getui_type'),
                'videoId'=>Request::getPost('mess_id'),
                'url'=>Request::getPost('mess_url'),
                'videoName'=>Request::getPost('mess_body')
            );

            $getui = new GeTuiTask($gt_params,$title,$push_data,array('android_push_clients'=>array('4d6cbfd318218f4379444030ebf21437')));
            $ret_android = $getui->push_list_android(); 
            
            $arr_ret_android = json_decode($ret_android);
            
            $gtData['start_time'] = time();
            $gtData['getui_type'] = self::ANDROID;
            $gtData['memo'] = '安卓全量推送';
            //TaskGetui::createRecord($gtData); //添加个推记录
            die();
//            $ret_ios= $getui->push_all_ios();
//            $gtData['start_time'] = time();
//            $gtData['getui_type'] = self::IOS;
//            $gtData['memo'] = '安卓全量推送';
//            TaskGetui::createRecord($gtData); //添加个推记录
        }
        View::setMainView('layouts/add');
        View::setvars(compact('messages'));
    }


    public function restorestreamAction()
    {
        $ugcConfig = app_site()->ugc_config;
        $stream = Request::getQuery('stream');     //流名称
        $model_stream = UgcStream::getStreamByname($stream);
        $access_key = $ugcConfig['ugc_yf_api_accesskey'];
        $secret_key = $ugcConfig['ugc_yf_api_secretkey'];
        $auth = new YfAuth($access_key, $secret_key);

        $rtmpUrl = $ugcConfig['ugc_yf_rtmp_host']."/live/{$stream}_540p";
        $url = "/api/ban_rtmp_url/delete?rtmp_url={$rtmpUrl}";
        $token = $auth->getAccessToken($url);
        $headers = [
            "accessToken:$token",
            "Connection:keep-alive",
            "Cache-Control:max-age=0"
        ];
        $url = $ugcConfig['ugc_yf_api_host'].$url;
        $strret = F::crulYFApiRequest($url,$headers);
        $ret = json_decode($strret,true);
        if(boolval($ret['Ok']))
        {
            $model_stream->is_pause = 0;
            $model_stream->save();
            $arr=array('code'=>200,'msg' => Lang::_('success'));
        }
        else{
            $arr=array('code'=>'error','msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
    }


    /*
     *@desc 推流管理
     * */
    public function anchorRtmpListAction()
    {
        $conditions = array();
        $channel_id = Session::get('user')->channel_id;
        array_push($conditions,"Admin.channel_id = $channel_id");
        if($mess = Request::getPost())
        {
            if(isset($mess['keyword']) && $mess['keyword'])
            {
                $c = "Admin.mobile like '%{$mess['keyword']}%'";
                $c .= " OR UgcStream.stream = '{$mess['keyword']}'";
                array_push($conditions,$c);
            }
        }

        $data = UgcStream::getAll($conditions);
        View::setVars(compact('data','mess'));
    }
    /*
     * @desc 添加主播的流信息（推流地址、流名称 ）
     * */
    public function addStreamAction()
    {
        $admin_id = Request::getQuery('admin_id','int',0);
        $stream = UgcStream::getOneByAdminId($admin_id);
        if(!empty($stream))
        {
            echo json_encode(array('msg'=>'主播直播流已经存在不能重复生成'));
            exit;
        }
        $ltvUgcConfig = app_site()->ltvugc_config;
        $stream = $this->generalStream($admin_id);
        $rate = 540;
        $stream_data = $this->generalStreamData($admin_id,$stream,$rate,$ltvUgcConfig);


        $modelUgcStream = new UgcStream();
        $ret = $modelUgcStream->saveGetId($stream_data);
        if($ret)
        {
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
    }

    private function generalStream($admin_id)
    {
        //流名称算法md5($admin_id+microtime());
        return md5(strval($admin_id).strval(microtime()));
    }
    private function generalStreamData($admin_id,$stream_name,$rate,$ltvUgcConfig)
    {

        $streamData = array(
            "admin_id" => $admin_id,
            "stream" => $stream_name,
            "hls_url"  => "{$ltvUgcConfig['hls_url']}{$stream_name}/{$rate}p.m3u8",
            "play_url" => "{$ltvUgcConfig['play_url']}{$stream_name}_{$rate}p",
            "rtmp_url" => "{$ltvUgcConfig['rtmp_url']}{$stream_name}_{$rate}p",
            "start_time" => 0,
            "end_time" => 0,
            "cdn_url1" => '',
            "cdn_url2" => '',
            "cdn_url3" => '',
            "is_pause" => 0,
        );
        return $streamData;
    }








}