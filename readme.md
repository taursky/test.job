Assembly/Startup Instruction

https://github.com/taursky/test.job.git

next
.env
add
DB_USER=you mysql username
DB_HOST=127.0.0.1
DB_PORT=3306
DB_PASSWORD="you mysql password"
DB_NAME="you mysql database name"

create database

composer install
yarn install
yarn run (dev or prod)
php bin/console doctrine:migrations:migrate

php bin/console cron:create

namme = FlightCancellation
command = vc:flight:cancellation
частота запросов (каждые 5 мин.)
chedule = 5 * * * *
description = 'some description'
enable = y

в crontab 
* * * * * /path/to/symfony/install/bin/console cron:run 1>> /dev/null 2>&1

Создаем рейс со 150 местами
php bin/console vc:create:flight 

$event = [
'reserve',
'cancel_reservation',
'buy_ticket',
'return_ticket'
];

secret_key => src/Entity/Flight.php
flight number = 6942
Запрос you-site.com/v1/callback/events
?secret_key=Aic1yaelohzomib7Taroow3v
&flight='flight number'
&event='$event[some variant]'
&place='place(from 1 to 150)'
&passenger= passenger(from 1 to 150)

