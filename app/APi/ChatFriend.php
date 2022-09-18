<?php

namespace App\Api;

class ChatFriend extends BaseApi
{
    public function rule()
    {
        return [
            'searchUser' => [
                'uid' => ['name' => 'uid', 'require' => true],
            ],
            'apply' => [
                'uid' => ['name' => 'uid', 'require' => true],
                'remark' => ['name' => 'remark', 'require' => true],
            ],
            'agreeApply' => [
                'id' => ['name' => 'id', 'require' => true],
                'type' => ['name' => 'type', 'require' => true],
            ],
            'remark' => [
                'uid' => ['name' => 'uid', 'require' => true],
                'remark' => ['name' => 'remark', 'require' => true],
            ],
            'del' => [
                'uid' => ['name' => 'uid', 'require' => true],
            ]
        ];
    }

    //查找用户
    public function searchUser(\Swoole\Http\Request $request, \Swoole\Http\Response $response): array
    {
        $searchUid = $request->post['uid'];
        return (new \App\Controller\ChatFriend($this->uid))->searchUser($searchUid);
    }

    //申请好友
    public function apply(\Swoole\Http\Request $request, \Swoole\Http\Response $response): array
    {
        $applyUid = $request->post['uid'];
        $remark = $request->post['remark'];
        return (new \App\Controller\ChatFriend($this->uid))->apply($applyUid, $remark);
    }

    //申请列表
    public function applyList(\Swoole\Http\Request $request, \Swoole\Http\Response $response): array
    {
        return (new \App\Controller\ChatFriend($this->uid))->applyList();
    }

    //    添加好友
    public function agreeApply(\Swoole\Http\Request $request, \Swoole\Http\Response $response): array
    {
        $applyId = $request->post['id'];
        $type = $request->post['type'];//0-等待确认 1-同意 2-拒绝 4-拉黑
        return (new \App\Controller\ChatFriend($this->uid))->applyHandler($applyId, $type);
//        [
//            'add_time' => '添加时间',
//            'uid' => '好友id',
//            'remark' => '备注',
//            ''
//        ];

    }

    //好友列表
    public function friendList(\Swoole\Http\Request $request, \Swoole\Http\Response $response): array
    {
        return (new \App\Controller\ChatFriend($this->uid))->friendList();
    }

    //    备注好友
    public function remark(\Swoole\Http\Request $request, \Swoole\Http\Response $response): array
    {
        $uid = $request->post['uid'];
        $remark = $request->post['remark'];

        return (new \App\Controller\ChatFriend($this->uid))->remark($uid, $remark);
    }

//    删除好友
    public function del(\Swoole\Http\Request $request, \Swoole\Http\Response $response): array
    {
        $uid = $request->post['uid'];
        return (new \App\Controller\ChatFriend($this->uid))->del($uid);
    }
}