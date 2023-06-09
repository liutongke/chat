<?php

return [
    \HttpRouter("/", "App\Controller\Index@Index"),
    \HttpRouter("/login", "App\Controller\Auth@login"),
    \HttpRouter("/logs", "App\Controller\Logs@index"),
    \HttpRouter("/demo", "App\Controller\Demo@demo"),
    \HttpRouter("/start", function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
        return [
            "code" => 200,
            "msg" => "login",
            "data" => "data"
        ];
    }),
    \HttpRouter("/hello", "App\Controller\Hello@index"),
    \HttpRouter("/redis/setData", "App\Controller\RedisDemo@setData"),
    \HttpRouter("/mysql/get", "App\Controller\MysqlDemo@getOne"),
    \HttpRouter("/mysql/save", "App\Controller\MysqlDemo@save"),
    \HttpRouter("/mysql/del", "App\Controller\MysqlDemo@del"),
    \HttpRouter("/mysql/update", "App\Controller\MysqlDemo@update"),


    \HttpRouter("/chat/login", "App\Controller\ChatAuth@login"),
    \HttpRouter("/chat/register", "App\Controller\ChatAuth@register"),
    \HttpRouter("/chat/chatFriend/search", "App\Controller\ChatFriends@searchUser"),
    \HttpRouter("/chat/chatFriend/apply", "App\Controller\ChatFriends@apply"),
    \HttpRouter("/chat/chatFriend/applyList", "App\Controller\ChatFriends@applyList"),
    \HttpRouter("/chat/chatFriend/agreeApply", "App\Controller\ChatFriends@agreeApply"),
    \HttpRouter("/chat/chatFriend/friendList", "App\Controller\ChatFriends@friendList"),
];