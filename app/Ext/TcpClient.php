<?php

namespace App\Ext;

use App\Model\UserToken;

class TcpClient
{
    private array $clientList = [];

    /**
     * @throws \Exception
     */
    public function client(string $serverIp): \Swoole\Client
    {
        if (isset($this->clientList[$serverIp]) && $this->clientList[$serverIp]->isConnected()) {
            return $this->clientList[$serverIp];
        }
        $client = new \Swoole\Client(SWOOLE_SOCK_TCP);
        $tcpConf = DI()->config->get('conf.tcp');
        if (!isset($tcpConf['port'])) {
            throw new \Exception("connect failed. Error: get tcp prot\n");
        }
        if (!$client->connect($serverIp, $tcpConf['port'], -1)) {
            throw new \Exception("connect failed. Error: {$client->errCode}\n");
        }

        return $this->clientList[$serverIp] = $client;
    }

    public function send(int $sendToUid, array $sendMsg): bool
    {
        $serverIp = (new UserToken())->getLocalIp($sendToUid);
        $client = $this->client($serverIp);
        return $client->send(json_encode($sendMsg));
    }
}