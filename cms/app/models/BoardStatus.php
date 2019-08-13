<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class BoardStatus extends Model {

    public function getSource() {
        return 'board_status';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'board_id', 'admin_id', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['board_id', 'admin_id', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'board_id', 'admin_id', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'board_id' => Column::TYPE_INTEGER,
                'admin_id' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'board_id', 'admin_id', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'board_id' => Column::BIND_PARAM_INT,
                'admin_id' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [

            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /*
     * 获取一个留言板，准确定位
     */
    public static function getOneBoardStatus($board_id, $admin_id) {
        $parameters = array();
        $parameters['conditions'] = "board_id=" . $board_id . " AND admin_id=" . $admin_id;
        return BoardStatus::findFirst($parameters);
    }

    /*
     * 获取一个用户的所有留言板
     */
    public static function getBoardStatusByAdmin($admin_id) {
        $data = BoardStatus::query()
            ->columns(array('Board.*', 'BoardStatus.*'))
            ->where("BoardStatus.admin_id = {$admin_id}")
            ->leftjoin('Board', 'BoardStatus.board_id=Board.id')
            ->orderBy('BoardStatus.status ASC')
            ->execute()
            ->toArray();
        return $data;
    }

    /*
     * 获取一个留言板的所有参与用户
     */
    public static function getAdminByBoard($board_id) {
        $data = BoardStatus::query()
            ->columns(array('BoardStatus.*', 'Admin.id', 'Admin.name'))
            ->where("BoardStatus.board_id = {$board_id}")
            ->leftjoin('Admin', 'BoardStatus.admin_id=Admin.id')
            ->execute()->toArray();
        return $data;
    }

    /*
     * 修改一个留言板所有用户为未读（除了发送者）
     */
    public function modifyStatusAll($board_id, $admin_id) {
        $phql = "UPDATE BoardStatus SET status = 1 WHERE board_id = ?0 AND admin_id != ?1";
        $this->modelsManager->executeQuery(
            $phql,
            array(
                0 => $board_id,
                1 => $admin_id
            )
        );
    }

    /*
     * 修改一个用户未读的留言板全改为已读
     */
    public function modifyStatusRead($admin_id) {
        $phql = "UPDATE BoardStatus SET status = 2 WHERE admin_id = ?0 AND status = 1";
        $this->modelsManager->executeQuery(
            $phql,
            array(
                0 => $admin_id
            )
        );
    }

    /*
     * 修改一个用户已读的留言板全改为清空
     */
    public function modifyStatusClear($admin_id) {
        $phql = "UPDATE BoardStatus SET status = 3 WHERE admin_id = ?0 AND status = 2";
        $this->modelsManager->executeQuery(
            $phql,
            array(
                0 => $admin_id
            )
        );
    }

    public function modifyBoardStatus($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    public function createBoardStatus($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

}