<?php
declare (strict_types=1);

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

        $client = ClientFactory::createFileGetClient();

        echo json_encode($client->get([

            'url' => 'https://fenying.net/',
            'getHeaders' => true,
            'getData' => true,
            'getProfile' => false,
            'headers' => [
                'MY-TEST' => 'hello',
            ],
            'caFile' => __DIR__ . '/cacert.pem'

        ]), JSON_PRETTY_PRINT);

        return 0;
    }
}

$debug = new Debug();

exit($debug->main());
