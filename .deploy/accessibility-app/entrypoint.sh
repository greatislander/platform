#!/bin/sh

set -e

mkdir -p $FILES_PATH
mkdir -p $CACHE_PATH

rsync -a /app/storage/ $FILES_PATH/
rm -rf /app/storage

rsync -a /app/bootstrap/cache/ $CACHE_PATH/
rm -rf /app/bootstrap/cache

ln -s $FILES_PATH /app/storage
chown www-data:root $FILES_PATH/ -R

ln -s $CACHE_PATH /app/bootstrap/cache
chown www-data:root $CACHE_PATH/ -R

if [ ! -f $FILES_PATH/../deploy.lock ]
then

  touch $FILES_PATH/../deploy.lock

  if [ "$APP_ENV" == "production" ]
  then
    php artisan migrate --step --force
  else
    php artisan migrate:fresh --step 
    php artisan db:seed DevSeeder
  fi

  php artisan google-fonts:fetch
  php artisan storage:link
  php artisan cache:clear
  php artisan view:clear
  php artisan optimize 
  php artisan event:cache
  
fi

rm -rf $FILES_PATH/../deploy.lock

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
