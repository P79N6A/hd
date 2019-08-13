<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2015/12/14
 * Time: 9:54
 */
/**
 * 读取 sql 文件并写入数据库
 * @version 1.01 demo.php
 */
class DBManager
{
    var $dbHost = '';
    var $dbUser = '';
    var $dbPassword = '';
    var $dbSchema = '';

    function __construct($host,$user,$password,$schema)
    {
        $this->dbHost = $host;
        $this->dbUser = $user;
        $this->dbPassword = $password;
        $this->dbSchema = $schema;
    }

    function createFromFile($sqlPath,$prefix = '',$commenter = array('#','--'))
    {
        //判断文件是否存在
        if(!file_exists($sqlPath))
            return false;

        $handle = fopen($sqlPath,'rb');

        $sqlStr = fread($handle,filesize($sqlPath));

        //通过sql语法的语句分割符进行分割
        $segment = explode(";",trim($sqlStr));

//        var_dump($segment);

        //去掉注释和多余的空行
        foreach($segment as & $statement)
        {
            $sentence = explode("\n",$statement);

            $newStatement = array();

            foreach($sentence as $subSentence)
            {
                if('' != trim($subSentence))
                {
                    //判断是会否是注释
                    $isComment = false;
                    foreach($commenter as $comer)
                    {
                        if(preg_match("/^".$comer."/",trim($subSentence)))
                        {
                            $isComment = true;
                        }
                    }
                    //如果不是注释，则认为是sql语句
                    if(!$isComment)
                        $newStatement[] = $subSentence;
                }
            }

            $statement = $newStatement;
        }
//        var_dump($segment);
        //对表进行修改
        if('' != $prefix)
        {
            //只有表名在第一行出现时才有效 例如 CREATE TABLE talbeName
            $regxTable = "^[\`\'\"]{0,1}[\_a-zA-Z]+[\_a-zA-Z0-9]*[\`\'\"]{0,1}$";//处理表名的正则表达式
            $regxLeftWall = "^[\`\'\"]{1}";

            $sqlFlagTree = array(
                "INSERT" => array(
                    "INTO" => array(
                        "$regxTable" => 0
                    )
                )
            );

            foreach($segment as & $statement)
            {
                $tokens = explode(" ",$statement[0]);

                $tableName = array();
                $this->findTableName($sqlFlagTree,$tokens,0,$tableName);

                if(empty($tableName['leftWall']))
                {
                    $newTableName = $prefix.$tableName['name'];
                }
                else{
                    $newTableName = $tableName['leftWall'].$prefix.$tableName['leftWall'];
                }

                $statement[0] = str_replace($tableName['name'],$newTableName,$statement[0]);
                $statement[0] = str_replace("INSERT","INSERT IGNORE",$statement[0]);
            }
            //修改字段
            if($prefix=='stations') {
                $this->removeField($segment[0], 'id');
                $this->modifyField($segment[0], 'ac_name', 'name');
                $this->modifyField($segment[0], 'ac_code', 'code');
                $this->modifyField($segment[0], 'ac_type', 'type');
                $this->removeField($segment[0], 'zrtg');
                $this->removeField($segment[0], 'ac_template_web');
                $this->removeField($segment[0], 'ac_template_wap');
                $this->removeField($segment[0], 'ac_text_content');
                $this->removeField($segment[0], 'ac_creator_id');
                $this->removeField($segment[0], 'ac_status');
                $this->removeField($segment[0], 'ac_create_timestamp');
                $this->removeField($segment[0], 'ac_update_timestamp');
                $this->modifyField($segment[0], 'ac_url', 'epg_path');
                $this->removeField($segment[0], 'server_id');
                $this->modifyField($segment[0], 'channel_image', 'logo');
                $this->removeField($segment[0], 'ac_inner_uri');
            }
            if($prefix=='stations_epg'){
                $this->removeField($segment[0], 'id');
                $this->modifyField($segment[0], 'ac_code', 'stations_id');
                $this->modifyField($segment[0], 'stream_name', 'name');
                $this->modifyField($segment[0], 'videodatarate', 'kpbs');
                $this->modifyField($segment[0], 'audiodatarate', 'audiokpbs');
                $this->modifyField($segment[0], 'cdn1', 'cdn');
                $this->modifyField($segment[0], 'percent1', 'percent');
                $this->removeField($segment[0], 'cdn2');
                $this->removeField($segment[0], 'cdn3');
                $this->removeField($segment[0], 'cdn4');
                $this->removeField($segment[0], 'percent2');
                $this->removeField($segment[0], 'percent3');
                $this->removeField($segment[0], 'percent4');
            }
        }
//        var_dump($segment);
        //组合sql语句
        foreach($segment as & $statement)
        {
            $newStmt = '';
            foreach($statement as $sentence)
            {
                $newStmt = $newStmt.trim($sentence)."\n";
            }

            $statement = $newStmt;
        }

        //用于测试------------------------
//        var_dump($segment);exit;
//        writeArrayToFile('data.txt',$segment);
        //-------------------------------

        self::saveByQuery($segment);

        return true;
    }
    private function modifyField(&$arr,$old_field,$new_field){
        if(preg_match("/[\`]".$old_field."[\`]/",$arr[0])) {
            $arr[0]=str_replace("`".$old_field."`","`".$new_field."`",$arr[0]);
        }
    }
    private function removeField(&$arr,$field){
        $old=substr($arr[0],stripos($arr[0],'(')+1,stripos($arr[0],')')-stripos($arr[0],'(')-1);
        $findFiled=explode(",",$old);
        $index=0;
        for($j=0;$j<count($findFiled);$j++){
            if(preg_match("/[\`]".$field."[\`]/",$findFiled[$j])){
                for($i=1;$i<count($arr);$i++){
                    $a=explode(",",substr($arr[$i],stripos($arr[$i],'(')+1,stripos($arr[$i],')')-stripos($arr[$i],'(')-1));
                    array_splice($a,$index,1);
                    $b=implode(",",$a);
                    $b="(".$b.")";
                    if($i!=count($arr)-1){
                        $b=$b.",";
                    }
                    $arr[$i]=$b;
                }
                array_splice($findFiled,$j,1);
            }
            $index++;
        }
        $new=implode(",",$findFiled);
        $arr[0]=str_replace($old,$new,$arr[0]);
    }

