# 常量表

> [返回目录](../index.md)

本章节介绍 LiteRT/HTTP 库提供的所有常量。

## PROTOCOL_DELIMITER

```php
/**
 * HTTP 协议规定的协议换行符。
 */
const PROTOCOL_DELIMITER = "\r\n";
```

## SEGMENT_DELIMITER

```php
/**
 * HTTP 协议规定的段分隔符，即头部信息和数据之间的间隔。
 */
const SEGMENT_DELIMITER = "\r\n\r\n";
```

## AVAILABLE_METHODS

```php
/**
 * LiteRT/HTTP 库中支持的 HTTP 方法。
 */
const AVAILABLE_METHODS = [
    'GET' => 1,
    'POST' => 1,
    'DELETE' => 1,
    'PUT' => 1,
    'PATCH' => 1,
    'HEAD' => 1,
    'OPTIONS' => 1
];
```

## METHOD\_CONTENT_SUPPORTS

```php
/**
 * LiteRT/HTTP 库中支持发送 HTTP Content 的 HTTP 方法。
 */
const METHOD_CONTENT_SUPPORTS = [
    'GET' => false,
    'POST' => true,
    'PATCH' => true,
    'PUT' => true,
    'DELETE' => false,
    'HEAD' => false,
    'OPTIONS' => false
];
```

## DEFAULT\_DATA\_CONTENT_TYPE

```php
/**
 * 默认发送的 Content-Type 值。
 */
const DEFAULT_DATA_CONTENT_TYPE = 'application/x-www-form-urlencoded';
```

## JSON\_CONTENT_TYPE

```php
/**
 * JSON 数据的 Content-Type 值。
 */
const JSON_CONTENT_TYPE = 'application/json';
```

## DEFAULT_TIMEOUT

```php
/**
 * 默认的网络请求超时时间，单位为秒。
 */
const DEFAULT_TIMEOUT = 30;
```

## DEFAULT\_STRICT\_SSL

```php
/**
 * 默认的 HTTPS 协议安全检查设置。
 */
const DEFAULT_STRICT_SSL = true;
```

## DEFAULT_VERSION

```php
/**
 * 默认使用的 HTTP 协议版本。
 */
const DEFAULT_VERSION = 1.1;
```

## 错误码 E\_LACK\_FIELD\_URL

```php
/**
 * 错误：请求信息中缺少 url 字段。
 */
const E_LACK_FIELD_URL = 0x0001;
```

## 错误码 E\_METHOD\_UNSUPPORTED

```php
/**
 * 错误：不支持请求的方法。
 */
const E_METHOD_UNSUPPORTED = 0x0002;
```

## 错误码 E\_LACK\_FIELD\_DATA

```php
/**
 * 错误：请求信息中缺少 data 字段。
 */
const E_LACK_FIELD_DATA = 0x0003;
```

## 错误码 E\_INVALID\_DATA\_TYPE

```php
/**
 * 错误：不支持的 dataType。
 */
const E_INVALID_DATA_TYPE = 0x0004;
```

## 错误码 E\_REQUEST\_FAILURE

```php
/**
 * 错误：请求失败（未能收到服务器的返回）。
 */
const E_REQUEST_FAILURE = 0x0005;
```

## 错误码 E\_VERSION\_UNSUPPORTED

```php
/**
 * 错误：不支持该 HTTP 协议版本。
 */
const E_VERSION_UNSUPPORTED = 0x0006;
```

## 错误码 E_TIMEOUT

```php
/**
 * 错误：请求失败，网络超时。
 */
const E_TIMEOUT = 0x0007;
```

## HTTP 状态码常量表

[HTTPRFCLink]: https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html

具体含义参考 [RFC 文档][HTTPRFCLink]。

```php
const CODE_CONTINUE = 100;
const CODE_SWITCHING_PROTOCOL = 101;
const CODE_OK = 200;
const CODE_CREATED = 201;
const CODE_ACCEPTED = 202;
const CODE_NO_CONTENT = 204;
const CODE_PARTIAL = 206;
const CODE_MULTI_CHOICES = 300;
const CODE_MOVED_PERMANENTLY = 301;
const CODE_FOUND = 302;
const CODE_SEE_OTHER = 303;
const CODE_NOT_MODIFIED = 304;
const CODE_USE_PROXY = 305;
const CODE_TEMPORARY_REDIRECT = 307;
const CODE_BAD_REQUEST = 400;
const CODE_UNAUTHORIZED = 401;
const CODE_PAYMENT_REQUIRED = 402;
const CODE_FORBIDDEN = 403;
const CODE_NOT_FOUND = 404;
const CODE_METHOD_NOT_ALLOWED = 405;
const CODE_NOT_ACCEPTABLE = 406;
const CODE_PROXY_UNAUTHORIZED = 407;
const CODE_REQUEST_TIMEOUT = 408;
const CODE_CONFLICT = 409;
const CODE_GONE = 410;
const CODE_LENGTH_REQUIRED = 411;
const CODE_PRECONDITION_FAILED = 412;
const CODE_ENTITY_TOO_LARGE = 413;
const CODE_URI_TOO_LONG = 414;
const CODE_UNSUPPORTED_MEDIA_TYPE = 415;
const CODE_RANGE_NOT_SATISFIABLE = 416;
const CODE_EXPECTATION_FAILED = 417;
const CODE_INTERNAL_SERVER_ERROR = 500;
const CODE_NOT_IMPLEMENTED = 501;
const CODE_BAD_GATEWAY = 502;
const CODE_SERVER_UNAVAILABLE = 503;
const CODE_GATEWAY_TIMEOUT = 504;
const CODE_VERSION_UNSUPPORTED = 505;
```

> [返回目录](../index.md)
