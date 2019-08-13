<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Baoliao extends Model {
    const ALL = -1;
    const DELETE = 0;
    const ACCEPT = 1;
    const UNCHACKED = 2;
    const REJECT = 3;

    const PAGE_SIZE = 50;


    public function getSource() {
        return 'baoliao';
    }

    /**
     * @param $channel_id
     * @param $user_id
     * @param $per_page
     * @param $page
     * @return mixed
     */
    public static function apiGetBaoliaoByUser($channel_id, $user_id, $per_page, $page) {

        $keymain = "baoliao_user_id:" . $user_id;
        $datamain = MemcacheIO::get($keymain);
        if(!$datamain) {
            for($i=0; $i<10; $i++) {
                $key = D::memKey('apiGetBaoliaoByUser', [
                    'channel_id' => $channel_id,
                    'user_id' => $user_id,
                    'per_page' => $per_page,
                    'page' => $i,
                ]);
                MemcacheIO::set($key, false, 86400);
            }
            MemcacheIO::set($keymain, true, 86400);
        }
        $key = D::memKey('apiGetBaoliaoByUser', [
            'channel_id' => $channel_id,
            'user_id' => $user_id,
            'per_page' => $per_page,
            'page' => $page,
        ]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $query = self::query()
                ->columns(['Baoliao.id,Baoliao.title,Baoliao.content,Baoliao.status,BaoliaoReply.reply'])
                ->leftJoin("BaoliaoReply", "Baoliao.id = BaoliaoReply.baoliao_id")
                ->andWhere("Baoliao.channel_id = {$channel_id}")
                ->andWhere("Baoliao.user_id = {$user_id}")
                ->andWhere("Baoliao.status > 0 ");
            $query = $query->orderBy('Baoliao.create_at desc');
            $rs = $query
                ->paginate($per_page, '\GenialCloud\Helper\Pagination', $page)
                ->models;
            if (!empty($rs)) {
                $data = $rs->toArray();
            }
            MemcacheIO::set($key, $data);
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'title', 'content', 'user_id', 'username', 'create_at', 'status', 'client', 'ip',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'title', 'content', 'user_id', 'username', 'create_at', 'status', 'client', 'ip',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'title', 'content', 'user_id', 'username', 'create_at', 'status', 'client', 'ip',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'content' => Column::TYPE_TEXT,
                'user_id' => Column::TYPE_INTEGER,
                'username' => Column::TYPE_VARCHAR,
                'create_at' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'client' => Column::TYPE_INTEGER,
                'ip' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'user_id', 'create_at', 'status', 'client',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'content' => Column::BIND_PARAM_STR,
                'user_id' => Column::BIND_PARAM_INT,
                'username' => Column::BIND_PARAM_STR,
                'create_at' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'client' => Column::BIND_PARAM_INT,
                'ip' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'client' => 'web',
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * 验证字段有效性
     * @param $input
     * @return mixed
     */
    public static function makeValidator($input) {
        return Validator::make(
            $input,
            [
                'channel_id' => 'required',
                'title' => 'required',
                'content' => 'required',
                'user_id' => 'required',
                'username' => 'required|max:30',
            ],
            [
                'channel_id.required' => '频道ID必填',
                'title.required' => '内容必填',
                'content.required' => '内容必填',
                'user_id.required' => '用户ID必填',
                'username.required' => '用户名必填',
                'username.max' => '用户名最长30字符',
            ]
        );
    }

    public function createBaoliao($data) {
        $this->assign($data);
        $this->create_at = time();
        $this->status = -1;
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    private static function getOne($id) {
        return Baoliao::findFirst(array(
            'id = :id:',
            'bind' => array('id' => $id)
        ));
    }

    public static function getBaoliaoByUser($user_id, $channel_id) {
        return Baoliao::query()
            ->columns(array('Baoliao.*', 'BaoliaoAttachment.*'))
            ->leftJoin("BaoliaoAttachment", "BaoliaoAttachment.baoliao_id=Baoliao.id")
            ->where("user_id = '{$user_id}' and channel_id = '{$channel_id}'")
            ->order('Baoliao.id desc')->paginate(10, 'Pagination');
    }

    public function deleteBaoliao() {
        return $this->changeStatus(Baoliao::DELETE);
    }

    public function changeStatus($status) {
        $this->status = $status;
        return $this->save();
    }

    public static function getBaoliaoList($channel_id, $status = null, $content = null) {
        $conditions = "channel_id={$channel_id} AND status != 0";
        if ($status != Baoliao::ALL) {
            $conditions = "channel_id={$channel_id} AND status = {$status}";
        }
        if ($content != null) {
            $conditions = $conditions . " AND ( title like '%{$content}%' or content like '%{$content}%')";
        }

        return Baoliao::query()
            ->where($conditions)
            ->orderBy('create_at desc')
            ->paginate(Baoliao::PAGE_SIZE, 'Pagination');
    }

    /**
     * @param $channel_id
     * @param $type
     * @param $per_page
     * @param $page
     * @return array|mixed
     */
    public static function apiGetBaoLiao($channel_id, $per_page, $page) {
        $key = D::memKey('apiGetActivity', ['channel_id' => $channel_id, 'per_page' => $per_page, 'page' => $page]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $query = self::query()->andCondition('channel_id', $channel_id);
            $query->orderBy('create_at DESC');
            $data = $query->limit($per_page, $per_page * ($page - 1))
                ->execute()
                ->toArray();
            MemcacheIO::set($key, $data, 1800);
        }
        return $data;
    }

    public static function getName($baoliao_id) {
        $result = self::query()->where("id = $baoliao_id")->execute()->toarray();
        return $result;
    }
}