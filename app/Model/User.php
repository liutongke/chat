<?php

namespace App\Model;

class User
{
    private $uid;
    private string $key = 'uInfo:';
    private static int $ttl = 86400;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    //获取单个用户
    public function getUser($uid)
    {
        $redis = new \Simps\DB\BaseRedis();
        $key = $this->getKey($uid);
        $user = $redis->get($key);

        if (!$user) {
            $database = new \Simps\DB\BaseModel();
            $res = $database->select('user_info', [
                'id(uid)',
                'nick',
                'head',
            ], [
                'id' => $uid
            ]);
            if (!$res) {
                return [];
            }
            $redis->set($key, json_encode($res['0']));
            $redis->expire($key, self::$ttl);
            return $res['0'];
        }
        return json_decode($user, true);
    }

    //获取多个用户信息
    public function getUserList(array $uidList): array
    {
        $redis = new \Simps\DB\BaseRedis();
        $multi = $redis->multi(\Redis::PIPELINE);
        foreach ($uidList as $uid) {
            $multi->get($this->getKey($uid));
        }
        $rets = $multi->exec();
        $needDbuid = [];
        $map = [];
        foreach ($uidList as $k => $uid) {
            $node = $rets[$k];
            if (empty($node)) {
                $needDbuid[] = $uid;
            } else {
                $map[$uid] = json_decode($node, true);
            }
        }

        if (!empty($needDbuid)) {
            $database = new \Simps\DB\BaseModel();
            $dbuidList = $database->select('user_info', [
                'id(uid)',
                'nick',
                'head',
            ], [
                'id' => $needDbuid
            ]);

            $redis = new \Simps\DB\BaseRedis();
            $multi = $redis->multi(\Redis::PIPELINE);

            foreach ($dbuidList as $item) {
                $map[$item['uid']] = $item;
                $multi->set($this->getKey($item['uid']), json_encode($item));
                $multi->expire($this->getKey($item['uid']), self::$ttl);
            }
            $rets = $multi->exec();
        }
        return $map;
    }

    private function getKey($uid): string
    {
        return $this->key . $uid;
    }
}