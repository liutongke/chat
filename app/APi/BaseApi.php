<?php

namespace App\Api;

use App\Model\UserToken;
use Sapi\Api;

class BaseApi extends Api
{
    public int $uid;

    //用户权限验证
    public function userCheck($request): string
    {
        if (!isset($request->header['x-token'])) {
            return 'empty_token';
        }
        $token = $request->header['x-token'];
        $account = (new UserToken())->get($token);
        if (empty($account)) {
            return 'token_error';
        }
        $this->uid = $account['0'];
        return '';
    }

}