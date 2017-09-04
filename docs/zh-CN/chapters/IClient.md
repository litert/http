# Interface \\L\\Http\\IClient

> [返回目录](../index.md)

这是 HTTP Client 类的通用接口。

## 1. 方法 __construct

这是 HTTP Client 类的构造函数接口声明。

### 1.1. 方法签名

```php
public function __construct(
    array $params = []
): void;
```

### 1.2. 参数说明

这个方法只接收一个参数 `array $params = []`，但是这个参数里面可以有多个字段：

这是一个可选参数，它接受一个数组，数组中支持如下字段：

> 以下字段均为可选字段。

```php
/**
 * 该字段用于设置 SSL 严格模式，默认为开启。
 *
 * 如果将此字段设置为 false，则不进行 SSL/TLS 证书和域名校验。
 */
bool strictSSL = true;

/**
 * 该字段用于设置 CA 证书集合文件的绝对路径，默认为空，
 * 表示使用 php.ini 配置里的路径。
 *
 * 推荐从 https://curl.haxx.se/ca/cacert.pem 下载。
 */
string caFile = null;

/**
 * 该字段用于设置客户端使用的 HTTP 协议版本，默认是 1.1 版本。
 */
float version = 1.1;
```

### 1.3. 返回值

构造函数没有返回值。

## 2. 方法 request

这个方法用于发起一个 HTTP 请求。

### 2.1. 方法签名

```php
public function request(
    string $method,
    array $params = []
): Response;
```

### 2.2. 参数说明

-   `string $method`

    该参数用于指定请求使用的 HTTP 方法，可以使用如下方法

    - GET
    - POST
    - PUT
    - PATCH
    - DELETE
    - OPTIONS
    - HEAD

    > 方法名称严格区分大小写。

-   `array $params`

    该参数用于配置请求的相关设置。

    ```php
    /**
     * [必须字段]
     *
     * 该字段用于设置要请求的目标 URL。
     */
    string url;

    /**
     * 该字段用于设置发送请求时的 HTTP Body 数据。
     *
     * 仅当 $method 为 PUT/PATCH/POST 时，本字段为必须字段。
     * 其它 HTTP 方法的请求是，该字段被忽略。
     *
     * 如果 data 是 string 类型，则直接发送。
     * 如果 data 是 array 类型，则根据 dataType 字段编码后发送。
     *
     * 其它情况则抛出异常。
     */
    array|string data = null;

    /**
     * 仅当 data 为 array 时生效，可以取如下值：
     *
     * - json： data 将被编码为 JSON 格式。
     * - form： data 将被编码为 x-www-form-urlencoded 格式。
     *
     * 注：请求的 Headers 也将被重置为对应的编码类型的 MIME Type。
     */
    string dataType = 'form';

    /**
     * 该字段用于设置是否要获取请求返回的 HTTP 头信息。
     *
     * 设为 true 表示需要获取 HTTP 头。
     */
    bool getHeaders = false;

    /**
     * 该字段用于设置是否要获取请求返回的 HTTP 请求统计信息。
     *
     * 设为 true 表示需要获取 HTTP 请求统计信息。
     *
     * 注：并非所有客户端都支持该属性。
     */
    bool getProfile = false;

    /**
     * 该字段用于设置是否要获取请求返回的 HTTP Body 数据。
     *
     * 设为 true 表示需要获取 HTTP Body 数据。
     *
     * 注：当使用 HTTP HEAD 方法时，该字段被忽略，并强制设为 false。
     * 因为 HEAD 方法不返回 HTTP Body 数据。
     */
    bool getData = true;

    /**
     * 该字段用于设置 SSL 严格模式，默认使用构造函数中传递的
     * strictSSL 字段，如果构造时未设置，则默认为 true。
     *
     * 如果将此字段设置为 false，则不进行 SSL/TLS 证书和域名校验。
     */
    bool strictSSL = true;

    /**
     * 该字段用于设置 CA 证书集合文件的绝对路径，默认使用构造函数中
     * 传递的 caFile 字段，如果构造时未设置，则使用 php.ini 配置
     * 里的路径。
     *
     * 推荐从 https://curl.haxx.se/ca/cacert.pem 下载。
     */
    string caFile = null;

    /**
     * 该字段用于设置客户端使用的 HTTP 协议版本，默认使用构造
     * 函数中设置的版本号。
     */
    float version = 1.1;
    ```

