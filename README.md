## 腾讯云云点播使用 HLS 加密

### 安装

```bash
composer require sy-records/vod-hls
```
### 加密过程

1. [创建 HLS 普通加密模板](https://cloud.tencent.com/document/product/266/35167)
2. [对需加密视频进行加密转码](https://cloud.tencent.com/document/product/266/9642)
3. 前端利用 tcplayer（或者超级播放器）播放视频；
4. 播放器自动请求 [getkeyurl 获取 dk](https://cloud.tencent.com/document/product/266/9643)，getkeyurl 根据业务侧逻辑确认是否返回 dk；
5. 成功返回 dk 播放器自动解码播放。

### 使用方法

#### 1. 创建 HLS 普通加密模板

```php
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

$secretKey = ""; // 由腾讯云平台上申请的标识身份的SecretKey 需要生成签名

$data = Core::getCloudData($config, $secretKey);

```

#### 2. 通过 ProcessFile 接口发起视频加密

```php
/**
 * 通过 ProcessFile 接口发起视频加密
 * @url https://cloud.tencent.com/document/product/266/9642
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

```

### 3. 获取视频信息（获取EDK）

```php
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
```

### 4. 根据业务侧逻辑确认是否返回 dk

```php
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
```
