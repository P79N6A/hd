<?php

//评论异步入库脚本

/**###################################################
 *
 * 脚本相关配置
 *
#####################################################*/
define('COMMENT_OPERATE_INTERVAL', 200);     //处理间隔  单位: 毫秒
define('COMMENT_FAIL_RETRY_TIMES', 2);      //失败重试次数
define('COMMENT_REDIS_QUEUE_KEY', 'comment_video');   //评论redis队列名称
define('COMMENT_LOG_DIR', '/data/log/moveComment/');         //日志文件打印目录

/**
 * 打印日志
 * @param string $type  日志级别: info, warn, error
 * @param string $info  日志信息
 */
function debug_comment($type, $info){
    if(!file_exists(COMMENT_LOG_DIR)){
        mkdir(COMMENT_LOG_DIR, 666, true);
    }
    $logFile = COMMENT_LOG_DIR.'/moveComment.'.date('Y-m-d').'.log';
    $logInfo = date('Y-m-d H:i:s')."\t". $type ."\t". $info ."\t". microtime(true) ."\n";
    file_put_contents($logFile, $logInfo, FILE_APPEND);
}

//加载main.php中的项目配置
$main_config = include_once(__DIR__."/../../app/config/main.php");

//Mysql数据库配置
$mysql_config = $main_config['db_interactive'];
//创建数据库链接
$mysql = new PDO('mysql:host='.$mysql_config['host'].';dbname='.$mysql_config['dbname'], $mysql_config['username'], $mysql_config['password']);


//创建Redis链接
$redis = $main_config['components']['redis']['init']();


//评论需要添加的字段
$commentColumn = array(
    'comment_id',
    'channel_id',
    'user_id',
    'username',
    'data_id',
    'cid',
    'pid',
    'type',
    'title',
    'cmtType',
    'father_id',
    'content',
    'create_at',
    'replynum',
    'likes',
    'down',
    'domain',
    'client',
    'ip',
    'location',
    'partition_by',
);


//数据异步入库操作
try {
    while (true) {
        $res = $redis->brPop(COMMENT_REDIS_QUEUE_KEY.date('Ymd'), 30);
        if (is_array($res) && !empty($res[1])) {
            debug_comment('debug', 'comment content: ' . $res[1]);

            $commentData = json_decode($res[1], true);
            $is_insert_db_success = false;    //数据是否添加成功

            //数据预处理
            $insertCommentSql = 'insert into comment('.implode(', ', $commentColumn).') VALUES (:'. implode(', :', $commentColumn) .')';
            $sth = $mysql->prepare($insertCommentSql);
            $executeCommentData = array();
            foreach ($commentColumn as $column) {
                $executeCommentData[':'.$column] = $commentData[$column];
            }

            //将评论插入数据库, 增加失败重试次数
            for($retry_time = 0; $retry_time <= COMMENT_FAIL_RETRY_TIMES; $retry_time++) {
                $saveRet = $sth->execute($executeCommentData);      //数据入库
                if ($saveRet) {
                    $is_insert_db_success = true;
                    debug_comment('debug', 'comment insert db success, retry_times:'.$retry_time);
                    break;    //如果数据添加成功, 跳出循环
                } else {
                    debug_comment('warn', 'comment insert db fail, sql:'.$insertCommentSql.', param:'.json_encode($executeCommentData).', retry_times:'.$retry_time);
                    debug_comment('warn', 'comment insert db fail, mysql_error:'.json_encode($mysql->errorInfo()).', retry_times:'.$retry_time);
                }
            }

            //如果重试之后, 数据仍然添加失败, 将其从缓存中删除, 并将其对应的评论数量减1
            if(!$is_insert_db_success){
                //删除集合中的commentid
                $redis->zRem("comment_".$commentData['type']."_".$commentData['data_id']."_commentIds", $commentData['comment_id']);

                //删除字符串中commentid对应的信息
                $redis->del("comment_".$commentData['type']."_commentInfos_".$commentData['comment_id']);

                //评论总数大于0的情况下, 评论总数减1
                if($redis->get("comment_count_".$commentData['data_id']) > 0){
                    $redis->decr("comment_count_".$commentData['data_id']);
                }
            }
        }

        usleep(COMMENT_OPERATE_INTERVAL);   //处理间隔
    }
} catch (Exception $e) {
    debug_comment('error', 'php shell script process fail:'.$e->getMessage());
}










