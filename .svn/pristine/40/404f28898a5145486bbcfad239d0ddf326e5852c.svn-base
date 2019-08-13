<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class StaticFiles extends Model {

    public function getSource() {
        return 'static_files';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'category_id', 'data_id', 'author_id', 'father', 'is_folder', 'name', 'type', 'path', 'status', 'partition_by', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'category_id', 'data_id', 'author_id', 'father', 'is_folder', 'name', 'type', 'path', 'status', 'created_at', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'category_id', 'data_id', 'author_id', 'father', 'is_folder', 'name', 'status', 'partition_by', 'created_at', 'updated_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'category_id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'author_id' => Column::TYPE_INTEGER,
                'father' => Column::TYPE_INTEGER,
                'is_folder' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'type' => Column::TYPE_INTEGER,
                'path' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'category_id', 'data_id', 'author_id', 'father', 'is_folder', 'type', 'status', 'partition_by', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'category_id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'author_id' => Column::BIND_PARAM_INT,
                'father' => Column::BIND_PARAM_INT,
                'is_folder' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_INT,
                'path' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'category_id' => '0',
                'data_id' => '0',
                'father' => '0',
                'is_folder' => '0',
                'status' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**

     * 获取文件类型

     * @param string $filename 文件名称

     * @return string 文件类型

     */

    public static function   getFileType($filename) {

        return substr($filename, strrpos($filename, '.') + 1);

    }

    public static function deletepath($id) {
        $ids = StaticFiles::getIdsByfather($id);
        array_push($ids, $id);
        $files = self::query()
            ->inWhere('id', $ids)
            ->execute();
        foreach ($files as $f) {
            $f->delete();
        }
        return true;
    }

    public static function getIdsByfather($father) {
        $ids = [];
        $childrens = StaticFiles::getFilesByFather($father);
        foreach($childrens as $c) {
            array_push($ids, $c['id']);
            foreach(self::getIdsByfather($c['id']) as $v) {
                array_push($ids, $v);
            }
        }
        return $ids;
    }

    public static function savepath($path) {
        if($path) {
            $path_parts = explode('/', $path);
            $father = 0;
            $channel_id = $path_parts[0];
            $data_id = $path_parts[1];
            $author_id = Session::get('user')->id;
            $length = count($path_parts);
            foreach($path_parts as $key=> $path_part) {
                $model = new StaticFiles();
                $staticfile = $model->checkUnique($channel_id, $data_id, $father,  $path_part);
                if(!$staticfile) {
                    $data = array(
                        'channel_id' => $channel_id,
                        'category_id' => 0,
                        'data_id' => $data_id,
                        'author_id' => $author_id,
                        'father' => $father,
                        'is_folder' => ($key==($length-1))?0:1,
                        'name' => $path_part,
                        'type' => StaticFiles::getFileType($path_part),
                        'path' => ($key==($length-1))?$path:"",
                        'status' => 1,
                        'partition_by' => date("Y"),
                        'created_at' => time(),
                        'updated_at' => time(),
                    );
                    $father = $model->saveGetId($data);
                }
                else {
                    $father = $staticfile->id;
                    $staticfile->updated_at = time();
                    $staticfile->save();
                }
            }
        }
    }

    public static function getFilesByData($data_id) {
        return self::query()
            ->andCondition('data_id', $data_id)
            ->andCondition('status', 1)
            ->execute()->toArray();
    }

    public static function getFilesByFather($id) {
        return self::query()
            ->andCondition('father', $id)
            ->execute()->toArray();
    }


    public function checkUnique($channel_id, $data_id, $father,  $name) {
        return self::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('data_id', $data_id)
            ->andCondition('father', $father)
            ->andCondition('name', $name)
            ->first();
    }

    /**
     * 获取上传文件信息
     * @param unknown $data_id
     */
	public static function getDataById($data_id) {
		return self::query()
		->andCondition('data_id', $data_id)
		->andCondition('is_folder', 0)
		->execute()->toArray();
	}
}