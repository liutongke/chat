@echo off

docker build -t apiswoole ./

SET dirPath=%~dp0

docker run -i -t -p 9600:9500 -p 9601:9501 -p 9602:9502/udp --name swoole-bat-01 -v %dirPath%:/var/www apiswoole