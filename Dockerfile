FROM phpswoole/swoole:5.0-php8.0

EXPOSE 9500
EXPOSE 9501
EXPOSE 9502
EXPOSE 9503

RUN sed -i 's/deb.debian.org/mirrors.tencent.com/g' /etc/apt/sources.list

RUN apt-get update

COPY swoole.conf /etc/supervisor/service.d

COPY . /var/www/html

WORKDIR /var/www/html
RUN composer install --no-cache
#CMD ["php","./apiswoole.php"]
ENTRYPOINT ["/entrypoint.sh"]

#docker build -t apiswoole:v1 ./