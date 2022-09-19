<?php

namespace App\Controller;

use App\Model\Chat;
use App\Model\ChatOfflineMsg;
use Sapi\HttpCode;

class ChatController
{
    public function sendMsgToUser(\Swoole\WebSocket\Server $server, array $msg, $fd): array
    {
        $bindUid = $server->connection_info($fd)['uid'];
        
        $chatModel = new Chat();
        $uid = $msg['uid'];
        $res = $chatModel->sendMsg($server, $uid, $msg);
        return [
            'err' => 200,
            'data' => [
                'uid' => $bindUid,
                'res' => $res,
            ]
        ];
    }

    //消息报告
    public function msgReport(\Swoole\WebSocket\Server $server, array $msg): array
    {

        $userId = $msg['uid'];
        $ChatOfflineMsgModel = new ChatOfflineMsg($userId);
        $res = $ChatOfflineMsgModel->delMsg($msg['key']);

        return [
            'err' => HttpCode::$StatusOK,
            'data' => [
                'res' => $res,
            ]
        ];
    }
}