### 2.3. 返回值

成功时返回一个 `\L\Http\Response` 类的对象实例。
请求失败时（服务器返回 HTTP 错误码除外）抛出 `\L\Core\Exception` 异常。

## 3. 方法 get

这个方法用于发起一个 HTTP GET 请求。

### 3.1. 方法签名

```php
public static function get(
    array $params
): IClient;
```

### 3.2. 参数说明

-   `array $params = []`

    参考 `\L\Http\IClient::request` 方法的 `$params` 参数。

### 3.3. 返回值

成功时返回一个 `\L\Http\Response` 类的对象实例。
请求失败时（服务器返回 HTTP 错误码除外）抛出 `\L\Core\Exception` 异常。

## 4. 方法 post

这个方法用于发起一个 HTTP POST 请求。

### 4.1. 方法签名

```php
public static function post(
    array $params
): IClient;
```

### 4.2. 参数说明

-   `array $params = []`

    参考 `\L\Http\IClient::request` 方法的 `$params` 参数。

    > 在此方法中 `data` 字段为必须字段。

### 4.3. 返回值

成功时返回一个 `\L\Http\Response` 类的对象实例。
请求失败时（服务器返回 HTTP 错误码除外）抛出 `\L\Core\Exception` 异常。

## 5. 方法 put

这个方法用于发起一个 HTTP PUT 请求。

### 5.1. 方法签名

```php
public static function put(
    array $params
): IClient;
```

### 5.2. 参数说明

-   `array $params = []`

    参考 `\L\Http\IClient::request` 方法的 `$params` 参数。

    > 在此方法中 `data` 字段为必须字段。

### 5.3. 返回值

成功时返回一个 `\L\Http\Response` 类的对象实例。
请求失败时（服务器返回 HTTP 错误码除外）抛出 `\L\Core\Exception` 异常。

## 6. 方法 patch

这个方法用于发起一个 HTTP PATCH 请求。

### 6.1. 方法签名

```php
public static function patch(
    array $params
): IClient;
```

### 6.2. 参数说明

-   `array $params = []`

    参考 `\L\Http\IClient::request` 方法的 `$params` 参数。

    > 在此方法中 `data` 字段为必须字段。

### 6.3. 返回值

成功时返回一个 `\L\Http\Response` 类的对象实例。
请求失败时（服务器返回 HTTP 错误码除外）抛出 `\L\Core\Exception` 异常。

## 7. 方法 delete

这个方法用于发起一个 HTTP DELETE 请求。

### 7.1. 方法签名

```php
public static function delete(
    array $params
): IClient;
```

### 7.2. 参数说明

-   `array $params = []`

    参考 `\L\Http\IClient::request` 方法的 `$params` 参数。

### 7.3. 返回值

成功时返回一个 `\L\Http\Response` 类的对象实例。
请求失败时（服务器返回 HTTP 错误码除外）抛出 `\L\Core\Exception` 异常。

## 8. 方法 head

这个方法用于发起一个 HTTP HEAD 请求。

### 8.1. 方法签名

```php
public static function head(
    array $params
): IClient;
```

### 8.2. 参数说明

-   `array $params = []`

    参考 `\L\Http\IClient::request` 方法的 `$params` 参数。

    > 该方法中，`getData` 字段无效，将被强制置为 false。

### 8.3. 返回值

成功时返回一个 `\L\Http\Response` 类的对象实例。
请求失败时（服务器返回 HTTP 错误码除外）抛出 `\L\Core\Exception` 异常。

## 9. 方法 options

这个方法用于发起一个 HTTP OPTIONS 请求。

### 9.1. 方法签名

```php
public static function options(
    array $params
): IClient;
```

### 9.2. 参数说明

-   `array $params = []`

    参考 `\L\Http\IClient::request` 方法的 `$params` 参数。

    > 该方法中，`getData` 字段无效，将被强制置为 false。

### 9.3. 返回值

成功时返回一个 `\L\Http\Response` 类的对象实例。
请求失败时（服务器返回 HTTP 错误码除外）抛出 `\L\Core\Exception` 异常。

> [返回目录](../index.md)
