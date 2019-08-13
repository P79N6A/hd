<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Sqlserver\V20180328\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method string getZone() 获取实例可用区，类似ap-guangzhou-1（广州一区）；实例可售卖区域可以通过接口DescribeZones获取
 * @method void setZone(string $Zone) 设置实例可用区，类似ap-guangzhou-1（广州一区）；实例可售卖区域可以通过接口DescribeZones获取
 * @method integer getMemory() 获取实例内存大小，单位GB
 * @method void setMemory(integer $Memory) 设置实例内存大小，单位GB
 * @method integer getStorage() 获取实例磁盘大小，单位GB
 * @method void setStorage(integer $Storage) 设置实例磁盘大小，单位GB
 * @method string getInstanceChargeType() 获取付费模式，目前只支持预付费，其值为PREPAID。可不填，默认值为PREPAID
 * @method void setInstanceChargeType(string $InstanceChargeType) 设置付费模式，目前只支持预付费，其值为PREPAID。可不填，默认值为PREPAID
 * @method integer getProjectId() 获取项目ID
 * @method void setProjectId(integer $ProjectId) 设置项目ID
 * @method integer getGoodsNum() 获取本次购买几个实例，默认值为1。取值不超过10
 * @method void setGoodsNum(integer $GoodsNum) 设置本次购买几个实例，默认值为1。取值不超过10
 * @method string getSubnetId() 获取VPC子网ID，形如subnet-bdoe83fa；SubnetId和VpcId需同时设置或者同时不设置
 * @method void setSubnetId(string $SubnetId) 设置VPC子网ID，形如subnet-bdoe83fa；SubnetId和VpcId需同时设置或者同时不设置
 * @method string getVpcId() 获取VPC网络ID，形如vpc-dsp338hz；SubnetId和VpcId需同时设置或者同时不设置
 * @method void setVpcId(string $VpcId) 设置VPC网络ID，形如vpc-dsp338hz；SubnetId和VpcId需同时设置或者同时不设置
 * @method integer getPeriod() 获取购买实例周期，默认取值为1，表示一个月。取值不超过48
 * @method void setPeriod(integer $Period) 设置购买实例周期，默认取值为1，表示一个月。取值不超过48
 * @method integer getAutoVoucher() 获取是否自动使用代金券；1 - 是，0 - 否，默认不使用
 * @method void setAutoVoucher(integer $AutoVoucher) 设置是否自动使用代金券；1 - 是，0 - 否，默认不使用
 * @method array getVoucherIds() 获取代金券ID数组，目前单个订单只能使用一张
 * @method void setVoucherIds(array $VoucherIds) 设置代金券ID数组，目前单个订单只能使用一张
 * @method string getDBVersion() 获取数据库版本号，目前取值有2012SP3，表示SQL Server 2012；2008R2，表示SQL Server 2008 R2；2016SP1，表示SQL Server 2016 SP1。每个地域支持售卖的版本可能不一样，可以通过DescribeZones接口来拉取每个地域可售卖的版本信息。不填的话，默认为版本2008R2
 * @method void setDBVersion(string $DBVersion) 设置数据库版本号，目前取值有2012SP3，表示SQL Server 2012；2008R2，表示SQL Server 2008 R2；2016SP1，表示SQL Server 2016 SP1。每个地域支持售卖的版本可能不一样，可以通过DescribeZones接口来拉取每个地域可售卖的版本信息。不填的话，默认为版本2008R2
 */

/**
 *CreateDBInstances请求参数结构体
 */
class CreateDBInstancesRequest extends AbstractModel
{
    /**
     * @var string 实例可用区，类似ap-guangzhou-1（广州一区）；实例可售卖区域可以通过接口DescribeZones获取
     */
    public $Zone;

    /**
     * @var integer 实例内存大小，单位GB
     */
    public $Memory;

    /**
     * @var integer 实例磁盘大小，单位GB
     */
    public $Storage;

    /**
     * @var string 付费模式，目前只支持预付费，其值为PREPAID。可不填，默认值为PREPAID
     */
    public $InstanceChargeType;

    /**
     * @var integer 项目ID
     */
    public $ProjectId;

    /**
     * @var integer 本次购买几个实例，默认值为1。取值不超过10
     */
    public $GoodsNum;

    /**
     * @var string VPC子网ID，形如subnet-bdoe83fa；SubnetId和VpcId需同时设置或者同时不设置
     */
    public $SubnetId;

