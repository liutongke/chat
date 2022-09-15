<?php

namespace App\Model;

class User
{
    private $uid;
    private string $key = 'uInfo:';

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
            $res = $database->select("user_info", [
                "id",
                "nick",
                "head",
            ], [
                "id" => $uid
            ]);
            if (!$res) {
                return [];
            }
            $redis->set($key, json_encode($res['0']));
            $redis->expire($key, 86400);
            return $res['0'];
        }
        return json_decode($user, true);
    }

    //获取多个用户信息
    public function getUserList(array $uidList): array
    {

    }

    private function getKey($uid): string
    {
        return $this->key . $uid;
    }
}