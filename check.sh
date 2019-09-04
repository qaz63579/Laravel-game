#!/bin/sh

while :
do
	if !(ps aux | grep loop | grep php > /dev/null)
	then
		echo "loop not running"
		php /home/pi/www/web/Laravel-game/artisan command:loop &
		echo "Now Running"
	fi
	sleep 5
done




