<?php

namespace App\Controller;

use App\Ext\Timer;
use App\Model\Friend;
use App\Model\User;
use Sapi\HttpCode;

class ChatFriend
{
    private int $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    //查找用户
    public function searchUser($searchUid): array
    {
        $userModel = new User($this->uid);
        $user = $userModel->getUser($searchUid);
        return [
            'err' => HttpCode::$StatusOK,
            'data' => [
                'user' => $user,
            ]
        ];
    }

    //申请好友
    public function apply($applyUid, $remark): array
    {
        $database = new \Simps\DB\BaseModel();
        $last_user_id = $database->insert('apply_friend', [
            'uid' => $applyUid,
            'friend_uid' => $this->uid,
            'apply_time' => Timer::now(),
            'content' => $remark,
        ]);
        return [
            'err' => HttpCode::$StatusOK,
            'data' => [
                'last_user_id' => $last_user_id,
            ]
        ];
    }

    //好友申请列表
    public function applyList(): array
    {
        $database = new \Simps\DB\BaseModel();

        $list = $database->select('apply_friend', [
            'id',
            'friend_uid',
            'apply_time',
            'content(remark)',
        ], [
            'uid' => $this->uid,
            'LIMIT' => 20
        ]);

        return [
            'err' => 200,
            'data' => [
                'list' => $list
            ]
        ];
    }

    //同意添加好友
    public function applyHandler($applyId, $type): array
    {
        $database = new \Simps\DB\BaseModel();
        $list = $database->select('apply_friend', [
            'uid',
            'friend_uid',
        ], [
            'id' => $applyId,
            'LIMIT' => 1
        ]);

        $this->insterFriend(1, 2);
        return [
            'err' => 200,
            'data' => $list
        ];
    }

    public function friendList(): array
    {
        $friendModel = new Friend($this->uid);
        $user = $friendModel->list();
        return [
            'err' => 200,
            'data' => [
                'list' => $user
            ]
        ];
    }

    private function insterFriend($uid, $friend_uid)
    {
        $tm = Timer::now();
        $database = new \Simps\DB\BaseModel();

        $database->beginTransaction();

        try {
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
}