<?php

namespace App\APi;

use Sapi\Api;

class App extends Api
{
    public function rule()
    {
        return [
//            'Index' => [
//                'pic' => ['name' => 'pic', 'require' => true]
//            ]
        ];
    }

    public function Index(\Swoole\Http\Request $request, \Swoole\Http\Response $response): array
    {
//        $key = Aes::generate();
//        $aesModel = new Aes();
//        $data = $aesModel->aesEncrypt('biubiu', $key);
//        $d = $aesModel->aesDecrypt($data, $key);

        return [
            "code" => 200,
            "msg" => "hello World!",
            'tm' => date('Y-m-d H:i:s'),
            "data" => [
//                'key' => $key,
//                'encrypt' => $data,
//                'decrypt' => $d,
                'name' => 'api-swoole',
                'version' => '1.0.0',
//                'uuid' => uuid(),
            ],
        ];
    }
}