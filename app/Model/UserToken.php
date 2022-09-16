<?php

namespace App\Model;

use Simps\DB\BaseRedis;

class UserToken
{
    private static string $token_cache_key = 'tk:';
    private static string $uid_token_cache_key = 'utk:';
    private static string $uid_ip_cache_key = 'uip:';
    private static string $uid_fd_cache_key = 'ufd:';
    private static int $expire_time = 86400;

    public function __construct()
    {
    }

    public function save(string $token, int $uid, $openid = '', $sessionKey = ''): bool
    {
        $redis = new BaseRedis();
        $pipe = $redis->multi(\Redis::PIPELINE);
        $pipe->set(self::$token_cache_key . $token, json_encode([$uid, $openid, $sessionKey]), self::$expire_time);
        $pipe->set(self::$uid_token_cache_key . $uid, $token, self::$expire_time);
        $arr = $pipe->exec();
        return !empty($arr);
    }

    //设置uid所在ip
    public function bindIpFd(int $uid, string $localIp = '', string $fd): bool
    {
        if (empty($localIp)) {
            $localIp = getLocalIp();
        }
        $redis = new BaseRedis();
        $pipe = $redis->multi(\Redis::PIPELINE);
        $pipe->set(self::$uid_ip_cache_key . $uid, $localIp, self::$expire_time);
        $pipe->set(self::$uid_fd_cache_key . $uid, $fd, self::$expire_time);
        $arr = $pipe->exec();
        return !empty($arr);
    }

    //获取uid所在的fd
    public function getFd(int $uid): int
    {
        $redis = new BaseRedis();
        $fd = $redis->get(self::$uid_fd_cache_key . $uid);
        if (!$fd) {
            return 0;
        }
        return $fd;
    }

    //获取uid所在的ip
    public function getLocalIp(int $uid): string
    {
        $redis = new BaseRedis();
        $ip = $redis->get(self::$uid_ip_cache_key . $uid);
        if (!$ip) {
            return "";
        }
        return $ip;
    }

    public function get(string $token): array
    {
        $redis = new BaseRedis();
        $account = $redis->get(self::$token_cache_key . $token);
        if ($account) {
            $redis->expire(self::$token_cache_key . $token, self::$expire_time);
            return json_decode($account, true);
        }
        return [];
    }
}