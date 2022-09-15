<?php

namespace App\Controller;

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
                'name' => 'api-swoole',
                'version' => '1.0.0',
            ]
        ];
    }
}