<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class DataStatistics extends Model
{
    //定时更新data_id列表
    const QUEUEDATAID = "queueDataId";

    public function getSource()
    {
        return 'data_statistics';
    }

    public function metaData()
    {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'data_id', 'hits', 'hits_fake', 'likes', 'likes_fake', 'shares', 'shares_fake', 'comments', 'comments_fake', 'formulas', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['data_id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['hits', 'hits_fake', 'likes', 'likes_fake', 'shares', 'shares_fake', 'comments', 'comments_fake', 'formulas',],
            MetaData::MODELS_NOT_NULL => ['data_id', 'hits', 'hits_fake', 'likes', 'likes_fake', 'shares', 'shares_fake', 'comments', 'comments_fake', 'formulas', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'data_id' => Column::TYPE_INTEGER,
                'hits' => Column::TYPE_INTEGER,
                'hits_fake' => Column::TYPE_INTEGER,
                'likes' => Column::TYPE_INTEGER,
                'likes_fake' => Column::TYPE_INTEGER,
                'shares' => Column::TYPE_INTEGER,
                'shares_fake' => Column::TYPE_INTEGER,
                'comments' => Column::TYPE_INTEGER,
                'comments_fake' => Column::TYPE_INTEGER,
                'formulas' => Column::TYPE_VARCHAR,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'data_id', 'hits', 'hits_fake', 'likes', 'likes_fake', 'shares', 'shares_fake', 'comments', 'comments_fake', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'data_id' => Column::BIND_PARAM_INT,
                'hits' => Column::BIND_PARAM_INT,
                'hits_fake' => Column::BIND_PARAM_INT,
                'likes' => Column::BIND_PARAM_INT,
                'likes_fake' => Column::BIND_PARAM_INT,
                'shares' => Column::BIND_PARAM_INT,
                'shares_fake' => Column::BIND_PARAM_INT,
                'comments' => Column::BIND_PARAM_INT,
                'comments_fake' => Column::BIND_PARAM_INT,
                'formulas' => Column::BIND_PARAM_STR,
                'partition_by' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'hits' => '0',
                'hits_fake' => '0',
                'likes' => '0',
                'likes_fake' => '0',
                'shares' => '0',
                'shares_fake' => '0',
                'comments' => '0',
                'comments_fake' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * 更新或新增统计
     */
    public static function updateByDataId($data_id)
    {
        if (intval($data_id)) {
            //是否存在data_id
            if (!$DataStatistics = DataStatistics::findFirst("data_id =" . $data_id)) {
                $DataStatistics = new self();
            }

            //构建数据
            $DataStatistics->data_id = $data_id;
            //点击量
            $DataStatistics->hits = RedisIO::get("hits:" . $data_id);
            $DataStatistics->hits_fake = RedisIO::get("hits:" . $data_id) + RedisIO::get("baseHitsCounts:" . $data_id);
            //点赞量
            $DataStatistics->likes = RedisIO::get("meiZiLikes:" . $data_id);
            $DataStatistics->likes_fake = RedisIO::get("meiZiLikes:" . $data_id) + RedisIO::get("baseLikesCounts:" . $data_id);
            //分享量
            $DataStatistics->shares = RedisIO::get("share:" . $data_id);
            $DataStatistics->shares_fake = RedisIO::get("share:" . $data_id) + RedisIO::get("baseShareCounts:" . $data_id);
            //评论量
            $DataStatistics->comments = RedisIO::get("allCommentCounts:" . $data_id);
            $DataStatistics->comments_fake = RedisIO::get("allCommentCounts:" . $data_id) + RedisIO::get("baseCommentCounts:" . $data_id);
            //计算公式
            $DataStatistics->formulas = RedisIO::get("formulas:" . $data_id);
            //数据库分区
            $DataStatistics->partition_by = date("Y");

            //保存或更新
            return $DataStatistics->save();
        } else {
            echo "data_id not empty";
        }
    }


}

