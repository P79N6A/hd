<?php

define('TencentCloud_ROOT', APP_PATH . 'libraries/TencentCloud/');
require_once(TencentCloud_ROOT . 'TCloudAutoLoader.php');
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Facefusion\V20181201\FacefusionClient;
use TencentCloud\Facefusion\V20181201\Models\FaceFusionRequest;
/**
 * @RoutePrefix("/txcloud")
 */
class TxCloudController extends ApiBaseController
{
    /**
     * @Post("/face")
     */
    public function getFaceAction()
    {
        $params = Request::getPost('params');
        $setting = Request::getPost('setting');
        try {
            $cred = new Credential($setting['SecretId'], $setting['SecretKey']);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("facefusion.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new FacefusionClient($cred, "ap-shanghai", $clientProfile);

            $req = new FaceFusionRequest();
            $params = json_encode($params);
            $req->fromJsonString($params);
            $resp = $client->FaceFusion($req);
            $res = $resp->toJsonString();
            echo $res;
        }
        catch(TencentCloudSDKException $e) {
            echo $e;
        }
        exit;
    }
}

?>