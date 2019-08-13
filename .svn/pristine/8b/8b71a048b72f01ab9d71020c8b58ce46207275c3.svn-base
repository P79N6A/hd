<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Blocks extends Model {

    use HasChannel;

    const PAGE_SIZE = 50;

    public function getSource() {
        return 'blocks';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'template_id', 'code', 'category_id', 'region_id', 'description', 'author_id', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'template_id', 'code', 'category_id', 'region_id', 'description', 'author_id', 'created_at', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'template_id', 'code', 'category_id', 'region_id', 'description', 'author_id', 'created_at', 'updated_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'template_id' => Column::TYPE_INTEGER,
                'code' => Column::TYPE_VARCHAR,
                'category_id' => Column::TYPE_INTEGER,
                'region_id' => Column::TYPE_INTEGER,
                'description' => Column::TYPE_VARCHAR,
                'author_id' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'template_id', 'category_id', 'region_id', 'author_id', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'template_id' => Column::BIND_PARAM_INT,
                'code' => Column::BIND_PARAM_STR,
                'category_id' => Column::BIND_PARAM_INT,
                'region_id' => Column::BIND_PARAM_INT,
                'description' => Column::BIND_PARAM_STR,
                'author_id' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'template_id' => '0',
                'category_id' => '0',
                'region_id' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function makeValidator($input, $excluded_id = 0) {
        $channel_id = Session::get("user")->channel_id;
        return Validator::make(
            $input, [
            'code' => "required|unique:blocks,code,{$excluded_id},id,channel_id,{$channel_id}",
            'description' => 'required',
        ], [
                'code.required' => '块标识必填',
                'code.unique' => '块标识已存在',
                'description.required' => '块描述必填',
            ]
        );
    }

    public static function getOne($block_id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $block_id;
        return Blocks::findFirst($parameters);
    }

    public function deleteBlock() {
        if (BlockValues::countValues($this->id)) {
            return false;
        } else {
            return $this->delete();
        }
    }

    public function createBlock($data) {
        $this->assign($data);
        $this->channel_id = Session::get('user')->channel_id;
        $this->author_id = Session::get('user')->id;
        $this->created_at = time();
        $this->updated_at = time();
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public function modifyBlock($data) {
        if (isset($data['code']))
            $this->code = $data['code'];
        if (isset($data['description']))
            $this->description = $data['description'];
        if ($this->save()) {
            $msg[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $msg[] = $m->getMessage();
            }
        }
        return $msg;
    }

    public static function getBlockList($channel_id, $code = null) {
        $conditions = "Blocks.channel_id={$channel_id} ";
        if ($code != null) {
            $conditions = $conditions . " AND ( title like '%{$code}%' or code like '%{$code}%')";
        }
        return Blocks::query()
            ->columns(array('Blocks.*', 'Admin.*'))
            ->where($conditions)
            ->leftJoin("Admin", "Blocks.author_id=Admin.id")
            ->orderBy('created_at')
            ->paginate(Blocks::PAGE_SIZE, 'Pagination');
    }

}