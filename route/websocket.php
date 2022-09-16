<?php

return [
    WsRouter("/", "\App\Controller\Websocket@index"),
    WsRouter("/login", "\App\Controller\Websocket@login"),
    WsRouter("/sendMsgToUser", "\App\APi\Chat@sendMsgToUser"),
];