<?php

/**
 * Created by PhpStorm.
 * User: xwsoul
 * Date: 15/12/4
 * Time: 下午5:19
 */
class SyncTask extends Task {

    public function testAction() {
        /**
         * @var Phalcon\Db\Adapter\Pdo\Mysql $db
         */
        $db = $this->getDI()->getShared('db2');
        $rs = $db->execute("SELECT * FROM affiliates;");
        if($rs) {
            dd($rs);
        }
    }

}