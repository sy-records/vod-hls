<?php
/**
 * 获取视频信息
 * User: lufei
 * Date: 2019/9/5
 * Email: lufei@swoole.com
 */

include '../vendor/autoload.php';

use Luffy\Tencent\Vod\Core;

/**
 * 获取视频信息
 * @url https://cloud.tencent.com/document/product/266/8586
 */
$config = [
	"Action" => "GetVideoInfo",
	"fileId" => "", // 文件 ID
	"Region" => "", // 区域参数
	"Timestamp" => time(), // 当前 UNIX 时间戳
	"Nonce" => uniqid(), // 随机正整数，与 Timestamp 联合起来, 用于防止重放攻击
	"SecretId" => "", // 由腾讯云平台上申请的标识身份的 SecretId
	"notifyMode" => "Finish" // 任务流状态变更通知模式任务流状态变更通知模式。
];

$secretKey = ""; // 由腾讯云平台上申请的标识身份的SecretKey 需要生成签名

$data = Core::getCloudData($config, $secretKey);

echo $data;