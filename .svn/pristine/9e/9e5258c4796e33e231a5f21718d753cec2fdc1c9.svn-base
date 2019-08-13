<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AdminExtYear extends Model {

    public function getSource() {
        return 'admin_ext';
    }

    public function onConstruct() {
        //使用年会数据库链接
        $this->setConnectionService('db_year');
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'admin_id', 'pinyin', 'department', 'duty', 'sort',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['admin_id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['pinyin', 'department', 'duty', 'sort',],
            MetaData::MODELS_NOT_NULL => ['admin_id', 'pinyin', 'department', 'duty', 'sort',],
            MetaData::MODELS_DATA_TYPES => [
                'admin_id' => Column::TYPE_INTEGER,
                'pinyin' => Column::TYPE_VARCHAR,
                'department' => Column::TYPE_INTEGER,
                'duty' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'admin_id', 'department', 'duty', 'sort',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'admin_id' => Column::BIND_PARAM_INT,
                'pinyin' => Column::BIND_PARAM_STR,
                'department' => Column::BIND_PARAM_INT,
                'duty' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'pinyin' => '',
                'department' => '0',
                'duty' => '0',
                'sort' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    
    
    public static function ext($admin_id){
        return self::findFirst($admin_id);
    }

    public static function findByDuty($duty_id){
        return  self::query()
            ->andCondition('duty',$duty_id)
            ->execute()->toArray();
    }

    public static function findByDept($department_id){
        return  self::query()
            ->andCondition('department',$department_id)
            ->execute()->toArray();
    }
    
    public static function resetExt($admin, $input) {
        $ext = self::query()->andCondition('admin_id', $admin->id)->first();
        if (!$ext) {
            $adminExt = new self;
            $adminExt->save([
                'admin_id' => $admin->id,
            ]);
            $ext = self::query()->andCondition('admin_id', $admin->id)->first();
        }
        return $ext->update([
                    'department' => isset($input['dept_id']) && $input['dept_id'] ? $input['dept_id'] : $ext->department,
                    'duty' => isset($input['duty_id']) && $input['duty_id'] ? $input['duty_id'] : $ext->duty,
                    'pinyin' => Cutf8py::encode($admin->name),
                    'sort' => isset($input['sort']) ? $input['sort'] : 0,
        ]);
    }
    
}