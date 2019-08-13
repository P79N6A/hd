<?php


class AppdownloadController extends \PublishBaseController {

    public function indexAction(){
        $channel_id =  (int)Request::getQuery('channel_id');
        $sku = Request::getQuery('sku', 'string','');
        if(!$channel_id)  {
            header("HTTP/1.1 404 Not Found");
            header('status: 404 Not Found');
            exit;
        }
        $data = AppList::getAppsByChannelId($channel_id);        
        if(!count($data->models))  {
            header("HTTP/1.1 404 Not Found");
            header('status: 404 Not Found');
            exit;
        }
        foreach($data->models as $app) {
        if($sku&&$app->sku!=$sku) continue;

        $data = [];
        $data['android'] = array('url'=>'', 'version'=>0);
        $data['ios'] = array('url'=>'', 'version'=>0);        
        $version_android = AppVersion::findFirst(array(
                    'app_id=:app_id: AND type=:type: AND version=:version:',
                    'bind' => array('app_id' => $app->id, 'type' => AppVersion::ANDROID, 'version' => $app->version_android)
                ));
        if($version_android) {
            $data['android']['url'] = $version_android->url;
            $data['android']['version'] = $version_android->version;
        }
        $version_ios = AppVersion::findFirst(array(
                    'app_id=:app_id: AND type=:type: AND version=:version:',
                    'bind' => array('app_id' => $app->id, 'type' => AppVersion::IOS, 'version' => $app->version_ios)
                ));
        if($version_ios) {
            $data['ios']['url'] = $version_ios->url;
            $data['ios']['version'] = $version_ios->version;
        }
            break;
            }
        View::setVars(compact('app','data'));
    }

}