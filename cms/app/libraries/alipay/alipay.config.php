<?php
/* *
 * 配置文件
 * 版本：3.3
 * 日期：2012-07-19
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
	
 * 提示：如何获取安全校验码和合作身份者id
 * 1.用您的签约支付宝账号登录支付宝网站(www.alipay.com)
 * 2.点击“商家服务”(https://b.alipay.com/order/myorder.htm)
 * 3.点击“查询合作者身份(pid)”、“查询安全校验码(key)”
	
 * 安全校验码查看时，输入支付密码后，页面呈灰色的现象，怎么办？
 * 解决方法：
 * 1、检查浏览器配置，不让浏览器做弹框屏蔽设置
 * 2、更换浏览器或电脑，重新登录查询。
 */

//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//合作身份者id，以2088开头的16位纯数字
$alipay_config['partner'] = '2088221936443963';

$alipay_config['seller'] = '3448087866@qq.com';

$alipay_config['private_key'] = 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAPLTS6Q/f73ezF0jKm4kmheE/0GIUJUlPpSfHTKmfnQvpjpBRkXJTlUJ2VhUdlR5QC3m62ZLiersvg7dMXpImGGuSwvvED6k61jb09PqOhYN5b4qlzMLOTOGf0w9LiUbht4/eh1QGPk6Bv4S+GrO95iCRBXhbNL46nGsoxWrdWVtAgMBAAECgYBGYxM5ECMCMbQBh3EELl3wVV/8afwZz4r9X8YB6ZscKLfBiSxKjFjFc65p2UnXoLIG3Dn+FAVtcKSDAIEYFjT9AwdavV6mDoJoNmzVK/C0hTDwYrLOAsv4tPHFmJ6c10Vl8R9udSb0iRElZjMGrYfQc8fbXLY9ifRnQvAYUxIjIQJBAP94321xc0a64DPzqx1r8VnAjB4lYlW2HIk9M3QofoBwoog5mSdX6H1MfA5K2fLRswBbOw6uu65idm1O0EHeauUCQQDzU7vCB4lA4v04NuUnjZMV4RFHwzaNFboPrFU0nDJFS+53J/EoPIxnievaRwDLyCHNv06SoUa8obQvUKtonP/pAkEAm6YQMoTxoAFRgjWOZrGSbYVzmRZb0C7ROghQpg/Z6vU1AVxeZGsZ2eVUm/ycx2Vd8vSiibKJ5JhW4QgouEkDxQJAejBcIs1CAVF7MxVt8XTInb4NvWmsJSD59BDoIKknHWFJ+JAYK8pr/zplM5FgNvTi9LzSqhNOwD67it8JlFjysQJAFUqPHtDvCwJMUoYzZPfhmmMK0JUlE2DQ1lyy9JuY5rB9CmP0gnLBRFOO8uGyoj2nS/cYWLyEdsDxBaDLNCIjfQ==';

$alipay_config['public_key'] = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';

//商户的私钥（后缀是.pen）文件相对路径
$alipay_config['private_key_path'] = APP_PATH . 'libraries/alipay/key/rsa_private_key.pem';

//支付宝公钥（后缀是.pen）文件相对路径
$alipay_config['ali_public_key_path'] = APP_PATH . 'libraries/alipay/key/alipay_public_key.pem';

$alipay_config['notify_url'] = 'https://payhudong.cztv.com/Paymentnotify/alipay';
// $alipay_config['notify_url'] = 'http://wap.damitv.com/payment_notify/alipay';


//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


//签名方式 不需修改
$alipay_config['sign_type'] = strtoupper('RSA');

//字符编码格式 目前支持 gbk 或 utf-8
$alipay_config['input_charset'] = strtolower('utf-8');

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
$alipay_config['cacert'] = getcwd() . DIRECTORY_SEPARATOR . 'third_party' . DIRECTORY_SEPARATOR . 'alipay' . DIRECTORY_SEPARATOR . 'cacert.pem';
//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport'] = 'http';

?>