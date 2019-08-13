<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AppList extends Model {

    public function getSource() {
        return 'app_list';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'bundleid', 'channel_id', 'name', 'intro', 'sku', 'author_id', 'version_android', 'version_ios', 'logo', 'copyright',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['bundleid', 'channel_id', 'name', 'intro', 'sku', 'author_id', 'version_android', 'version_ios', 'logo', 'copyright',],
            MetaData::MODELS_NOT_NULL => ['id', 'bundleid', 'channel_id', 'name', 'sku', 'author_id', 'version_android', 'version_ios',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'bundleid' => Column::TYPE_VARCHAR,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'intro' => Column::TYPE_VARCHAR,
                'sku' => Column::TYPE_VARCHAR,
                'author_id' => Column::TYPE_VARCHAR,
                'version_android' => Column::TYPE_VARCHAR,
                'version_ios' => Column::TYPE_VARCHAR,
                'logo' => Column::TYPE_VARCHAR,
                'copyright' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'author_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'bundleid' => Column::BIND_PARAM_STR,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'intro' => Column::BIND_PARAM_STR,
                'sku' => Column::BIND_PARAM_STR,
                'author_id' => Column::BIND_PARAM_STR,
                'version_android' => Column::BIND_PARAM_STR,
                'version_ios' => Column::BIND_PARAM_STR,
                'logo' => Column::BIND_PARAM_STR,
                'copyright' => Column::BIND_PARAM_STR,
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

    public static function makeValidators($inputs) {
        return Validator::make(
            $inputs, [
            'name' => 'required',
            'bundleid' => 'required',
            'sku' => 'required',
            'copyright' => 'required',
            'channel_id' => 'required',
        ], [
            'name.required' => '请填写应用名',
            'intro.required' => '请填写应用介绍',
            'bundleid.required' => '请填写APP ID',
            'sku.required' => '请填写sku',
            'copyright.required' => '请填写copyright',
            'channel_id.required' => '请填写channel_id'
        ]);
    }

    public function updateApp($data) {
        $this->assign($data);
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public function createApp($data) {
        $this->assign($data);
        $this->version_android = 0;
        $this->version_ios = 0;
        $this->author_id = Session::get('user')->id;
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public static function deleteApp($app_id, $channel_id) {
        $app = AppList::findFirst(array(
            'id=:app_id: AND channel_id=:channel_id:',
            'bind' => array('app_id' => $app_id, 'channel_id' => $channel_id)
        ));
        if ($app) {
            $items = AppVersion::find(array(
                'app_id=:app_id:',
                'bind' => array('app_id' => $app_id)
            ));

            foreach ($items as $item) {
                $item->delete();
            }

            return $app->delete();
        }
        return false;
    }

    public static function getAppsByChannelId($channel_id) {
        return AppList::query()
            ->where("channel_id={$channel_id}")
            ->paginate(50, 'Pagination');
    }


    public static function getApp($app_id, $channel_id) {
        return AppList::findFirst(array(
            'id=:id: AND channel_id=:channel_id:',
            'bind' => array('id' => $app_id, 'channel_id' => $channel_id)
        ));
    }

    public static function getAppBySku($sku) {
        return AppList::findFirst(array(
            'sku=:sku:',
            'bind' => array('sku' => $sku)
        ));
    }

}