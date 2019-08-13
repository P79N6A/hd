<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Duty extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'duty';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'sort',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'sort',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'sort',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'name', 'sort',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
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

    public static function findAll() {
        return Duty::query()->where('channel_id=' . Session::get('user')->channel_id)->paginate(Duty::PAGE_SIZE, 'Pagination');
    }

    public static function getOne($dutyid) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $dutyid;
        return Duty::findFirst($parameters);
    }

    public function deleteDuty() {
        return $this->delete();
    }

    public static function makeValidator($input) {
        $validator = Validator::make(
            $input, [
            'name' => "required",
        ], [
                'name.required' => '不能为空',
            ]
        );
        return $validator;
    }

    public function saveDuty($data) {
        $this->channel_id = $data['channel_id'];
        $this->name = $data['name'];
        $this->sort = 1;
        $msg = [];
        if ($this->save()) {
            $msg[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $msg[] = $m->getMessage();
            }
        }
        return $msg;
    }

    public function createDuty($data) {
        $this->channel_id = $data['channel_id'];
        $this->name = $data['name'];
        $this->sort = isset($data['sort']) ? $data['sort'] : 1;
        $msg = [];
        if ($this->save()) {
            $msg[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $msg[] = $m->getMessage();
            }
        }
        return $msg;
    }

    public function modifyDuty($data) {
        if (isset($data['name']))
            $this->name = $data['name'];
        if (isset($data['sort']))
            $this->sort = $data['sort'];
        $msg = [];
        if ($this->save()) {
            $msg[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $msg[] = $m->getMessage();
            }
        }
        return $msg;
    }

    // 获取当前频道的岗位表
    public static function dutyList() {
        $data = self::query()->andCondition('channel_id', Session::get('user')->channel_id)->orderBy('sort desc')->execute()->toArray();
        $return = [];
        if (!empty($data)) {
            $return = array_refine($data, 'id', 'name');
        }
        return $return;
    }

}