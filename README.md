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

