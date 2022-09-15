<?php

namespace App\Model;

use Simps\DB\BaseRedis;

class UserToken
{
    private int $uid;
    private string $key = 'uInfo:';

    private static string $token_cache_key = 'tk:';
    private static string $uid_token_cache_key = 'utk:';
    private static int $expire_time = 3600;

    public function __construct()
    {
    }

    public function save(string $token, $uid, $openid = '', $sessionKey = ''): bool
    {
        $redis = new BaseRedis();
        $pipe = $redis->multi(\Redis::PIPELINE);
        $pipe->set(self::$token_cache_key . $token, json_encode([$uid, $openid, $sessionKey]), self::$expire_time);
        $pipe->set(self::$uid_token_cache_key . $uid, $token, self::$expire_time);
        $arr = $pipe->exec();
        return !empty($arr);
    }

    public function get(string $token): array
    {
        $redis = new BaseRedis();
        $userInfo = $redis->get(self::$token_cache_key . $token);
        if ($userInfo) {
            $redis->expire(self::$token_cache_key . $token, self::$expire_time);
            return json_decode($userInfo, true);
        }
        return [];
    }
}