    /**
     * @var string VPC网络ID，形如vpc-dsp338hz；SubnetId和VpcId需同时设置或者同时不设置
     */
    public $VpcId;

    /**
     * @var integer 购买实例周期，默认取值为1，表示一个月。取值不超过48
     */
    public $Period;

    /**
     * @var integer 是否自动使用代金券；1 - 是，0 - 否，默认不使用
     */
    public $AutoVoucher;

    /**
     * @var array 代金券ID数组，目前单个订单只能使用一张
     */
    public $VoucherIds;

    /**
     * @var string 数据库版本号，目前取值有2012SP3，表示SQL Server 2012；2008R2，表示SQL Server 2008 R2；2016SP1，表示SQL Server 2016 SP1。每个地域支持售卖的版本可能不一样，可以通过DescribeZones接口来拉取每个地域可售卖的版本信息。不填的话，默认为版本2008R2
     */
    public $DBVersion;
    /**
     * @param string $Zone 实例可用区，类似ap-guangzhou-1（广州一区）；实例可售卖区域可以通过接口DescribeZones获取
     * @param integer $Memory 实例内存大小，单位GB
     * @param integer $Storage 实例磁盘大小，单位GB
     * @param string $InstanceChargeType 付费模式，目前只支持预付费，其值为PREPAID。可不填，默认值为PREPAID
     * @param integer $ProjectId 项目ID
     * @param integer $GoodsNum 本次购买几个实例，默认值为1。取值不超过10
     * @param string $SubnetId VPC子网ID，形如subnet-bdoe83fa；SubnetId和VpcId需同时设置或者同时不设置
     * @param string $VpcId VPC网络ID，形如vpc-dsp338hz；SubnetId和VpcId需同时设置或者同时不设置
     * @param integer $Period 购买实例周期，默认取值为1，表示一个月。取值不超过48
     * @param integer $AutoVoucher 是否自动使用代金券；1 - 是，0 - 否，默认不使用
     * @param array $VoucherIds 代金券ID数组，目前单个订单只能使用一张
     * @param string $DBVersion 数据库版本号，目前取值有2012SP3，表示SQL Server 2012；2008R2，表示SQL Server 2008 R2；2016SP1，表示SQL Server 2016 SP1。每个地域支持售卖的版本可能不一样，可以通过DescribeZones接口来拉取每个地域可售卖的版本信息。不填的话，默认为版本2008R2
     */
    function __construct()
    {

    }
    /**
     * 内部实现，用户禁止调用
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("Zone",$param) and $param["Zone"] !== null) {
            $this->Zone = $param["Zone"];
        }

        if (array_key_exists("Memory",$param) and $param["Memory"] !== null) {
            $this->Memory = $param["Memory"];
        }

        if (array_key_exists("Storage",$param) and $param["Storage"] !== null) {
            $this->Storage = $param["Storage"];
        }

        if (array_key_exists("InstanceChargeType",$param) and $param["InstanceChargeType"] !== null) {
            $this->InstanceChargeType = $param["InstanceChargeType"];
        }

        if (array_key_exists("ProjectId",$param) and $param["ProjectId"] !== null) {
            $this->ProjectId = $param["ProjectId"];
        }

        if (array_key_exists("GoodsNum",$param) and $param["GoodsNum"] !== null) {
            $this->GoodsNum = $param["GoodsNum"];
        }

        if (array_key_exists("SubnetId",$param) and $param["SubnetId"] !== null) {
            $this->SubnetId = $param["SubnetId"];
        }

        if (array_key_exists("VpcId",$param) and $param["VpcId"] !== null) {
            $this->VpcId = $param["VpcId"];
        }

        if (array_key_exists("Period",$param) and $param["Period"] !== null) {
            $this->Period = $param["Period"];
        }

        if (array_key_exists("AutoVoucher",$param) and $param["AutoVoucher"] !== null) {
            $this->AutoVoucher = $param["AutoVoucher"];
        }

        if (array_key_exists("VoucherIds",$param) and $param["VoucherIds"] !== null) {
            $this->VoucherIds = $param["VoucherIds"];
        }

        if (array_key_exists("DBVersion",$param) and $param["DBVersion"] !== null) {
            $this->DBVersion = $param["DBVersion"];
        }
    }
}
