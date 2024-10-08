#!/bin/bash

set -e

APP_PATH=/var/www/app

# primera funcion
create_laravel_project() {

    composer create-project laravel/laravel:^11.0 $APP_PATH

    chmod -R 775 $APP_PATH/storage
    chmod -R 775 $APP_PATH/bootstrap   
}
#------------------------------------------------------
# sugunda funcion
start_laravel_project() {

    chown -R 1000:1000 $APP_PATH
    chown -R www-data:www-data $APP_PATH/storage
    chown -R www-data:www-data $APP_PATH/bootstrap

    # Supervisor is a client/server system that allows its users to monitor and control a number of processes on UNIX-like operating systems.
    /usr/bin/supervisord -c /etc/supervisord.conf
}
#------------------------------------------------------


if [ ! -f /var/www/app/artisan ]; then
    create_laravel_project
    sleep 1
    start_laravel_project
else
    start_laravel_project
fi     

exit 0