#!/bin/bash

chmod -R 0775 /src/frontend/web/assets
chmod -R 0775 /src/backend/web/assets
chmod -R 0775 /src/frontend/runtime
chmod -R 0775 /src/backend/runtime

echo ">>> Start nginx background service"
/etc/init.d/nginx start

echo ">>> Start main php-fpm service"
php-fpm --allow-to-run-as-root