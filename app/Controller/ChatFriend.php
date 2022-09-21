<?php

namespace App\Controller;

use App\Ext\Timer;
use App\Model\ApplyFriend;
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
    //0-等待确认 1-同意 2-拒绝 4-拉黑
    public function applyHandler($applyId, $type): array
    {

        $friendModel = new Friend($this->uid);
        if ($friendModel->isMax()) {
            return [
                'err' => 400,
                'data' => ['好友数量限制']
            ];
        }

        $ApplyFriendModel = new ApplyFriend($this->uid);
        $applyFriendInfo = $ApplyFriendModel->get($applyId);

        if (!empty($applyFriendInfo) && $type == 1) {//同意好友
            $friend_uid = $applyFriendInfo['friend_uid'];
            $friendModel->add($this->uid, $friend_uid, $applyId, $type);
        } else if (!empty($applyFriendInfo) && $type != 1) {//其它处理
            $ApplyFriendModel->update($applyId, $type);
        }

        return [
            'err' => 200,
            'data' => $applyFriendInfo
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

    public function remark($friend_id, $remark): array
    {
        $friendModel = new Friend($this->uid);
        $res = $friendModel->remark($friend_id, $remark);
        return [
            'err' => 200,
            'data' => [
                'res' => $res
            ]
        ];
    }

    public function del(int $friend_id): array
    {
        $friendModel = new Friend($this->uid);
        $res = $friendModel->del($this->uid, $friend_id);
        return [
            'err' => 200,
            'data' => [
                'res' => $res
            ]
        ];
    }
}