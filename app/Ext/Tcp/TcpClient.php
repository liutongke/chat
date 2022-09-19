<?php

namespace App\Ext\Tcp;

use App\Model\UserToken;

class TcpClient
{
    private static array $clientList = [];

    /**
     * @throws \Exception
     */
    private function client(string $serverIp): \Swoole\Client
    {
        if (isset(self::$clientList[$serverIp]) && self::$clientList[$serverIp]->isConnected()) {
            return self::$clientList[$serverIp];
        }
        $client = new \Swoole\Client(SWOOLE_SOCK_TCP);
        $tcpConf = DI()->config->get('conf.tcp');
        if (!isset($tcpConf['port'])) {
            throw new \Exception("connect failed. Error: get tcp port\n");
        }
        if (!$client->connect($serverIp, $tcpConf['port'], -1)) {
            throw new \Exception("connect failed. Error: {$client->errCode}\n");
        }

        self::$clientList[$serverIp] = $client;
        return $client;
    }

    public function send(int $sendToUid, array $sendMsg): bool
    {
        $serverIp = (new UserToken())->getLocalIp($sendToUid);
        if (!empty($serverIp)) {
            $client = $this->client($serverIp);
            return $client->send(json_encode($sendMsg));
        }
    }
}