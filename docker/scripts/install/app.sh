#!/bin/bash

chmod -R 0777 /src/frontend/web/assets
chmod -R 0777 /src/backend/web/assets
chmod -R 0777 /src/frontend/runtime
chmod -R 0777 /src/backend/runtime

echo ">>> Start nginx background service"
/etc/init.d/nginx start

echo ">>> Start main php-fpm service"
php-fpm --allow-to-run-as-root
systemctl enable filebeat

cd /src && composer install