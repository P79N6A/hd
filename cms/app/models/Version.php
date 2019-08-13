<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Version extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'version';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'name', 'content', 'updated_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['name', 'content', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'name', 'updated_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'content' => Column::TYPE_TEXT,
                'updated_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'updated_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'content' => Column::BIND_PARAM_STR,
                'updated_at' => Column::BIND_PARAM_INT,
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
        return Version::query()->orderBy('updated_at desc, id desc')->paginate(Version::PAGE_SIZE, 'Pagination');
    }

    public static function getOne($versionid) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $versionid;
        return Version::findFirst($parameters);
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

    public function saveVersion($data) {
        $this->name = $data['name'];
        $this->updated_at = $data['updated_at'];
        $this->content = $data['content'];
        $this->sort = 0;
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

    public function createVersion($data) {
        $this->name = $data['name'];
        $this->updated_at = $data['updated_at'];
        $this->content = $data['content'];
        $this->sort = isset($data['sort']) ? $data['sort'] : 0;
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

    public function modifyVersion($data) {
        if (isset($data['name']))
            $this->name = $data['name'];
        if (isset($data['updated_at']))
            $this->updated_at = $data['updated_at'];
        if (isset($data['content']))
            $this->content = $data['content'];
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

}