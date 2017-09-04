# Changes Logs

## v0.1.1

- 添加 HTTP 协议版本选择字段 version。

## v0.1.0

- 解决问题： CURL 发送 HEAD 方法请求时被锁死，需要忽略 HTTP Content 数据。
- 解决问题： `file_get_contents` 在服务器返回错误时也能获取 HTTP Content 数据。
- 支持 HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS 七种 HTTP 方法。
- 支持自定义 CA 证书集合文件。
- 使用 HTTP 1.1 版本。
- 支持 HTTP 和 HTTPS。
- 实现基于 file\_get_contents 的 HTTP 客户端类。
- 实现基于 CURL 的 HTTP 客户端类。
- 初始化项目。
