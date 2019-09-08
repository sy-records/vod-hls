<?php
/**
 * 创建/更新hls模板
 * User: lufei
 * Date: 2019/9/8
 * Email: lufei@swoole.com
 */

include '../vendor/autoload.php';

use Luffy\Tencent\Vod\Core;

/**
 * 创建 HLS 普通加密模板
 * @url https://cloud.tencent.com/document/product/266/35167
 */
$config = [
    "Action" => "CreateSimpleAesTemplate", // 接口指令的名称
    "get_key_url" => "", // HLS 普通加密模板的 GetKeyURL 必须https
    "Region" => "", // 区域参数
    "Timestamp" => time(), // 当前 UNIX 时间戳
    "Nonce" => uniqid(), // 随机正整数，与 Timestamp 联合起来, 用于防止重放攻击
    "SecretId" => "", // 由腾讯云平台上申请的标识身份的 SecretId
];

/**
 * 更新 HLS 普通加密模板
 * @url https://cloud.tencent.com/document/product/266/35168
 */
//$config = [
//  "Action" => "ModifySimpleAesTemplate",
//  "definition" => 10, // HLS 普通加密模板 ID
//  "get_key_url" => "",
//  "Region" => "",
//  "Timestamp" => time(),
//  "Nonce" => uniqid(),
//  "SecretId" => "",
//];

$secretKey = ""; // 由腾讯云平台上申请的标识身份的SecretKey 需要生成签名

$data = Core::getCloudData($config, $secretKey);

echo $data;
