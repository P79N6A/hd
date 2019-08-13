<?php

/**
 * @RoutePrefix("/advert")
 */

class AdvertController extends ApiBaseController {

    /**
     * @Get("/{id:[0-9]+}")
     * @param int $id
     * @return json
     */
    public function sourceAction($id) {
        $id = intval($id);
        $space = AdvertSpace::getOne($id);
        $data = array();
        if($space) {
            $advertdata = Advert::getAdvertBySpaceid($space->id);
            if($space->type==AdvertSpace::ADVERT_SAPCE_TYPE_CODE) {
                foreach ($advertdata->models as $k => $v) {
                  $setting = json_decode($v->setting, true);
                  $data[] = array(
                      'type'=>  Advert::getTypeName($v->type),
                      'code'=> $setting[0]['code'],
                      'startdate'=> $v->startdate,
                      'enddate'=> $v->enddate,
                      'duration'=> $v->duration,
                    );
                }
            }
            else if($space->type==AdvertSpace::ADVERT_SAPCE_TYPE_TEXT) {
                foreach ($advertdata->models as $k => $v) {
                  $setting = json_decode($v->setting, true);
                  $data[] = array(
                      'type'=>  Advert::getTypeName($v->type),
                      'linkurl'=> $setting[0]['linkurl'],
                      'desc'=> $setting[0]['alt'],
                      'startdate'=> $v->startdate,
                      'enddate'=> $v->enddate,
                      'duration'=> $v->duration,
                    );
                }
            }
            else {
                foreach ($advertdata->models as $k => $v) {
                  $setting = json_decode($v->setting, true);
                  $data[] = array(
                      'type'=>  Advert::getTypeName($v->type),
                      'linkurl'=> $setting[0]['linkurl'],
                      'imageurl'=> $setting[0]['imageurl'],
                      'desc'=> $setting[0]['alt'],
                      'startdate'=> $v->startdate,
                      'enddate'=> $v->enddate,
                      'duration'=> $v->duration,
                    );
                }
            }
        }
        $this->_json($data);
    }

    /**
     * @Get("/")
     */
    public function startAction() {
        $space = AdvertSpace::getSpaceByType($this->channel_id, AdvertSpace::ADVERT_SAPCE_TYPE_APPSTART);
        $data = array();
        if($space) {
            $advertdata = Advert::getAdvertBySpaceid($space->id);
            foreach ($advertdata->models as $k => $v) {
              $setting = json_decode($v->setting, true);
              $data[] = array(
                  'type'=>  Advert::getTypeName($v->type),
                  'linkurl'=> $setting[0]['linkurl'],
                  'imageurl'=> $setting[0]['imageurl'],
                  'desc'=> $setting[0]['alt'],
                  'startdate'=> $v->startdate,
                  'enddate'=> $v->enddate,
                  'duration'=> $v->duration,
                );
            }
        }
        $this->_json($data);
    }
}