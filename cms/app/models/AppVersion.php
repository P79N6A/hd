<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AppVersion extends Model {

    const CHECKED = 1;
    const UNCHECKED = 0;
    const PUBLISHED = 1;
    const UNPUBLISHED = 0;

    const ANDROID = 1;
    const IOS = 2;

    public function getSource() {
        return 'app_version';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'app_id', 'type', 'name', 'logo', 'newfeature', 'version', 'apk', 'url', 'status', 'publish', 'downloads', 'publishtime', 'created_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['app_id', 'type', 'name', 'logo', 'newfeature', 'version', 'apk', 'url', 'status', 'publish', 'downloads', 'publishtime', 'created_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'app_id', 'type', 'name', 'logo', 'version', 'status', 'publish', 'downloads', 'publishtime', 'created_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'app_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'logo' => Column::TYPE_VARCHAR,
                'newfeature' => Column::TYPE_VARCHAR,
                'version' => Column::TYPE_VARCHAR,
                'apk' => Column::TYPE_VARCHAR,
                'url' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
                'publish' => Column::TYPE_INTEGER,
                'downloads' => Column::TYPE_INTEGER,
                'publishtime' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'app_id', 'type', 'type', 'status', 'publish', 'downloads', 'publishtime', 'created_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'app_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'logo' => Column::BIND_PARAM_STR,
                'newfeature' => Column::BIND_PARAM_STR,
                'version' => Column::BIND_PARAM_STR,
                'apk' => Column::BIND_PARAM_STR,
                'url' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'publish' => Column::BIND_PARAM_INT,
                'downloads' => Column::BIND_PARAM_INT,
                'publishtime' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
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

    public static function findAll($app_id, $type) {
        return AppVersion::query()->where("app_id = '{$app_id}' and type=" . $type)->order('id desc')->paginate(10, 'Pagination');
    }

    public static function getLastVersion($app_id, $app_type) {
        $type = ($app_type == "android") ? 1 : 2;
        $versiondata = self::query()
            ->andCondition('app_id', $app_id)
            ->andCondition('type', $type)
            ->orderBy('id desc')
            ->first();
        return $versiondata;
    }

    public static function getOneByVersion($app_id, $type, $version) {
        return self::query()->andCondition('app_id', $app_id)
            ->andCondition('type', $type)
            ->andCondition('version', $version)
            ->first();
    }

    public static function getOne($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        return AppVersion::findFirst($parameters);
    }

    public function updateVersion($data) {
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


    public function createVersion($data) {
        $this->assign($data);
        $this->author_id = Session::get('user')->id;
        $this->created_at = time();
        $this->status = 0;
        $this->publish = 0;
        $this->downloads = 0;
        $this->publishtime = 0;
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public function changeStatus($status) {
        $this->status = $status;
        return $this->save();
    }


    public function changePublish($publish) {
        $this->publish = $publish;
        if ($publish == 1) $this->publishtime = time();
        return $this->save();
    }


    public static function makeValidators($inputs) {
        return Validator::make(
            $inputs, [
            'name' => 'required',
            'version' => 'required',
            'newfeature' => 'required',
            'url' => 'required',
        ], [
            'name.required' => '请填写应用名',
            'version.required' => '请填写版本号',
            'newfeature.required' => '请填写新特性',
            'url.required' => '请填写url 或 上传apk',
        ]);
    }

    public static function deleteVersion($id, $channel_id) {
        $version = AppVersion::findFirst(array(
            'id=:id:',
            'bind' => array('id' => $id)
        ));
        if ($version) {
            $app = AppList::getApp($version->app_id, $channel_id);
            if ($app) {
                return $version->delete();
            }
        }
        return false;
    }
}