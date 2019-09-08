<?php
/**
 * User: lufei
 * Date: 2019/9/4
 * Email: lufei@swoole.com
 */

namespace Luffy\Tencent\Vod;


class Core
{
	static $vodHost = "vod.api.qcloud.com";
	static $vodUrl = "https://vod.api.qcloud.com/v2/index.php";

	/**
	 * 获取腾讯云接口信息
	 * @param array $config
	 * @param $secretKey
	 * @return bool|string
	 * @throws \Exception
	 */
	public static function getCloudData(array $config, $secretKey)
	{
		$srcStr = self::makeSignPlainText($config, "GET", self::$vodHost);
		$config['Signature'] = self::sign($srcStr, $secretKey);
		$query = http_build_query($config);
		$data = self::myCurl(self::$vodUrl, $query);
		return $data;
	}

	/**
	 * @param $url
	 * @param bool $params
	 * @param int $ispost
	 * @return bool|string
	 */
	static public function myCurl($url, $params = false, $ispost = 0)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
		curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
		if ($ispost) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_URL, $url);
		} else {
			if ($params) {
				curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
			} else {
				curl_setopt($ch, CURLOPT_URL, $url);
			}
		}
		$response = curl_exec($ch);
		if ($response === false) {
			return 'cURL Error:' . curl_error($ch);
		}
		curl_close($ch);
		return $response;
	}

	/**
	 * sign
	 * 生成签名
	 * @param  string $srcStr    拼接签名源文字符串
	 * @param  string $secretKey secretKey
	 * @param  string $method    请求方法
	 * @return
	 */
	public static function sign($srcStr, $secretKey, $method = 'HmacSHA1')
	{
		switch ($method) {
			case 'HmacSHA1':
				$retStr = base64_encode(hash_hmac('sha1', $srcStr, $secretKey, true));
				break;
			case 'HmacSHA256':
				$retStr = base64_encode(hash_hmac('sha256', $srcStr, $secretKey, true));
				break;
			default:
				throw new \Exception($method . ' is not a supported encrypt method');
				return false;
				break;
		}
		return $retStr;
	}

	/**
	 * makeSignPlainText
	 * 生成拼接签名源文字符串
	 * @param  array $requestParams  请求参数
	 * @param  string $requestMethod 请求方法
	 * @param  string $requestHost   接口域名
	 * @param  string $requestPath   url路径
	 * @return
	 */
	public static function makeSignPlainText($requestParams, $requestMethod = 'GET', $requestHost, $requestPath = '/v2/index.php' )
	{
		$url = $requestHost . $requestPath;
		// 取出所有的参数
		$paramStr = self::_buildParamStr($requestParams, $requestMethod);
		$plainText = $requestMethod . $url . $paramStr;
		return $plainText;
	}

	/**
	 * _buildParamStr
	 * 拼接参数
	 * @param  array $requestParams  请求参数
	 * @param  string $requestMethod 请求方法
	 * @return
	 */
	protected static function _buildParamStr($requestParams, $requestMethod = 'GET')
	{
		$paramStr = '';
		ksort($requestParams);
		$i = 0;
		foreach ($requestParams as $key => $value) {
			if ($key == 'Signature') {
				continue;
			}
			// 排除上传文件的参数
			if ($requestMethod == 'POST' && substr($value, 0, 1) == '@') {
				continue;
			}
			// 把 参数中的 _ 替换成 .
			if (strpos($key, '_')) {
				$key = str_replace('_', '.', $key);
			}
			if ($i == 0) {
				$paramStr .= '?';
			} else {
				$paramStr .= '&';
			}
			$paramStr .= $key . '=' . $value;
			++$i;
		}
		return $paramStr;
	}

}