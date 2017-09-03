# Class \\L\\Http\\ClientFactory

> [返回目录](../index.md)

这是一个工厂类，用于产生各种 HTTP Client 对象。

## 1. 静态方法 detectCACerts

这个方法用于判断 PHP 配置文件 php.ini 里配置的 SSL/TLS 的 CA 证书集合文件是否可用。

### 1.1. 方法签名

```php
public static function detectCACerts(): bool;
```

### 1.2. 返回值

如果 CA 证书集合文件可用，则返回 true，否则返回 false。

## 2. 静态方法 createCURLClient

这个方法用于创建一个基于 CURL 的 HTTP 客户端对象。

### 2.1. 方法签名

```php
public static function createCURLClient(
    array $params = []
): IClient;
```

### 2.2. 参数说明

-   `array $params = []`

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
    ```

### 2.3. 返回值

返回一个 ClientCURL 类的对象实例。

## 3. 静态方法 createFileGetClient

这个方法用于创建一个基于 `file_get_contents` 的 HTTP 客户端对象。

### 3.1. 方法签名

```php
public static function createFileGetClient(
    array $params = []
): IClient;
```

### 3.2. 参数说明

-   `array $params = []`

    这是一个可选参数，它接受一个数组，数组中支持如下字段：

    > 以下字段均为可选字段。

    ```php
    /**
     * 该字段用于设置 SSL严格模式，默认为开启。
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
    ```

### 3.3. 返回值

返回一个 ClientFileGet 类的对象实例。

> [返回目录](../index.md)
