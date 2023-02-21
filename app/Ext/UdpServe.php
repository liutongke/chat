<?php

namespace App\Ext;

class UdpServe
{
    public function onPacket(\Swoole\Server $server, string $data, array $clientInfo)
    {
        echo $data;
    }
}