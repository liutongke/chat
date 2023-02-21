<?php

namespace App\Model;

//离线消息
class ChatOfflineMsg
{
    private static string $token_cache_key = 'offlineMsg:';
    private static int $ttl = 86400;
    private int $uid;
    private string $key;
    private $redis;

    public function __construct(int $uid)
    {
        $this->uid = $uid;
        $this->key = self::$token_cache_key . $this->uid;
        $this->redis = new \Simps\DB\BaseRedis();
    }

    //消息送达删除消息
    public function delMsg($key): bool
    {
        $n = $this->redis->hdel($this->key, $key);
        return !empty($n);
    }

    //添加用户的消息
    public function addMsg(array $msg): bool
    {
        $this->pushNotify($msg);
        $multi = $this->redis->multi(\Redis::PIPELINE);
        $multi->hset($this->key, $msg['id'], json_encode($msg));
        $multi->expire($this->key, self::$ttl);
        $rets = $multi->exec();
        return !empty($rets);
    }

    //第三方通知栏推送
    public function pushNotify(array $msg)
    {
        go(function () use ($msg) {
//            \Co::sleep(2.0);
        });
    }

    //获取离线消息发送
    public function getMsg(): array
    {
        return $this->redis->HGETALL($this->key);
    }
}