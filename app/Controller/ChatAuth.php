<?php

namespace App\Controller;

use App\Ext\Timer;
use App\Model\UserToken;
use Sapi\Api;
use Sapi\HttpCode;

class ChatAuth extends Api
{
    public function rule()
    {
        return [
            'login' => [
                'uid' => ['name' => 'uid', 'require' => true],
                'password' => ['name' => 'password', 'require' => true],
            ],
            'register' => [
                'nick' => ['name' => 'nick', 'require' => true],
                'head' => ['name' => 'head', 'require' => true],
                'password' => ['name' => 'password', 'require' => true],
            ]
        ];
    }

    //登录
    public function login(\Swoole\Http\Request $request, \Swoole\Http\Response $response): array
    {
        $uid = $request->post['uid'];
        $password = $request->post['password'];

        $database = new \Simps\DB\BaseModel();
        $res = $database->select("user_info", [
            "id",
            "password",
        ], [
            "id" => $uid
        ]);

        if (!$res) {
            return [
                "code" => HttpCode::$StatusBadRequest,
                "msg" => "not account",
                "data" => [

                ],
            ];
        }
        if (md5($password . getSalt()) != $res['0']['password']) {
            return [
                "code" => HttpCode::$StatusBadRequest,
                "msg" => "password err",
                "data" => [

                ],
            ];
        }
        $token = md5($uid . Timer::now() . getSalt());


        $database->update("user_info", [
            "login_tm" => Timer::now(),
        ], [
            "id" => $uid
        ]);

        $localIp = getLocalIp();
        (new UserToken($uid))->save($token, $uid);

        return [
            "code" => HttpCode::$StatusOK,
            "msg" => "success",
            "data" => [
                'localIp' => $localIp,
                'token' => $token,
            ],
        ];
    }

//    注册账号
    public function register(\Swoole\Http\Request $request, \Swoole\Http\Response $response): array
    {
        $nick = $request->post['nick'];
        $password = $request->post['password'];
        $head = $request->post['head'];

        $database = new \Simps\DB\BaseModel();
        $last_user_id = $database->insert("user_info", [
            "head" => $head,
            "nick" => $nick,
            "password" => md5($password . getSalt()),
            "register_tm" => Timer::now(),
            "login_tm" => Timer::now(),
        ]);

        if ($last_user_id) {
            return [
                "code" => HttpCode::$StatusOK,
                "msg" => "success",
                "data" => [
                    'uid' => $last_user_id,
                ],
            ];
        }

        return [
            "code" => HttpCode::$StatusBadRequest,
            "msg" => "error",
            "data" => [
                'uid' => $last_user_id,
            ],
        ];
    }
}