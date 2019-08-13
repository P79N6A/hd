<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class ActivitySignup extends Model {
    const INIT = 0;         //初始化
    const ACCEPT = 1;       //审核通过
    const REJECT = 2;       //拒绝
    const DELETE = 3;       //删除
    const ALL = 4;

    public static $properties = [
        'id' => 'ID',
        'channel_id' => '频道号',
        'activity_id' => '活动号',
        'mobile' => '手机号',
        'name' => '姓名',
        'user_id' => '性别',
        'user_name' => '年收入',
        'create_at' => '创建时间',
        'update_at' => '更新时间',
        'status' => '状态',
        'ext_field1' => '体重',
        'ext_field2' => '年龄',
//        'ext_fields' => '',
//        'ext_values' => '',
//        'r_name' =>'姓',
//        'r_nickname' => '名',
//        'b_year' => '年份',
//        'b_month' => '月份',
//        'b_date' => '日期',
        'r_title' => '籍贯',
        'r_height' => '身高',
        'r_degree' => '学历',
        'job' => '职业',
        'work_place' => '工作地',
        'merry' => '婚姻状况',
        'weixin' => '微信号',
        'standard' => '择偶标准',
        'introduce' => '自我介绍',
        'pay_number' => '消费额度',
        'work_picture' => '照片',
    ];

    public static $main_parameter = [
        'channel_id',
        'activity_id',
        'mobile',
        'name',
        'user_id',
        'user_name',
        'create_at',
        'update_at',
        'status',
        'ext_field1',
        'ext_field2',
        'index'
    ];

    protected static $strstatus = ['未处理', '审核通过', '拒绝', '删除', 'ALL'];

    public function getSource() {
        return 'activity_signup';
    }

    public static function apiGetActivitySignupRankingListByParameter($channel_id, $activity_arr, $parameter_name, $number = 50, $page = 1) {
        $allow = in_array($parameter_name,self::$main_parameter);
        if($allow) {
            $key = D::memKey('apiGetActivitySignupRankingListByParameter', ['channel_id' => $channel_id, 'activity_id' => implode(',',$activity_arr), 'parameter_name' => $parameter_name]);
            $data = MemcacheIO::get($key);
            if (!$data || !open_cache()) {
                $rs = self::query()
                    ->andCondition('channel_id', $channel_id)
                    ->andWhere('activity_id in ('.implode(',',$activity_arr).') and status=1')
                    ->orderBy(''.$parameter_name.' desc, update_at asc')
                    ->limit($number, ($page - 1) * $number)->execute();
                if ($rs) {
                    $data = $rs->toArray();
                    foreach ($data as $k=>$activity) {
                        foreach (json_decode($activity['ext_fields'], true) as $key => $value) {
                            if (!isset($data[$k][$key])) {
                                $data[$k][$key] = $value;
                            }
                        }
                        unset($data[$k]['ext_fields']);
                    }
                }
                MemcacheIO::set($key, $data, 30);
            }
            return $data;
        }else{
            return false;
        }
    }

    /**
     * 通过制定字段和制定字段的值，查找
     * @param int $channel_id
     * @param int $activity_id
     * @return mixed
     */
    public static function apiGetActivitySignupByParameter($channel_id, $activity_id, $parameter_name, $parameter = null) {
        $allow = in_array($parameter_name,self::$main_parameter);
        if($allow && $parameter!==null) {
            $key = D::memKey('apiGetActivitySignupByParameter', ['channel_id' => $channel_id, 'activity_id' => $activity_id, $parameter_name => $parameter]);
            $data = MemcacheIO::get($key);
            if (!$data || !open_cache()) {
                $rs = self::query()
                    ->andCondition('channel_id', $channel_id)
                    ->andCondition('activity_id', $activity_id)
                    ->andCondition($parameter_name,$parameter)
                    ->first();
                if ($rs) {
                    $data = $rs->toArray();
                    foreach (json_decode($data['ext_fields'],true) as $key=>$value){
                        if(!isset($data[$key])){$data[$key] = $value;}
                    }
                    unset($data['ext_fields']);
                }
                MemcacheIO::set($key, $data, 30);
            }
            return $data;
        }else{
            return false;
        }
    }

    /*
     * api通过名称获取报名表
     * ps:多用于名称唯一性的活动
     */
    public static function apiGetActivitySignupByName($channel_id, $activity_id, $name){
        $key = D::memKey('apiGetActivitySignupByParameter', ['channel_id' => $channel_id, 'activity_id' => $activity_id, 'name' => $name]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $rs = self::query()
                ->andCondition('channel_id', $channel_id)
                ->andCondition('activity_id', $activity_id)
                ->andCondition('name', $name)
                ->first();
            if ($rs) {
                $data = $rs->toArray();
                MemcacheIO::set($key, $data, 60);
            }else{
                $data = null;
            }
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'activity_id', 'mobile', 'name', 'user_id', 'user_name', 'create_at', 'update_at', 'status', 'ext_field1', 'ext_field2', 'ext_fields', 'ext_values', 'index'
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'activity_id', 'mobile', 'name', 'user_id', 'user_name', 'create_at', 'update_at', 'status', 'ext_field1', 'ext_field2', 'ext_fields', 'ext_values', 'index'],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'activity_id', 'mobile', 'name', 'user_name', 'create_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'activity_id' => Column::TYPE_INTEGER,
                'mobile' => Column::TYPE_VARCHAR,
                'name' => Column::TYPE_VARCHAR,
                'user_id' => Column::TYPE_INTEGER,
                'user_name' => Column::TYPE_VARCHAR,
                'create_at' => Column::TYPE_INTEGER,
                'update_at' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'ext_field1' => Column::TYPE_INTEGER,
                'ext_field2' => Column::TYPE_INTEGER,
                'ext_fields' => Column::TYPE_TEXT,
                'ext_values' => Column::TYPE_TEXT,
                'index' => Column::TYPE_INTEGER
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'activity_id', 'user_id', 'create_at', 'update_at', 'status', 'ext_field1', 'ext_field2', 'index'
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'activity_id' => Column::BIND_PARAM_INT,
                'mobile' => Column::BIND_PARAM_STR,
                'name' => Column::BIND_PARAM_STR,
                'user_id' => Column::BIND_PARAM_INT,
                'user_name' => Column::BIND_PARAM_STR,
                'create_at' => Column::BIND_PARAM_INT,
                'update_at' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'ext_field1' => Column::BIND_PARAM_INT,
                'ext_field2' => Column::BIND_PARAM_INT,
                'ext_fields' => Column::BIND_PARAM_STR,
                'ext_values' => Column::BIND_PARAM_STR,
                'index' => Column::BIND_PARAM_INT
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '0',
                'user_name' => '',
                'status' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public function addMember($data) {
        $this->assign($data);
        return $this->save();
    }

    /*
 *  user:fenggu
 *  date:2016-4-11
 *  time:19:40
 *  desc:获取频道下面指定活动的报名表是否包含相同的手机号码 *
 *
 * */

    public static function valMobileIsEmpty($channel_id, $actvity_id, $mobile) {
        $obj = self::find(
            array('conditions' => 'channel_id = :channel_id: AND activity_id = :activity_id: AND mobile = :mobile:',
                'bind' => array('channel_id' => $channel_id, 'activity_id' => $actvity_id, 'mobile' => $mobile)));
        return count($obj) > 0 ? false : true;
    }

    public static function findOneByMobile($channel_id, $activity_id, $mobile) {
        return self::query()->andCondition('channel_id',$channel_id)
            ->andCondition('activity_id',$activity_id)
            ->andCondition('mobile',$mobile)->first();
    }


    public static function getDataByActivityId($channel_id, $activity_id) {
        return ActivitySignup::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('activity_id', $activity_id)
            ->order('id DESC')
            ->paginate(20, 'Pagination');
    }

    public static function getDataBySearch($channel_id, $activity_id, $search) {
        $query = self::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('activity_id', $activity_id);
        foreach ($search as $key => $value) {
            if($key=='page') continue;
            $query = $query->andWhere($key . " like '%{$value}%' ");
        }
        return $query->order('id DESC')
            ->paginate(20, 'Pagination');

    }

    public static function getAllBySearch($channel_id, $activity_id, $search) {
        $query = self::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('activity_id', $activity_id);
        foreach ($search as $key => $value) {
            $query = $query->andWhere($key . " like '%{$value}%'");
        }
        return $query->order('id asc')
            ->execute();

    }

    public static function getExtFieldsValueById($id) {
        return self::query()->andWhere("id = :id:", array('id' => $id))->columns(array('channel_id', 'activity_id','mobile', 'name' , 'user_id', 'user_name','ext_field1' ,'ext_field2', 'ext_values', 'ext_fields'))->first();
    }

    public static function findOneObject($id) {
        $object = ActivitySignup::query()->where("id='{$id}'")->first();
        return $object;
    }

    public static function getOneBymobile($channel_id, $activity_id, $mobile) {
        return self::query()->andWhere('channel_id = :channel_id:', array('channel_id' => $channel_id))
            ->andWhere('activity_id = :activity_id:', array('activity_id' => $activity_id))
            ->andWhere("mobile = :mobile:", array('mobile' => $mobile))
            ->first()->toArray();
    }

    public function createActivitySignup($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    public static function UpdateSignupData($id, $status, $exdata) {
        $item = self::findFirst($id);
        if (count($item)) {
            $item->status = $status;
            $ex_values = json_decode($item->ext_values, true);
            foreach ($exdata as $key => $value) {
                $ex_values[$key] = $value;
            }
            $item->ext_values = json_encode($ex_values);
            $id = $item->save();
        }
    }

    /*
     * 以下方法用于拍拍浙江美
     */

    public static function getWorkList($channel_id, $activity_id, $page = 1, $pagenum = 20, $work_type = null, $order_by = null) {
        $key = D::memKey('getWorkList', ['channel_id' => $channel_id , 'activity_id' => $activity_id , 'page' => $page , 'pagenum' => $pagenum , 'work_type' => $work_type?:0]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {

            $query = self::query()
                ->andCondition('channel_id', $channel_id)
                ->andCondition('activity_id', $activity_id)
                ->andCondition('status', 1);
            if ($work_type) {
                $query = $query->andCondition('ext_field2', $work_type);
            }
            if ($order_by) {
                $order_str = $order_by . ' DESC';
            }else {
                $order_str = 'index desc,ext_field1 DESC,create_at desc';
            }
            $data = $query->orderBy($order_str)
                ->limit($pagenum, ($page - 1) * $pagenum)
                ->execute();

            $data_arr = array();
            if($data){
                $data_arr = $data->toArray();
            }
            $data = $data_arr;
            MemcacheIO::set($key, $data, 30);
        }

        return $data;
    }

    /*
     * 由于全表查询比较消耗资源所以缓存总数10分钟
     */
    public static function getWorkCount($channel_id, $activity_id, $work_type) {
        $key = 'cztv::activity_signup::work_count::' . $channel_id . '::' . $activity_id . '::' . $work_type;
        $work_count = RedisIO::get($key);
        if (!$work_count) {
            $activity_all = self::query()
                ->andCondition('channel_id', $channel_id)
                ->andCondition('activity_id', $activity_id)
                ->andCondition('ext_field2', $work_type)
                ->execute()->toArray();
            $work_count = count($activity_all);
            RedisIO::set($key, $work_count, 60 * 10);
        }
        return $work_count;
    }

    /**
     * 来源为0时为总榜，1时为pc端排行，2时为h5排行
     * @param $channel_id
     * @param $activity_id
     * @param int $source
     * @return array|mixed
     */
    public static function getWorkRanking($channel_id, $activity_id, $source = 0) {
        $key = 'cztv::activity_signup::work_ranking::' . $channel_id . '::' . $activity_id . '::' . $source;
        $work_ranking = MemcacheIO::get($key);
        if (!$work_ranking) {
            $query = self::query()->columns(array('id'))
                ->andCondition('channel_id', $channel_id)
                ->andCondition('activity_id', $activity_id);
            if ($source != 0) {
                $query = $query->andCondition('user_id', $source);
            }
            $return = $query->andCondition('status', 1)->orderBy('ext_field1 DESC')->execute()->toArray();
            $work_ranking = array();
            foreach ($return as $a => $b) {
                $work_ranking[] = $b['id'];
            }
            MemcacheIO::set($key, $work_ranking, 30);
        }
        return $work_ranking;
    }

    public static function getWorkById($id) {
        $key = D::memKey('getWorkById', ['id' => $id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()->andCondition('id', $id)->first();
            $data_arr = array();
            if($data){
                $data_arr = $data->toArray();
            }
            $data = $data_arr;
            MemcacheIO::set($key, $data, 30);
        }
        return $data;
    }

    public static function upWorkById($id) {
        $work = self::query()->andCondition('id', $id)->first();
        if($work) {
            $work->ext_field1++;
            $work->update_at = time();
            return ($work->save()) ? true : false;
        }
        return false;
    }

    public static function setWorkVote($id, $work_vote) {
        $work = self::query()->andCondition('id', $id)->first();
        if($work&&($work_vote>$work->ext_field1)) {
            $work->ext_field1 = $work_vote;
            $work->update_at = time();
            return ($work->save()) ? true : false;
        }
        return false;
    }

}