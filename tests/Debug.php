<?php
declare (strict_types=1);
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/1
 * Time: 10:22
 */

namespace L\Test;

use L\Http\ClientFactory;

require __DIR__ . '/../vendor/autoload.php';

class Debug
{
    public function main(): int
    {
        ini_set('display_errors', 'on');
        error_reporting(E_ALL);
        if (ClientFactory::detectCACerts()) {

            echo <<<INFO
SSL/TLS CA certificates bundle file is detected.

INFO;

        }

        $client = ClientFactory::createCURLClient();

        echo json_encode($client->post([

            'url' => 'https://test-paypal.reolink.com/test.php?a=123',
            'getHeaders' => true,
            'getData' => true,
            'getProfile' => false,
            'headers' => [
                'MY-TEST' => 'hello',
            ],
            "data" => http_build_query([
                'a' => 'test',
                'g' => 1111
            ])

        ]), JSON_PRETTY_PRINT);

        return 0;
    }
}

$debug = new Debug();

exit($debug->main());
