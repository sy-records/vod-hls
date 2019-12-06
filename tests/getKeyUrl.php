<?php
/**
 * 获取视频解密密钥
 * User: lufei
 * Date: 2019/9/8
 * Email: lufei@swoole.com
 */

include '../vendor/autoload.php';

use Luffy\Tencent\Vod\Core;

/**
 * getkeyurl获取dk
 * @url https://cloud.tencent.com/document/product/266/9643
 */
$config = [
	"Action" => "DescribeDrmDataKey",
	"edkList.0" => "", // 视频edk
	"Region" => "", // 区域参数
	"Timestamp" => time(), // 当前 UNIX 时间戳
	"Nonce" => uniqid(), // 随机正整数，与 Timestamp 联合起来, 用于防止重放攻击
	"SecretId" => "", // 由腾讯云平台上申请的标识身份的 SecretId
];

$secretKey = ""; // 由腾讯云平台上申请的标识身份的SecretKey 需要生成签名

$data = Core::getCloudData($config, $secretKey);

$res = json_decode($data,true);

echo $res['data']['keyList'][0]['dk'];