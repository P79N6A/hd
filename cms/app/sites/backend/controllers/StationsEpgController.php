<?php
/**
 *  电视节目流管理
 *  controller stationsEpg
 *  @author     Zhangyichi
 *  @created    2015-9-11
 *  
 *  @param id,stations_id,name,width,height,cdn,percent,kpbs,audiokpbs,drm
 */

class StationsEpgController extends \BackendBaseController {

    public function indexAction() {
        $id = Request::getQuery('id','int');
        $station = Stations::getOne($id);
        if($station) {
            $data= StationsEpg::getStationsEpg($station->id);
        }
        else {
            $data = array();  
        }

        View::setVars(compact('data', 'station'));
    }

    public function createAction() {
        $station_id = (int)Request::getQuery('station_id','int');
        $station = Stations::getOne($station_id);
        $messages = [];
        if (Request::isPost()) {
            $inputs=Request::getPost();
            $validator=StationsEpg::makeValidator($inputs);
            if($validator->passes()){
                $stationsepg=new StationsEpg();
                if($stationsepg->createStationsEpg($inputs)) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }else{
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages','station'));
    }

    public function modifyAction() {
        $id = Request::getQuery('id','int');
        $data = StationsEpg::getOne($id);
        $station = Stations::getOne($data->stations_id);
        $this->initFormView();        
        $messages = [];
        if (Request::isPost()) {
            $inputs=Request::getPost();
            $validator=StationsEpg::makeValidator($inputs);
            if($validator->passes()){
                if($data->modifyStationsEpg($inputs)) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }else{
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
        }
        View::setVars(compact('messages','data','station'));
    }

    public function deleteAction(){
        $id=$this->request->getQuery("id","int");
        $return=StationsEpg::deleteStationsEpg($id);
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function searchAction($id){
        return StationsEpg::getStationsEpgById($id);
    }



}