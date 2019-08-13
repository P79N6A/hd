<?php

/**
 * 接名牌控制器
 * @author JasonFang
 *
 */
class VoiceController extends \PublishBaseController {

    protected $data_voice_ar = array(
        'wf'=>'汪峰',
        'zjl'=>'周杰伦',
        'ny'=>'那英',
        'ycq'=>'庾澄庆',
        'tt'=>'淘汰',
    );


    /**
	 * 数据首页
	 * 
	 */
	public function indexAction() {
            $url_online = 'http://yaotvcode.act.qq.com/vendorapi/guessdata';
            
            $return_str = F::curlRequest($url_online,'get');//print_r($return_str);
            //第一阶段数据格式
            //$return_str = '{"error":0,"msg":"OK","title":"李莫愁","ext_data":{"wf":10,"zjl":20,"ny":30,"ycq":20,"tt":20}}';
            //第二阶段数据格式
            //$return_str = '{"error":0,"msg":"OK","ext_data":{"pid1":{"name":"\u5218\u6587\u5929","percent":10},"pid2":{"name":"\u5415\u4fca\u54f2","percent":90}}}';
            $return = json_decode($return_str);
            //$ar = $this->data_voice_ar;
            $data = $return->ext_data;
            $title = '总决赛';
            foreach ($data as $key => $value) {
                $items[]=array(
                    'title'=>$value->name,
                    'num'=>$value->percent.'%'
                );
            }
            View::setVars(compact('items','title','return_str'));
	}
	
        public function index2Action() {
            $url_online = 'http://yaotvcode.act.qq.com/vendorapi/guessdata';
            
            $return_str = F::curlRequest($url_online,'get');print_r($return_str);exit;
            
	}
	
}