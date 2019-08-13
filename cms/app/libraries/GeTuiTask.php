<?php

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/7/14
 * Time: 23:53
 */
class GeTuiTask
{
    private $gt_ios_key;
    private $gt_ios_secrect;
    private $gt_ios_appid;
    private $gt_android_key;
    private $gt_android_secrect;
    private $gt_android_appid;  
    private $mess_title;
    private $mess_body;

    private $gt_push_ios_config;
    private $gt_push_android_config;
    private $gt_push_data;
    private $clients;
    private $return;


    public function __construct($gt_params,$title,$body,$clients=null)
    {
        foreach($gt_params as $key=>$value)
        {
            $this->$key = $value;
        }
        if($clients)
        {
            $this->setClients($clients);    //初始化IDS
        }

        if(!empty($title) && !empty($body))
        {
            $this->mess_title = $title;
            $this->mess_body = $body;
            $this->message_body();
        }
        $this->inintgtConfig();
    }


    private function setClients($push_clients)
    {
        $this->clients = $push_clients;
    }


    private function message_body()
    {
        $this->gt_push_data = array('title'=>$this->mess_title,'data'=>$this->mess_body);
    }

    private function inintgtConfig()
    {
        $this->gt_push_android_config= array(
            'AppKey'=>$this->gt_android_key,
            'MasterSecret' => $this->gt_android_secrect,
            'AppID'=>$this->gt_android_appid
        );

        $this->gt_push_ios_config = array(
            'AppKey'=>$this->gt_ios_key,
            'MasterSecret' => $this->gt_ios_secrect,
            'AppID'=>$this->gt_ios_appid
        );
    }



    public function push_list_android($pushType)
    {
        if($this->clients['android_push_clients'])
            $this->return = F::getuiAndriodTvCztvProxy($this->gt_push_android_config, $this->gt_push_data, $pushType, $this->clients['android_push_clients']);
    }

    public function push_list_ios($pushType)
    {
        if($this->clients['ios_push_clients'])
           $this->return = F::getuiIOSTvCztvProxy($this->gt_push_ios_config, $this->gt_push_data, $pushType, $this->clients['ios_push_clients']);  
    }
    


    public function push_all_android($pushType)
    {
        $this->return = F::getuiAndriodTvCztvProxy($this->gt_push_android_config,$this->gt_push_data,$pushType);
    }

    public function push_all_ios($pushType)
    {
        $this->return = F::getuiIOSTvCztvProxy($this->gt_push_ios_config,$this->gt_push_data,$pushType);
    }
	
    /**
     * 返回消息发送结果
     * @return unknown
     */
    public function getReturn() {
    	
    	$result = self::valReturn($this->return);
    	return $result[0];
    }
    
    /**
     * 过滤返回结果
     * @param unknown $value
     * @return unknown
     */
    public static function valReturn($value) {
    	$var = $value;
    	$preg = "/\{.*?\}/";
    	preg_match_all($preg,$var,$match);
    	return $match;
    }
}