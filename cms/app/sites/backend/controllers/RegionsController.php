<?php
/**
 * Created by 
 * Date: 2015/11/26
 */
class RegionsController extends \BackendBaseController {

    public $ignore = [
        'json', 'list'
    ];
    
    /**
     * json数据
     */
    public function jsonAction() {

        $root = Regions::getRootCategory();
        $data = array();
        foreach($root as $node) {
            $temp = RegionsTree::getCategoryJson($node->id);
            array_push($data, $temp);
        }
        echo json_encode($data);
        exit;
    }

    public function jsonGetRegionDataAction(){
        $data_id = Request::getQuery("data_id","int",0);
        $data = [];
        if($data_id>0){
            $region_data =  RegionData::findRegionData($data_id);

            if(count($region_data)>0){
                $address = $region_data[0]['description'];
                $region = Regions::fetchById($region_data[0]['province_id']);
                $data['province_name'] =$region?$region->name:"";
                $region = Regions::fetchById($region_data[0]['city_id']);
                $data['city_name'] =$region?$region->name:"";
                $region = Regions::fetchById($region_data[0]['county_id']);
                $data['country_name'] =$region?$region->name:"";
                $region = Regions::fetchById($region_data[0]['town_id']);
                $data['town_name'] = $region?$region->name:"";
                $region = Regions::fetchById($region_data[0]['village_id']);
                $data['village_name'] = $region?$region->name:"";
                $data['description'] = $address;
            }
        }
        echo json_encode(array("success"=>($data?true:false),'data'=>$data));
        exit;
    }








    /**
     * 根据上级ID获取下级队列
     */
    public function listAction() {
        $father_id = Request::getPost('id','int');
        if(!$father_id){
            $father_id=0;
        }
        $data=Regions::findListByFather($father_id);
        echo json_encode($data);
        exit;
    }

    public function defaultAction(){
    	$father_id = Request::getPost('id','int');
    	if(!$father_id){
    		$father_id=0;
    	}
    	$data=Regions::findListByFather($father_id);
    	echo json_encode($data);
    	exit;
    }
    
    /**
     * 列表页管理
     */
    public function indexAction(){
        $data=Regions::findAll();
        View::setVars(compact('data'));
    }

    public function createAction(){
        $messages = [];
        if(Request::isPost()){
            $input = Request::getPost();
            if(isset($input['name'])){
                $region = new Regions();
                $region->name=$input['name'];
                $region->pinyin=Cutf8py::encode($input['name'], 'all');
                $region->pinyin_short=Cutf8py::encode($input['name']);
                if($input['country_id']){
                    if($input['province_id']){
                        if($input['city_id']){
                            if($input['county_id']){
                                if($input['town_id']){
                                    if($input['village_id']){
                                        $messages[] = Lang::_('cant low to village');
                                    }else{//创建村
                                        $region->father_id=$input['town_id'];
                                        $region->level='village';
                                        $region->createRegions();
                                        $messages[] = Lang::_('success');
                                    }
                                }else{//创建街道
                                    $region->father_id=$input['county_id'];
                                    $region->level='town';
                                    $region->createRegions();
                                    $messages[] = Lang::_('success');
                                }
                            }else{//创建县
                                $region->father_id=$input['city_id'];
                                $region->level='county';
                                $region->createRegions();
                                $messages[] = Lang::_('success');
                            }
                        }else{//创建市
                            $region->father_id=$input['province_id'];
                            $region->level='city';
                            $region->createRegions();
                            $messages[] = Lang::_('success');
                        }
                    }else{//创建省
                        $region->father_id=$input['country_id'];
                        $region->level='province';
                        $region->createRegions();
                        $messages[] = Lang::_('success');
                    }
                }else{//创建国家
                    $region->father_id=0;
                    $region->level='country';
                    $region->createRegions();
                    $messages[] = Lang::_('success');
                }
            }else{
                $messages[] = Lang::_('regions name not exit');
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function deleteAction(){
        $id = Request::get('id');
        $data = Regions::fetchById($id);
        $arr=Regions::findListByFather($data->id);
        if($arr){
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cant delete have son')]);
        }else if(Regions::deleteRegions($id)){
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        }else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }
        exit;
    }

    public function modifyAction(){
        $id = Request::get('id');
        $region = Regions::fetchById($id);
        if(Request::isPost()){
            $input = Request::getPost();
            if(isset($input['name'])){
                $region->name=$input['name'];
                $region->pinyin=Cutf8py::encode($input['name'], 'all');
                $region->pinyin_short=Cutf8py::encode($input['name']);
                if($input['country_id']){
                    if($input['province_id']){
                        if($input['city_id']){
                            if($input['county_id']){
                                if($input['town_id']){
                                    if($input['village_id']){
                                        $messages[] = Lang::_('cant low to village');
                                    }else{//创建村
                                        $region->father_id=$input['town_id'];
                                        $region->level='village';
                                        $region->createRegions();
                                        $messages[] = Lang::_('success');
                                    }
                                }else{//创建街道
                                    $region->father_id=$input['county_id'];
                                    $region->level='town';
                                    $region->createRegions();
                                    $messages[] = Lang::_('success');
                                }
                            }else{//创建县
                                $region->father_id=$input['city_id'];
                                $region->level='county';
                                $region->createRegions();
                                $messages[] = Lang::_('success');
                            }
                        }else{//创建市
                            $region->father_id=$input['province_id'];
                            $region->level='city';
                            $region->createRegions();
                            $messages[] = Lang::_('success');
                        }
                    }else{//创建省
                        $region->father_id=$input['country_id'];
                        $region->level='province';
                        $region->createRegions();
                        $messages[] = Lang::_('success');
                    }
                }else{//创建国家
                    $region->father_id=0;
                    $region->level='country';
                    $region->createRegions();
                    $messages[] = Lang::_('success');
                }
            }else{
                $messages[] = Lang::_('regions name not exit');
            }
        }
      
        //生成上级地区
        $parents=$region->getParents();
        unset($parents[count($parents)-1]);
        foreach($parents as $p){
            $level_id=($p->level).'_id';
            $region->$level_id=$p->id;
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages','region'));
    }
}