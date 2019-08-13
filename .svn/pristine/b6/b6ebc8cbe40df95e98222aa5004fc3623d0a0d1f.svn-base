<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Payment extends Model {
    const PAGE_SIZE = 20;

    public function getSource() {
        return 'payment';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'uid', 'order_id', 'channel_id', 'app_short_name', 'created_at', 'order_no', 'amount', 'charge_way', 'pay_account', 'rec_account', 'charget_datetime', 'charge_serial_no', 'return_mess', 'state',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['uid', 'order_id', 'channel_id', 'app_short_name', 'created_at', 'order_no', 'amount', 'charge_way', 'pay_account', 'rec_account', 'charget_datetime', 'charge_serial_no', 'return_mess', 'state',],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'order_id' => Column::TYPE_INTEGER,
                'uid' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'app_short_name' => Column::TYPE_VARCHAR,
                'created_at' => Column::TYPE_INTEGER,
                'order_no' => Column::TYPE_VARCHAR,
                'amount' => Column::TYPE_VARCHAR,
                'charge_way' => Column::TYPE_VARCHAR,
                'pay_account' => Column::TYPE_VARCHAR,
                'rec_account' => Column::TYPE_VARCHAR,
                'charget_datetime' => Column::TYPE_INTEGER,
                'charge_serial_no' => Column::TYPE_VARCHAR,
                'return_mess' => Column::TYPE_VARCHAR,
                'state' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'order_id', 'uid', 'channel_id', 'created_at', 'charget_datetime', 'state',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'order_id' => Column::BIND_PARAM_INT,
                'uid' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'app_short_name' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'order_no' => Column::BIND_PARAM_STR,
                'amount' => Column::BIND_PARAM_STR,
                'charge_way' => Column::BIND_PARAM_STR,
                'pay_account' => Column::BIND_PARAM_STR,
                'rec_account' => Column::BIND_PARAM_STR,
                'charget_datetime' => Column::BIND_PARAM_INT,
                'charge_serial_no' => Column::BIND_PARAM_STR,
                'return_mess' => Column::BIND_PARAM_STR,
                'state' => Column::BIND_PARAM_INT,
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

    /*
     * @desc 根据支付订单编号获取支付信息
     * @author fenggu
     * @date 2016-6-20 1:47
     * @param $orderno 订单编号
     * */
    public static function getItemByOrder($orderno) {
        return Payment::query()->where("order_no= '$orderno' ")->first();
    }

    public static function findAll($conditions = []){
        $criteria = Payment::query();

        if(!empty($conditions))
        {
            foreach($conditions as $c)
            {
                $criteria->andWhere($c);
            }
        }
        return $criteria->order("id desc")->paginate(Payment::PAGE_SIZE, 'Pagination');
    }

    /*
     * @Desc 第三方交易号获取订单信息
     * @param $trade_no string 第三方订单编号
     * @param $chare_way string 第三方支付方式 'applepay' 'weixinpay' 'alipay'
     *
     * */
    public static function findOneByTradeNo($trade_no,$charge_way)
    {
        $criterial = self::query();
        return $criterial->where("charge_serial_no = '{$trade_no}'")
                         ->andWhere("charge_way = '$charge_way'")
                         ->first();
    }


}