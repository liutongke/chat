<?php

return [
    \HttpRouter("/", "App\Api\App@Index"),
    \HttpRouter("/login", "App\Controller\Auth@login"),
    \HttpRouter("/logs", "App\Controller\Logs@index"),
    \HttpRouter("/demo", "App\Controller\Demo@demo"),
    \HttpRouter("/start", function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
        return 'hello';
    }),
    \HttpRouter("/hello", "App\Controller\Hello@index"),
    \HttpRouter("/redis/setData", "App\Controller\RedisDemo@setData"),
    \HttpRouter("/mysql/get", "App\Controller\MysqlDemo@getOne"),
    \HttpRouter("/mysql/save", "App\Controller\MysqlDemo@save"),
    \HttpRouter("/mysql/del", "App\Controller\MysqlDemo@del"),
    \HttpRouter("/mysql/update", "App\Controller\MysqlDemo@update"),


    \HttpRouter("/chat/login", "App\Api\ChatAuth@login"),
    \HttpRouter("/chat/register", "App\Api\ChatAuth@register"),
    \HttpRouter("/chat/chatFriend/search", "App\Api\ChatFriend@searchUser"),
    \HttpRouter("/chat/chatFriend/apply", "App\Api\ChatFriend@apply"),
    \HttpRouter("/chat/chatFriend/applyList", "App\Api\ChatFriend@applyList"),
    \HttpRouter("/chat/chatFriend/agreeApply", "App\Api\ChatFriend@agreeApply"),
    \HttpRouter("/chat/chatFriend/friendList", "App\Api\ChatFriend@friendList"),
];