<?php

namespace App\Model;

use App\Ext\Timer;

class ApplyFriend
{
    private int $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    public function get(int $applyId): array
    {
        $database = new \Simps\DB\BaseModel();
        $applyInfo = $database->get('apply_friend', [
            'uid',
            'friend_uid',
        ], [
            'id' => $applyId,
            'LIMIT' => 1
        ]);

        return $applyInfo ?? [];
    }

    public function update(int $applyId, int $type): int
    {
        $database = new \Simps\DB\BaseModel();

        return $database->update('apply_friend', [
            "agree_time" => Timer::now(),
            "agree" => $type
        ], [
            'id' => $applyId
        ]);
    }
}