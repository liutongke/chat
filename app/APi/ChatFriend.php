<?php

namespace App\Api;

class ChatFriend extends BaseApi
{
    public function rule()
    {
        return [
            'searchUser' => [
                'uid' => ['name' => 'uid', 'require' => true],
            ],
            'register' => [
                'nick' => ['name' => 'nick', 'require' => true],
                'head' => ['name' => 'head', 'require' => true],
                'password' => ['name' => 'password', 'require' => true],
            ]
        ];
    }

    //查找用户
    public function searchUser(\Swoole\Http\Request $request, \Swoole\Http\Response $response): array
    {
        $searchUid = $request->post['uid'];
        return (new \App\Controller\ChatFriend($this->uid))->searchUser($searchUid);
    }

    //申请列表
    public function newFriend(\Swoole\WebSocket\Server $server, array $msg): array
    {
        return [
            'err' => 200,
            'data' => [
                'name' => 'api-swoole',
                'version' => '1.0.0',
            ]
        ];
    }

    //好友列表
    public function list(\Swoole\WebSocket\Server $server, array $msg): array
    {
        return [
            'err' => 200,
            'data' => [
                'name' => 'api-swoole',
                'version' => '1.0.0',
            ]
        ];
    }

//    添加好友
    public function add(\Swoole\WebSocket\Server $server, array $msg): array
    {
        [
            'add_time' => '添加时间',
            'uid' => '好友id',
            'remark' => '备注',
            ''
        ];
        return [
            'err' => 200,
            'data' => [
                'name' => 'api-swoole',
                'version' => '1.0.0',
            ]
        ];
    }

//    删除好友
    public function del(\Swoole\WebSocket\Server $server, array $msg): array
    {
        return [
            'err' => 200,
            'data' => [
                'name' => 'api-swoole',
                'version' => '1.0.0',
            ]
        ];
    }

//    备注好友
    public function remark(\Swoole\WebSocket\Server $server, array $msg): array
    {
        return [
            'err' => 200,
            'data' => [
                'name' => 'api-swoole',
                'version' => '1.0.0',
            ]
        ];
    }
}