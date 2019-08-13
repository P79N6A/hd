function CZTVJsObject() {

    var bridge;
    if(window.WebViewJavascriptBridge) {
        bridge = window.WebViewJavascriptBridge;
    } else {
        document.addEventListener('WebViewJavascriptBridgeReady', function() {
            bridge = WebViewJavascriptBridge;
        }, false);
    }

    var cztv;
    if(window.CZTVJsdk) {
        cztv = CZTVJsdk;
    }

    this.share = function(params) {
        if(bridge) {
            bridge.callHandler('CZTVJsdk.share', params, function(response) {
                log('JS get response', response);
            });
        }
        if(cztv) {
            cztv.share(JSON.stringify(params));
        }
    };

    this.playSound = function() {
        if(bridge) {
            bridge.callHandler("CZTVJsdk.playSound");
        }
        if(cztv) {
            CZTVJsdk.playSound();
        }
        if(!cztv && !bridge) {
            if(window.shakeAudio) {
                shakeAudio.play();
            }
        }
    }

}