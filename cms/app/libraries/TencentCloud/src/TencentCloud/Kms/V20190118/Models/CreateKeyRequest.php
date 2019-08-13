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
namespace TencentCloud\Kms\V20190118\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method string getAlias() 获取作为密钥更容易辨识，更容易被人看懂的别名， 不可为空，1-60个字符或数字的组合
 * @method void setAlias(string $Alias) 设置作为密钥更容易辨识，更容易被人看懂的别名， 不可为空，1-60个字符或数字的组合
 * @method string getDescription() 获取CMK 的描述，最大1024字节
 * @method void setDescription(string $Description) 设置CMK 的描述，最大1024字节
 * @method string getKeyUsage() 获取指定key的用途。目前，仅支持"ENCRYPT_DECRYPT"，默认为  "ENCRYPT_DECRYPT"，即key用于加密和解密
 * @method void setKeyUsage(string $KeyUsage) 设置指定key的用途。目前，仅支持"ENCRYPT_DECRYPT"，默认为  "ENCRYPT_DECRYPT"，即key用于加密和解密
 * @method integer getType() 获取指定key类型，1为当前地域默认类型，默认为1，且当前只支持该类型
 * @method void setType(integer $Type) 设置指定key类型，1为当前地域默认类型，默认为1，且当前只支持该类型
 */

/**
 *CreateKey请求参数结构体
 */
class CreateKeyRequest extends AbstractModel
{
    /**
     * @var string 作为密钥更容易辨识，更容易被人看懂的别名， 不可为空，1-60个字符或数字的组合
     */
    public $Alias;

    /**
     * @var string CMK 的描述，最大1024字节
     */
    public $Description;

    /**
     * @var string 指定key的用途。目前，仅支持"ENCRYPT_DECRYPT"，默认为  "ENCRYPT_DECRYPT"，即key用于加密和解密
     */
    public $KeyUsage;

    /**
     * @var integer 指定key类型，1为当前地域默认类型，默认为1，且当前只支持该类型
     */
    public $Type;
    /**
     * @param string $Alias 作为密钥更容易辨识，更容易被人看懂的别名， 不可为空，1-60个字符或数字的组合
     * @param string $Description CMK 的描述，最大1024字节
     * @param string $KeyUsage 指定key的用途。目前，仅支持"ENCRYPT_DECRYPT"，默认为  "ENCRYPT_DECRYPT"，即key用于加密和解密
     * @param integer $Type 指定key类型，1为当前地域默认类型，默认为1，且当前只支持该类型
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
        if (array_key_exists("Alias",$param) and $param["Alias"] !== null) {
            $this->Alias = $param["Alias"];
        }

        if (array_key_exists("Description",$param) and $param["Description"] !== null) {
            $this->Description = $param["Description"];
        }

        if (array_key_exists("KeyUsage",$param) and $param["KeyUsage"] !== null) {
            $this->KeyUsage = $param["KeyUsage"];
        }

        if (array_key_exists("Type",$param) and $param["Type"] !== null) {
            $this->Type = $param["Type"];
        }
    }
}
