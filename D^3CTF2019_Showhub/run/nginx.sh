#! /bin/sh
/etc/init.d/php7.2-fpm start
service nginx start
while true;
do
	echo 1
	sleep 5
done
