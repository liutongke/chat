<?php

return [
    WsRouter("/", "\App\Controller\Websocket@index"),
    WsRouter("/login", "\App\Controller\Websocket@login"),
    WsRouter("/sendMsgToUser", "\App\Controller\Chat@sendMsgToUser"),
    WsRouter("/msgReport", "\App\Controller\Chat@msgReport"),
];