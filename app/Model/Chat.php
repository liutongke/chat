<?php

namespace App\Model;

use App\Ext\Tcp\TcpClient;

class Chat
{
    public function __construct()
    {
    }

    //发送消息
    public function sendMsg(\Swoole\WebSocket\Server $server, int $uid, array $msg, int $source = 0): bool
    {
        if ($source == 0) {
            (new ChatOfflineMsg($uid))->addMsg($msg);
        }
        
        if ($this->isLocal($uid)) {
            $fd = (new UserToken())->getFd($uid);
            if ($server->exist($fd)) {
                return $server->push($fd, json_encode($msg));
            }
        }
        $tcpClient = new TcpClient();

        return $tcpClient->send($uid, $msg);//不在本服务器转发一下
    }

    //用户是否本地
    public function isLocal(int $uid): bool
    {
        return (new UserToken())->getLocalIp($uid) == getLocalIp();
    }
}