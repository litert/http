# 类 Response

Response 类用于包含一个 HTTP 请求的响应结果。

## 1. 方法

### 1.1. 方法 isSuccess

这个方法用于判断 HTTP 请求是否得到一个成功的返回（HTTP 2xx）。

#### 1.1.1. 方法签名

```php
public function isSuccess(): bool;
```

#### 1.1.2. 返回值

返回 true 表示这个请求返回的值是 HTTP 200~299。

### 1.2. 方法 isServerError

这个方法用于判断 HTTP 请求是否因为服务器错误而失败（HTTP 5xx）。

#### 1.2.1. 方法签名

```php
public function isServerError(): bool
```

#### 1.2.2. 返回值

返回 true 表示这个请求返回的值是 HTTP 5xx。

### 1.3. 方法 isClientError

这个方法用于判断 HTTP 请求是否因为客户端错误而失败（HTTP 4xx）。

#### 1.3.1. 方法签名

```php
public function isClientError(): bool
```

#### 1.3.2. 返回值

返回 true 表示这个请求返回的值是 HTTP 4xx。

### 1.4. 方法 isRedirection

这个方法用于判断 HTTP 请求是否得到一个重定向要求。

#### 1.4.1. 方法签名

```php
public function isRedirection(): bool
```

#### 1.4.2. 返回值

返回 true 表示这个请求返回的值是 HTTP 3xx。

### 1.5. 方法 isMessage

这个方法用于判断 HTTP 请求是否得到一个消息码。

#### 1.5.1. 方法签名

```php
public function isMessage(): bool
```

#### 1.5.2. 返回值

返回 true 表示这个请求返回的值是 HTTP 1xx。

## 2. 属性

### 2.1. 属性 code

该属性表示 HTTP 请求返回的状态码。

#### 2.1.1. 属性定义

```php
public $code: bool;
```

### 2.2. 属性 data

该属性表示 HTTP 请求返回的数据。

#### 2.2.1. 属性定义

```php
public $data: string;
```

#### 2.2.2 注意事项

当 `getData` 字段被置为 false 时，data 属性为空。

### 2.3. 属性 headers

该属性表示 HTTP 请求返回的 HTTP 响应头信息。

#### 2.3.1. 属性定义

```php
public $headers: array;
```

#### 2.3.2 注意事项

仅当 `getHeaders` 字段被置为 true 时，headers 属性才有效。

### 2.4. 属性 previousHeaders

该属性表示 HTTP 请求返回的 HTTP （预置）头信息。

> 比如 HTTP POST 方法可能会发送 `Expect: 100 Contine` 头，
这时候请求会被拆分为多个 HTTP 请求，而 headers 属性中仅包含
最后一个请求的相应头信息。其它都在这个属性中。

#### 2.4.1. 属性定义

```php
public $previousHeaders: array;
```

#### 2.4.2 注意事项

仅当 `getHeaders` 字段被置为 true 时，previousHeaders 属性才有效。

### 2.5. 属性 profile

该属性表示 HTTP 请求的统计信息。

> 并非所有 Client 都支持该属性。

#### 2.5.1. 属性定义

```php
public $profile: array;
```

#### 2.5.2 注意事项

仅当 `getProfile` 字段被置为 true，且 Client 支持该字段时，
profile 属性才有效。

> [返回目录](../index.md)
