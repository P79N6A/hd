<?php
/**
 * Created by PhpStorm.
 * User: wujunwei
 * Date: 2016/5/12
 * Time: 10:02
 */

use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;

class MoveuserTask extends Task {

    private $channel_id = 1;    //新蓝网Chnanel_id
    private $logDir = '/tmp/';       //日志文件夹

    /**
     * 获取 老用户表 数据库连接
     * @return \Phalcon\Db\Adapter\Pdo\Mysql
     */
    private function getSourceDbConnection(){
        $conn = new Phalcon\Db\Adapter\Pdo\Mysql([
            'host' => '10.1.121.56',
            'username' => 'cms_online',
            'password' => 'RQ6xMSGGL6xcnBH7',
            'dbname' => 'letv_user',
            'port' => 3306,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        return $conn;
    }

    /**
     * 迁移userid表
     */
    public function moveuseridAction(){

        $db_output = $this->getSourceDbConnection();

        $logFile = $this->logDir.'/moveUserid.log';
        $logger = new FileAdapter($logFile);

        $userid_table = 'userid';   //老数据库中userid表名

        //获取表中的记录总数
        $res = $db_output->fetchOne('select count(1) as total from userid;');
        if(!$res){
            $logger->error('get userid column number fail');
        }
        $column_total = $res['total'];

        $logger->debug('table insert start');

        //循环读取
        $pageSize = 10;     //test
//        $pageSize = 5000;    //每次处理记录数
        for($page = 0; $page < ceil($column_total/$pageSize); $page++) {
            $logger->debug('page:'.$page.' insert start');

            $offset = $page * $pageSize;
            $sql = "select * from {$userid_table} limit {$offset}, {$pageSize}";
            $userList = $db_output->fetchAll($sql);
            foreach ($userList as $key => $value) {
                $useridModel = new Userid();
                $insertData = array(
                    'uid' => $value['uid'],
                    'nickname' => $value['nickname'],
                    'username' => $value['username'],
                );
                $res = $useridModel->create($insertData);

                if(!$res){
                    $logger->warning('userid:'. $value['uid'] .'insert fail');
                }
            }

            $logger->debug('page:'.$page.' insert end');
        }

        $logger->debug('table insert end');
    }

    /**
     * 迁移userlogin表
     */
    public function moveuserloginAction(){

        $db_output = $this->getSourceDbConnection();

        $logFile = $this->logDir.'/moveUserLogin.log';
        $logger = new FileAdapter($logFile);

        $max_table_num = 2;
//        $max_table_num = 99;    //userauth表 的 最大表后缀
        for($table_suffix = 0; $table_suffix <= $max_table_num; $table_suffix++) {
            //拼数据库表名
            $userauth_table = 'userauth_'.str_pad($table_suffix, 2, "0", STR_PAD_LEFT);

            $logger->debug('table:'.$userauth_table.' insert start');

            //从老数据表查数据, 并插入到新的数据库
            $sql = "select * from {$userauth_table}";
            $userList = $db_output->fetchAll($sql);
            foreach ($userList as $key => $value) {
                $userLoginModel = new Userlogin();
                $insertData = array(
                    'uid' => $value['uid'],
                    'channel_id' => $this->channel_id,
                    'loginname' => $value['loginname'],
                    'password' => $value['pwd'],
                    'salt' => $value['cdkey'],
                    'partition_by' => $table_suffix,        //分表, 分区对应
                    'status' => $value['status'],
                );
                $res = $userLoginModel->create($insertData);

                if(!$res){
                    $logger->warning('table:'.$userauth_table.', userid:'. $value['uid'] .'insert fail');
                }
            }

            $logger->debug('table:'.$userauth_table.' insert end');
        }
    }

    /**
     * 迁移用户基础信息表
     */
    public function moveusersAction() {

        // 输出数据
        $db_output = $this->getSourceDbConnection();

        $logFile = $this->logDir.'/moveUsers.log';
        $logger = new FileAdapter($logFile);

        //查询用户vip信息, 记录不多, 全部查询出来, 下面用到
        $vip_sql = 'SELECT uid,param1 as activity_id,param2 as begin_time,param3 as end_time from user_profile limit 10';
        $vipUserRes = $db_output->fetchAll($vip_sql);
        $vipUserList = array();
        if(is_array($vipUserRes) && !empty($vipUserRes)){
            foreach ($vipUserRes as $vipUser) {
                $vipUserList[ $vipUser['uid'] ] = $vipUser;
            }
        }

        $max_table_num = 2;     //测试
//        $max_table_num = 99;    //user_baseinfo 的 最大表后缀
        for($table_suffix = 0; $table_suffix <= $max_table_num; $table_suffix++) {
            $user_baseinfo_table = 'user_baseinfo_'.str_pad($table_suffix, 2, "0", STR_PAD_LEFT);

            $logger->debug('table:'.$user_baseinfo_table.' insert start');

            $sql = "select * from {$user_baseinfo_table}";
            $userList = $db_output->fetchAll($sql);
            foreach ($userList as $key => $value) {
                $usersModel = new Users();
                $insertData = array(
                    'uid' => $value['uid'],
                    'channel_id' => $this->channel_id,
                    'partition_by' => $table_suffix,        //分表, 分区对应
                    'signature' => null,
                    'realname' => $value['name'],
                    'qq' => $value['qq'],
                    'regist_ip' => $value['registIp'],
                    'birthday' => $value['birthday'],
                    'regist_service' => $value['registService'],
                    'province' => $value['province'],
                    'city' => $value['city'],
                    'last_modify_pwd_time' => $value['lastModifyPwdTime'],
                    'is_vip' => null,
                    'activity_id' => null,
                    'begin_time' => null,
                    'end_time' => null,
                    'status' => $value['status'],
                    'created_at' => $value['registTime'],
                    'updated_at' => $value['lastModifyTime'],
                    'avatar' => $value['picture'],
                    'gender' => $value['gender'],
                    'grade' => 0,
                    'credits' => 0,
                    'nickname' => $value['nickname'],
                    'username' => $value['username'],
                    'email' => $value['email'],
                    'mobile' => $value['mobile'],
                );

                //检查是否是vip
                $vipInfo = $vipUserList[$value['uid']];
                if(!empty($vipInfo)){
                    $insertData['is_vip'] = 1;
                    $insertData['activity_id'] = $vipInfo['activity_id'];
                    $insertData['begin_time'] = $vipInfo['begin_time'];
                    $insertData['end_time'] = $vipInfo['end_time'];
                }
                $res = $usersModel->create($insertData);
                
                if(!$res){
                    $logger->warning('table:'.$user_baseinfo_table.', userid:'. $value['uid'] .'insert fail');
                }
            }

            $logger->debug('table:'.$user_baseinfo_table.' insert end');
        }

    }

}