# ckplayer 视频播放器插件

## 安装说明

ckplayer 是一个开源的网页视频播放器，支持 mp4、flv、hls 等多种格式。

### 手动安装步骤

1. 访问 ckplayer 官网：http://www.ckplayer.com/
2. 下载最新版本的 ckplayer
3. 将文件解压到本目录 (`public/assets/plugins/ckplayer/`)
4. 确保目录中包含以下文件：
   - `ckplayer.js` - 主要 JS 文件
   - `ckplayer.css` - 样式文件
   - `ckplayer.swf` - Flash 播放器（可选，用于旧浏览器兼容）

### 使用方式

在页面中引入：

```html
<script src="/assets/plugins/ckplayer/ckplayer.js"></script>
```

### CDN 替代方案

如果无法本地安装，可使用 CDN：

```html
<script src="https://cdn.bootcdn.net/ajax/libs/ckplayer/ckplayer/ckplayer.min.js"></script>
```

## 许可证

ckplayer 使用 MIT 许可证开源。
