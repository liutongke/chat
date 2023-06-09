@echo off
setlocal
REM 获取当前目录路径
set "current_dir=%CD%"

REM 打印输出当前目录路径
echo current dir:%current_dir%

docker build -t apiswoole-chat:v1 ./
rem docker run -itd -p 9500:9500 -p 9501:9501 -p 9502:9502/udp -p 9503:9503 --name apiswoole-chat-1 apiswoole-chat:v1
docker run -itd -p 9500:9500 -p 9501:9501 -p 9502:9502/udp -p 9503:9503 --name apiswoole-chat-1 -v %current_dir%:/var/www/html apiswoole-chat:v1