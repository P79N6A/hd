<?php
/**
 *  电视广播台管理
 *  controller stations
 *  @author     Zhangyichi
 *  @created    2015-9-16
 *
 *  @param id,is_system,channel_id,code,name,type,logo,channel_name,customer_name,epg_path
 */


class ThirdpartyController extends \BackendBaseController {
    const IQiYi = 'iqiyi';
    const IQiYi_Request_Url = 'http://expand.video.iqiyi.com/api/search/list.json';
    const PageSize = 20;
    const IQiYi_Movie = 1;
    const IQiYi_Teleplay = 2;
    const IQiYi_Animation = 4;
    const IQiYi_Variety = 6;

    public function indexAction() {

    }

    public function searchAction() {
        $data = array();
        $input = Request::get();
        if(!empty($input['keyWord'])) {
            $url = $input['_url'];
            $param = array(
                'keyWord' => $input['keyWord'],
                'categoryIds' => empty($input['categoryIds'])?self::IQiYi_Variety:$input['categoryIds'],
                'pageNo' => empty($input['pageNo'])?1:$input['pageNo'],
                'pageSize' => empty($input['pageSize'])?self::PageSize:$input['pageSize'],
            );

            switch ($input['supplier']) {
                case self::IQiYi : $resp = $this->searchToIqiyi($param);
                $data = $resp['data'];$count = $resp['total'];break;
            }

            View::pick('thirdparty/index');
            View::setVars(compact('input','data','count','url'));
        }else {
            View::pick('thirdparty/index');
        }
    }

    private function searchToIqiyi(&$param) {
        $param['type'] = 'list';
        $param['apiKey'] = '61414cbfb33b4702847dc34fbc146f9d';
        var_dump(self::IQiYi_Request_Url.'?'.http_build_query($param));
//        $file_contents = file_get_contents(self::IQiYi_Request_Url.'?'.http_build_query($param));
        $file_contents = F::curlProxyCli(self::IQiYi_Request_Url.'?'.http_build_query($param) , 'get');
        $resp = json_decode($file_contents, true);
        if (isset($resp['code']) && $resp['code'] == 'A00000'){
            return $resp;
        }else{
            return array();
        }
    }

}