    private function saveByQuery($sqlArray)
    {
        $conn = mysqli_connect($this->dbHost,$this->dbUser,$this->dbPassword,$this->dbSchema);
        mysqli_query($conn,"set names utf8;");
        foreach($sqlArray as $sql)
        {
            mysqli_query($conn,$sql);
        }
        mysqli_close($conn);
    }

    private function findTableName($sqlFlagTree,$tokens,$tokensKey=0,& $tableName = array())
    {
        $regxLeftWall = "^[\`\'\"]{1}";
        $tableName['name'] ="";

        if(count($tokens)<=$tokensKey)
            return false;

        if('' == trim($tokens[$tokensKey]))
        {
            return self::findTableName($sqlFlagTree,$tokens,$tokensKey+1,$tableName);
        }
        else
        {
            foreach($sqlFlagTree as $flag => $v)
            {
                if(preg_match("/^".$flag."/",$tokens[$tokensKey]))
                {
                    if(0==$v)
                    {
                        $tableName['name'] = $tokens[$tokensKey];

                        if(preg_match("/^".$regxLeftWall."/",$tableName['name']))
                        {
                            $tableName['leftWall'] = $tableName['name']{0};
                        }

                        return true;
                    }
                    else{
                        return self::findTableName($v,$tokens,$tokensKey+1,$tableName);
                    }
                }
            }
        }

        return false;
    }
}
function writeArrayToFile($fileName,$dataArray,$delimiter="\r\n")
{
    $handle=fopen($fileName, "wb");

    $text = '';

    foreach($dataArray as $data)
    {
        $text = $text.$data.$delimiter;
    }
    fwrite($handle,$text);
}
//入口
$dbM = new DBManager('10.1.121.56','cms_online','RQ6xMSGGL6xcnBH7','cms_online');
$dbM->createFromFile('cztv_activity_channel.sql','stations');
$dbM->createFromFile('cztv_activity_live_streams.sql','stations_epg');

?>