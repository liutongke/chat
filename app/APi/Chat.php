<?php

namespace App\APi;

use App\Controller\ChatController;

class Chat
{
    public function sendMsgToUser(\Swoole\WebSocket\Server $server, array $msg, int $fd): array
    {
        return (new ChatController())->sendMsgToUser($server, $msg, $fd);
    }

    //消息报告
    public function msgReport(\Swoole\WebSocket\Server $server, array $msg): array
    {
        return (new ChatController())->msgReport($server, $msg);
    }

}