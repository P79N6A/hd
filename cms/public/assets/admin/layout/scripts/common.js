var is_Url = function(str_url){
    var strRegex = "^((https|http|ftp|rtsp|mms)?://)[^\s]*"
    var re=new RegExp(strRegex);
    if (re.test(str_url)){
        return true;
    }else{
        return false;
    }
}