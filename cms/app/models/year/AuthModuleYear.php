<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AuthModuleYear extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'auth_module';
    }

    public function onConstruct() {
        //使用年会数据库链接
        $this->setConnectionService('db_year');
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'name', 'channel_id', 'child', 'css', 'sort',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['name', 'channel_id', 'child', 'css', 'sort',],
            MetaData::MODELS_NOT_NULL => ['id', 'name', 'channel_id', 'child', 'css', 'sort',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'channel_id' => Column::TYPE_INTEGER,
                'child' => Column::TYPE_TEXT,
                'css' => Column::TYPE_VARCHAR,
                'sort' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'sort',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'channel_id' => Column::BIND_PARAM_INT,
                'child' => Column::BIND_PARAM_STR,
                'css' => Column::BIND_PARAM_STR,
                'sort' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '0',
                'sort' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }


    public function getChannel() {
        $channel = ChannelYear::getOneChannel($this->channel_id);
        if($this->channel_id==0) {
            return "System";
        }
        else {
            return $channel->name;
        }
    }
    public static function getAll(){
        return self::query()
            ->where("(channel_id=0 or channel_id=".Session::get('user')->channel_id.")")
            ->orderBy('sort DESC')->execute()->toArray();
    }

    public static function getOne($moduleid) {
        $parameters = array();
        $parameters['conditions'] = "id=".$moduleid;
        return self::findFirst($parameters);
    }

    public static function findAll() {
        return self::query()->paginate(AuthModule::PAGE_SIZE,'Pagination');
    }

    public static function makeValidator($input) {
        return Validator::make(
            $input, [
            'name' => 'required',
            'css' => 'required',
        ], [
                'name.required' => '模块名称必填',
                'css.required' => '图标必填',
            ]
        );
    }

    public function saveModule($data) {
        $this->channel_id = $data['channel_id'];
        $this->name = $data['name'];
        $this->css = $data['css'];
        $this->child = "0";
        if ($this->save()) {
            $msg[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $msg[] = $m->getMessage();
            }
        }
        return $msg;
    }
    public static function getOneName($moduleid) {
        $result = self::query()->where("id = '{$moduleid}'")->first();
        return $result;
    }
}