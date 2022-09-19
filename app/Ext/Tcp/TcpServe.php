<?php

namespace App\Ext\Tcp;

use App\Model\Chat;

class TcpServe
{
    private static $ForwardWebsocket = 'ForwardWebsocket';

    public function onReceive(\Swoole\Server $server, $fd, $threadId, $data)
    {
        $chatModel = new Chat();
        $msg = json_decode($data, true);
        $chatModel->sendMsg($server, $msg['uid'], $msg);
    }
}