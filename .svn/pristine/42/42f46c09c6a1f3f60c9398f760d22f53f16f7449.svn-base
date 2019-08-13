var _ua_ = navigator.userAgent.toLowerCase();
var device = "";
if (/iphone|ipad|ipod/.test(_ua_)) {
    device = "ios";
} else if (/android/.test(_ua_)) {
    device = "android";
}
function testPhone(tel) {
    tel = tel.trim();
    return /^1[0-9]{10}$/.test(tel);
}
function testName(name) {
    name = name.trim();
    return /^[\u4e00-\u9fa5a-zA-Z]{2,20}$/.test(name);
}
function testCode(code) {
    return code.trim().length > 1;
}
function testAddr(addr) {
    return addr.trim().length > 0;
}
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return r[2];
    return null;
}
function str_count(str, find) {
    var reg = new RegExp(find,"g");
    var c = str.match(reg);
    return c? c.length: 0;
}
function notifier(msg) {
    alert(msg);
}