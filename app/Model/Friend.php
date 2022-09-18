<?php

namespace App\Model;

use App\Ext\Timer;

class Friend
{
    private $uid;
    private $key;
    private static $instance;
    private static int $ttl = 86400;
    private static int $maxFriend = 500;//最大好友数量
    private static string $friend_cache_key = 'uFriend:';

    public static function comp($uid)
    {
        if (!isset(static::$instance)) {
            static::$instance = new self($uid);
        }
        return static::$instance;
    }

    public function __construct($uid)
    {
        $this->uid = $uid;
        $this->key = self::$friend_cache_key . $uid;
    }

    //获取好友列表 SELECT friend.friend_uid,friend.remark,user_info.nick,user_info.head FROM friend LEFT JOIN user_info ON friend.friend_uid = user_info.id WHERE friend.friend_uid =999;
    public function list(): array
    {
        $redis = new \Simps\DB\BaseRedis();
        $friendList = $redis->HGETALL($this->key);

        if (!empty($friendList)) {
            $list = [];
            foreach ($friendList as $k => $item) {
                $list[] = json_decode($item, true);
            }
            return $list;
        }

        $database = new \Simps\DB\BaseModel();
        $friendList = $database->select('friend', [
            '[>]user_info' => ['friend_uid' => 'id'],
        ], [
            'friend.friend_uid', 'friend.remark', 'user_info.nick', 'user_info.head'
        ], [
            'LIMIT' => self::$maxFriend,
            'uid' => $this->uid
        ]);

        $multi = $redis->multi(\Redis::PIPELINE);

        foreach ($friendList as $item) {
            $multi->hset($this->key, $item['friend_uid'], json_encode($item));
            $multi->expire($this->key, self::$ttl);
        }
        $rets = $multi->exec();

        return $friendList;
    }

    //是否达到最大好友限制
    public function isMax(): bool
    {
        $redis = new \Simps\DB\BaseRedis();
        $friendNum = $redis->HLEN($this->key);
        return $friendNum >= self::$maxFriend;
    }

    //添加好友
    public function add(int $uid, int $friend_uid, int $applyId, int $type)
    {
        $redis = new \Simps\DB\BaseRedis();
        $redis->expire($this->key, 1);
        $this->insterFriend($uid, $friend_uid, $applyId, $type);
        $redis->del($this->key);
    }

    private function insterFriend(int $uid, int $friend_uid, int $applyId, int $type)
    {
        $tm = Timer::now();
        $database = new \Simps\DB\BaseModel();

        $database->beginTransaction();

        try {
            $database->update('apply_friend', [
                "agree_time" => $tm,
                "agree" => $type
            ], [
                'id' => $applyId
            ]);

            $database->insert('friend', [
                'uid' => $uid,
                'friend_uid' => $friend_uid,
                'agree_time' => $tm,
            ]);

            $database->insert('friend', [
                'uid' => $friend_uid,
                'friend_uid' => $uid,
                'agree_time' => $tm,
            ]);
            $database->commit();
        } catch (\Exception $e) {
            $database->rollBack();
        }

    }

    //删除好友
    public function del(int $uid, int $friend_uid): bool
    {
        $redis = new \Simps\DB\BaseRedis();
        $database = new \Simps\DB\BaseModel();

        $myKey = self::$friend_cache_key . $uid;
        $friendKey = self::$friend_cache_key . $friend_uid;

        $multi = $redis->multi(\Redis::PIPELINE);
        $multi->expire($myKey, 1);
        $multi->expire($friendKey, 1);
        $rets = $multi->exec();

        if (!empty($rets)) {
            try {
                $database->delete('friend', [
                    'uid' => $friend_uid,
                    'friend_uid' => $uid,
                ]);

                $database->delete('friend', [
                    'uid' => $uid,
                    'friend_uid' => $friend_uid,
                ]);

                $multi = $redis->multi(\Redis::PIPELINE);
                $multi->del($myKey);
                $multi->del($friendKey);
                $rets = $multi->exec();

                $database->commit();
                return empty($rets);
            } catch (\Exception $e) {
                $database->rollBack();
                return false;
            }
        }
        return false;
    }

    //修改好友备注
    public function remark(int $friend_uid, string $remark): bool
    {
        $redis = new \Simps\DB\BaseRedis();
        $database = new \Simps\DB\BaseModel();

        $redis->expire($this->key, 1);
        $res = $database->update('friend', [
            'remark' => $remark,
        ], [
            'uid' => $this->uid,
            'friend_uid' => $friend_uid
        ]);

        $redis->del($this->key);
        return $res > 0;
    }
}