```shell
.\ab.exe -c 10 -n 10000 -H "x-token:62ebb065c3d77b06f300469843f01601" http://192.168.0.105:9500/chat/chatFriend/friendList

docker run -i -t -p 9500:9500 -p 9501:9501 -p 9502:9502/udp --name swoole-1 -v C:\Users\keke\dev\docker\lnmp:/var/www apiswoole-chat:v1
```

直接运行`start_server.bat`脚本一键安装镜像、创建容器，创建完成后直接进入镜像运行`apiswoole`

```shell
docker exec -it apiswoole-chat:v1 /bin/bash
```