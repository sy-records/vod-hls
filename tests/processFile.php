<?php
/**
 * 通过 ProcessFile 接口发起视频加密
 * User: lufei
 * Date: 2019/9/5
 * Email: lufei@swoole.com
 */

include '../vendor/autoload.php';

use Luffy\Tencent\Vod\Core;

/**
 * 通过 ProcessFile 接口发起视频加密
 * @url https://cloud.tencent.com/document/product/266/9642#.E6.8E.A5.E5.8F.A3.E5.90.8D.E7.A7.B0
 */
$config = [
	"Action" => "ProcessFile",
	"fileId" => "", // 文件 ID
	"transcode.definition.0" => 230, // 转码输出模板号
	"transcode.drm.definition" => 10, // 视频加密控制参数，加密方式；
	"Region" => "", // 区域参数
	"Timestamp" => time(), // 当前 UNIX 时间戳
	"Nonce" => uniqid(), // 随机正整数，与 Timestamp 联合起来, 用于防止重放攻击
	"SecretId" => "", // 由腾讯云平台上申请的标识身份的 SecretId
	"notifyMode" => "Finish" // 任务流状态变更通知模式任务流状态变更通知模式。
];

$secretKey = ""; // 由腾讯云平台上申请的标识身份的SecretKey 需要生成签名

$data = Core::getCloudData($config, $secretKey);

echo $data;