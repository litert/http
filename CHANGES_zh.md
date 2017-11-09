# Changes Logs

## v0.2.0-a2

- 增加控制器方法和类注解支持。

## v0.2.0-a1

- 增加 HTTP 服务端支持。

## v0.1.5

- 为每个请求增加 **timeout** 字段，用于控制请求的网络超时。
- 为客户端类增加 **timeout** 字段，作为每个请求的网络超时默认值。
- 增加完整的 HTTP 状态码常量列表。

## v0.1.4

- 修复 CURL 客户端中当 **data** 字段不是数组时不发送数据的问题。

## v0.1.3

- 修复 CURL 客户端 dataType 字段默认值。

## v0.1.2

- 修复 CURL 客户端 dataType 字段默认值缺失的 Bug。
- 修复 CURL 客户端中 getData 字段缺失时的错误返回。

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
