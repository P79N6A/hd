<?php

/**
 * 文章管理
 *
 * @author     Xue Wei
 * @created    2015-11-10
 */
class MediaVideosController extends MediaBaseController {

    protected $urlName = 'media_videos';

    protected $type = 'video';

    
    public function editAction() {
    	if($this->denySystemAdmin()) {
    		return true;
    	}
    	$getId = Request::get('id', 'int');
    	$this->initFormView();
    	$messages = [];
   
    	$model = Videos::channelQuery(Auth::user()->channel_id)
    	->andCondition('id', $getId)
    	->first();
    	if(!$model) {
    		abort(404);
    	}
    	$r = Data::getByMedia($model->id, 'video');
    	$datas = [];
    	if(!$r) {
    		$messages[] = Lang::_('can not find carrier');
    	} else {
    		$quotes = json_decode($r->data_data, true);
    		if($quotes) {
    			$datas = Data::queryByIds(Auth::user()->channel_id, $quotes);
    		}
    	}
    	// 默然地址，部门数据获取
    	$region = $this->initRegionData();
    	$government = $this->initDepartmentData($r->id);
    	if(Request::isPost()) {
    		//媒资ID
    		$data_id = $r->id;

            //修改缓存时间
            $last_modified_key = "media/" . $r->type . ":" . $r->id;
            F::_clearCache($last_modified_key, $r->channel_id);
    		
    		$comment_type = intval(Request::getPost('comment_type'));
    		$isSubUrl = Request::getPost('isSubUrl');
    		$noSubUrl = Request::getPost('noSubUrl');
    		
    		$data = $this->preProcessData(Request::getPost());    	
    		//兼容引用数据
    		$quotes = ids(Request::getPost('quotes'));
    		$datas = Data::queryByIds(Auth::user()->channel_id, $quotes);

    		$vVideos = Videos::makeValidator($data);
    		
    		
    	  if(!$vVideos->fails() ) {
                $model->comment_type = $comment_type;
                $data['updated_at'] = time();
                $r->created_at = strtotime($data['created_at']);
                $model->created_at = strtotime($data['created_at']);
                unset($data['created_at']);

                $data['redirect_url']= $isSubUrl;
              
                if($data['referer_self']==0) {
                    $referer_url = "http://";
                    preg_match("/^(http[s]?:\/\/)?([^\/]+)/i", $data['referer_url'], $arr_domain);
                    $referer_url .= $arr_domain[2];
                    $referer_m = Referer::findByDomain(Auth::user()->channel_id, $referer_url);
                    if(!$referer_m->id) {
                        $data["referer_name"] =($data["referer_name"])?$data["referer_name"]:"未知网站-".time();
                        $data['referer_id'] = Referer::addDomian(Auth::user()->channel_id, $referer_url, $data["referer_name"]);
                    }else {
                        $data['referer_id'] = $referer_m->id;
                    }
                }else {
                    $data['referer_id'] = 0;
                    $data['referer_url'] = "";
                }
            
                try {
                    if(count($datas) != count($quotes)) {
                        $this->throwDbE('非法的引用对象', 1);
                    }

                    //更新新闻
                    if(!$model->update($data, News::safeUpdateFields())) {
                        $this->throwDbE('model');
                    }
                    $data['data_data'] = json_encode($quotes);

                    if(!$r->update($data, Data::safeUpdateFields())) {
                        $this->throwDbE('r');
                    }
                    DataExt::setExtValue($r->id, DataTemplateParams::getParams($r->data_template_id), $data, $r->partition_by);
                    //删除地区
                    $region_arr = RegionData::findRegionData($r->id);
                    $index_arr = array();
                    $index=0;
                    foreach($data as $k=>$v){
                    	if(preg_match('/^(region)+/',$k)){
                    		$i=substr($k,-1);
                    		$index_arr[$index]=$v;
                    		$index++;
                    	}
                    }
                    foreach($region_arr as $k =>$v){
                    	if(!in_array($v['id'],$index_arr)){
                    		RegionData::deleteRegionData($v['id']);
                    		unset($region_arr[$k]);
                    	}
                    }
                 	//存地区
                    $regionData = new RegionData();
                    if(!$regionData->saveRegion($data,$r->id)){
                        //$this->throwDbE('regions');
                    }
                    //保存部门
                 	$gov = new GovernmentDepartmentData();
                    if(!$gov->updateGovernmentDepartment($data,$r->id)){
                    	//$this->throwDbE('save government Department error');
                    }
                    $messages[] = Lang::_('modify success');
                }catch(DatabaseTransactionException $e) {
                    
                    if($e->getCode() === 0) {
                        $_m = $e->getMessage();
                        $msgs = $$_m->getMessages();
                        foreach($msgs as $msg) {
                            $messages[] = $msg->getMessage();
                        }
                    } else {
                        $messages[] = $e->getMessage();
                    }
                }
            } else {
                $msgBag = $vData->messages();
                $msgBag->merge($vVideos->messages());
                foreach($msgBag->all() as $msg) {
                    $messages[] = $msg;
                }
            }
    	}
    	$data_id = $r->id;
        $param_values = DataExt::getExtValues($data_id);

    	$r->assignToMedia($model);
    	$region_arr = RegionData::showRegion($data_id);

        $data_templates = DataTemplates::findAllByMediaType(PrivateCategory::MEDIA_TYPE_VIDEO);
        if($r->data_template_id) {
            $custom_params = CustomParams::getParams(DataTemplateParams::getParams($r->data_template_id));
        }
        else {
            $custom_params = array();
        }
        $trmarr = array();
        $validatabody="";
        $validmsgbody="";


        $submitstep2ids = "";
        foreach ($custom_params as $param) {
            if($param['param_fun_type']=="text") {
                $validata = '"'.$param['param_name'].'":'.$param['param_validate'];
                $validmsg = '"'.$param['param_name'].'":'.$param['param_validate_msg'];
                $validata = str_ireplace('"required": true', '"required": false', $validata);/*必填初始化 false*/

                $validatabody .= (""==$validatabody)?$validata:",".$validata;
                $validmsgbody .= (""==$validmsgbody)?$validmsg:",".$validmsg;
            }
            else {
                $validata = "\"".$param['param_name']."\":{\"required\": true}\n";
                $validmsg = "\"".$param['param_name']."\":{\"required\": \"请输入".$param['param_label']."\"}\n";
                $validatabody .= (""==$validatabody)?$validata:",".$validata;
                $validmsgbody .= (""==$validmsgbody)?$validmsg:",".$validmsg;
            }
            array_push($trmarr, array(
                'id'=>$param['id'],
                'param_name'=>$param['param_name'],
                'param_value'=> $param_values[$param['param_name']],
                'param_label'=>$param['param_label'],
                'param_fun_type'=>$param['param_fun_type'],
                'is_required'=>false,
                'param_data'=>$param['param_data'],
                'param_bind_obj'=>"",
                'param_validate_type'=>"",
            ));
            $submitstep2ids .= ",'".$param['param_name']."': $(\"#au_".$param['param_name']."\").val()";
        }


        $dataJson =  "{\"objects\":".json_encode($trmarr)."}";

    	
    	$media_type = PrivateCategory::MEDIA_TYPE_NEWS;
    	$privateCategoryData = privateCategoryData::getIdByData($data_id);
    	$videoFiles = VideoFiles::findVideoByVideoId($getId);
    	    	
    	View::setVars(compact('model','dataJson','data_templates', 'messages', 'datas','region_arr','data_id','privateCategoryData', 'media_type','region','government','videoFiles'));
    } 
    
   
}