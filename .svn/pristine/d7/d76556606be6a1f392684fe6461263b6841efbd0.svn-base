<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class BlockValues extends Model {

    const PAGE_SIZE = 50;

    public function getSource() {
        return 'block_values';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'block_id', 'name', 'value', 'upload_value',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['block_id', 'name', 'value', 'upload_value',],
            MetaData::MODELS_NOT_NULL => ['id', 'block_id', 'name', 'value', 'upload_value',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'block_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'value' => Column::TYPE_TEXT,
                'upload_value' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'block_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'block_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'value' => Column::BIND_PARAM_STR,
                'upload_value' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'upload_value' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function makeValidator($input, $block_id, $excluded_id = 0) {
        return Validator::make(
            $input, [
            'name' => "required|unique:block_values,name,{$excluded_id},id,block_id,{$block_id}",
        ], [
                'name.required' => '区块名称必填',
                'name.unique' => '区块名称已存在',
            ]
        );
    }

    public static function getOne($value_id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $value_id;
        return BlockValues::findFirst($parameters);
    }

    public function createBlockValue($data) {
        $this->assign($data);
        $this->block_id = $data['block_id'];

        for ($i = 0; $i < 10; $i++) {
            if (!isset($data['block_title-' . $i])) break;
            if (isset($data['imageurl-' . $i])) {
                $arr[] = array('title' => $data['block_title-' . $i], 'image' => $data['imageurl-' . $i][0], 'link' => $data['block_link-' . $i], 'desc' => $data['block_desc-' . $i]);
            } else {
                $arr[] = array('title' => $data['block_title-' . $i], 'link' => $data['block_link-' . $i], 'desc' => $data['block_desc-' . $i]);
            }
        }

        $this->value = json_encode($arr);
        $messages = [];
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public function modifyBlockValue($data) {
        $this->assign($data);

        $this->block_id = $data['block_id'];
        $values = json_decode($block_value->value);
        for ($i = 0; $i < 10; $i++) {
            if (!isset($data['block_title-' . $i])) break;
            if (isset($data['imageurl-' . $i]) || $values[$i]->image) {
                if (isset($data['imageurl-' . $i])) {
                    $arr[] = array('title' => $data['block_title-' . $i], 'image' => $data['imageurl-' . $i][0], 'link' => $data['block_link-' . $i], 'desc' => $data['block_desc-' . $i]);
                } else {
                    $arr[] = array('title' => $data['block_title-' . $i], 'image' => $values[$i]->image, 'link' => $data['block_link-' . $i], 'desc' => $data['block_desc-' . $i]);
                }
            } else {
                $arr[] = array('title' => $data['block_title-' . $i], 'link' => $data['block_link-' . $i], 'desc' => $data['block_desc-' . $i]);
            }
        }
        $this->value = json_encode($arr);
        $messages = [];
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;

    }

    public static function getBlockValues($block_id) {
        $conditions = "block_id={$block_id} ";
        return BlockValues::query()
            ->where($conditions)
            ->paginate(BlockValues::PAGE_SIZE, 'Pagination');
    }

    public static function countValues($block_id) {
        $parameters = array();
        $parameters['conditions'] = "block_id={$block_id} ";
        $blockvalues = BlockValues::find($parameters);
        return count($blockvalues);
    }

}