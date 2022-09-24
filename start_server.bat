SET dirPath=%~dp0

docker build -t apiswoole ./

docker run -i -t -p 9500:9500 -p 9501:9501 -p 9502:9502/udp --name swoole-100 -v %dirPath%:/var/www apiswoole
