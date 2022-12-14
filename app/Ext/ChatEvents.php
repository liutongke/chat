<?php

namespace App\Ext;

use App\Model\ChatOfflineMsg;
use App\Model\UserToken;
use Sapi\CoServer;

class ChatEvents
{
    public function onHandshake(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        $token = $request->get['token'];
        if (empty($token)) {
            $pathInfoList = explode("/", $request->server['path_info']);
            $token = $pathInfoList['1'];
        }
        $userTokenModel = new UserToken();
        $account = $userTokenModel->get($token);

        if (empty($account) || !isset($account['0'])) {
            $response->end();
            return false;
        }

        // websocket握手连接算法验证
        $secWebSocketKey = $request->header['sec-websocket-key'];
        $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
        if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
            $response->end();
            return false;
        }
//        echo $request->header['sec-websocket-key'];
        $key = base64_encode(
            sha1(
                $request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
                true
            )
        );

        $headers = [
            'Upgrade' => 'websocket',
            'Connection' => 'Upgrade',
            'Sec-WebSocket-Accept' => $key,
            'Sec-WebSocket-Version' => '13',
        ];

        if (isset($request->header['sec-websocket-protocol'])) {
            $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
        }

        foreach ($headers as $key => $val) {
            $response->header($key, $val);
        }

        $response->status(101);
        $response->end();

        $server = CoServer::getInstance()->getServer();
        $userId = $account['0'];
        $server->defer(function () use ($server, $request, $userId, $userTokenModel) {
            $userTokenModel->bindIpFd($userId, $request->fd, getLocalIp());
            $server->bind($request->fd, $userId);
            $server->push($request->fd, "hello, welcome\n");

            //发送离线消息
            $msgList = (new ChatOfflineMsg($userId))->getMsg();

            foreach ($msgList as $sendMsg) {
                $server->push($request->fd, $sendMsg);
            }
        });
    }
}