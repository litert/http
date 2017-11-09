# Changes Logs

## v0.2.0-a2

- Added annotation supports for controller.

## v0.2.0-a1

- Added HTTP server supports.

## v0.1.5

- Added field **timeout** for request for network timeout.
- Added field **timeout** for IClient, as default value for every request.
- Added full table of HTTP status code constants.
- Improved the documents, added constants table.

## v0.1.4

- Fixed: Nothing send when field **data** is a string in CURL client.

## v0.1.3

- Fixed: Set the default value of field **getData** to be `true`.

## v0.1.2

- Fixed: Getting invalid response when the field **getData** is missed in CURL
client. 
- Fixed: The default value of field **dataType** in CURL client is missed. 

## v0.1.1

- Added field **version** for request to select version of HTTP protocol.

## v0.1.0

- Resolved: CURL get blocked when sending HEAD request.
- Resolved: `file_get_contents` get response contents when server response error code.
- Supported HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS methods.
- Supported custom CA bundle file.
- Supported HTTP/1.1 version.
- Supported both HTTPS and HTTP.
- Added file\_get\_contents-based HTTP client class.
- Added CURL-based HTTP client class.
- Initialized project.
