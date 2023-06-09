<?php

namespace App\Controller;

use Sapi\Api;

class Index extends Api
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
            "province" => "上海",
            "city" => "上海市",
            "adcode" => "310000",
            "weather" => "多",
            "temperature" => "20",
            "winddirection" => "北",
            "windpower" => "≤3",
            "humidity" => "24",
            "reporttime" => "2023-05-10 15:33:45",
            "temperature_float" => "21.0",
            "humidity_float" => "24.0"
        ];
//        {
//            "status": "1",
//    "count": "1",
//    "info": "OK",
//    "infocode": "10000",
//    "lives": [
//        {
//            "province": "上海",
//            "city": "上海市",
//            "adcode": "310000",
//            "weather": "多云",
//            "temperature": "21",
//            "winddirection": "北",
//            "windpower": "≤3",
//            "humidity": "24",
//            "reporttime": "2023-05-10 15:33:45",
//            "temperature_float": "21.0",
//            "humidity_float": "24.0"
//        }
//    ]
//}
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