FROM php:8.1-fpm

# CONFIGURE
RUN apt-get update && apt-get -y dist-upgrade && \
    apt-get install --fix-missing -y  \
                memcached  \
                zip \
                unzip  \
                build-essential  \
                wget  \
                ssh  \
                locales  \
                mc  \
                nano  \
                supervisor \
                libbz2-dev \
                libssl-dev \
                libpng-dev \
                libmagickwand-dev \
                libmagickcore-dev \
                libicu-dev \
                libmcrypt-dev \
                libmemcached-dev \
                libpq-dev \
                libssh2-1-dev \
                libxml2-dev \
                libxslt1-dev \
                zlib1g-dev \
                gettext \
                cron \
                git \
                nginx \
                libzip-dev \
                g++

RUN docker-php-ext-configure \
    gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-configure \
    intl

RUN docker-php-ext-install -j$(nproc) bz2 \
                                      calendar \
                                      exif \
                                      gd \
                                      gettext \
                                      intl \
                                      pcntl \
                                      pdo_pgsql \
                                      pgsql \
                                      shmop \
                                      sockets \
                                      sysvmsg \
                                      sysvsem \
                                      sysvshm \
                                      wddx \
                                      xsl \
                                      zip \
                                      bcmath \
                                      intl || echo "Rm failed"
RUN pecl install channel://pecl.php.net/igbinary \
                 imagick \
                 memcached \
                 msgpack \
                 redis \
                 mcrypt-1.0.2

RUN docker-php-ext-enable igbinary imagick memcached msgpack redis

RUN sed -i 's/# \(ru_RU.UTF-8 UTF-8\)/\1/g' /etc/locale.gen && locale-gen && update-locale "ru_RU.UTF-8"

COPY ./docker/scripts/install/app.sh /usr/bin/app
RUN chmod +x /usr/bin/app

# CREATE LOG DIR
RUN mkdir -p /etc/supervisor
RUN mkdir -p /var/log/php-fpm
RUN mkdir -p /var/log/nginx

# SETUP PERMAMENT VOLUMES
VOLUME /var/log/rest
VOLUME /var/log/php-fpm
VOLUME /var/log/php-fpm/supervisor
VOLUME /var/log/nginx

# CONFIGURATION PHP
COPY ./docker/php/fpm/ /usr/local/etc/php-fpm.d/
RUN rm -rf /var/lib/docker/volumes/5bea178c5e78d6a69636442820f8eede2a433474a904dfabafec650056d8b502
RUN rm -rf /var/lib/docker/volumes/d54e6f2202a378188c58122c7cbe3430deb58f7ab9d52a36411f6af70db98895
RUN rm -rf /var/lib/docker/volumes/f39062c6fdeb900886a9d3427fbcad2d28fd3ef4ede2a293c770f16017599c50

RUN rm -rf /src
RUN mkdir -p /src

# INSTALL TOOLS
RUN curl -L https://getcomposer.org/installer >> composer-setup.php
RUN php composer-setup.php
RUN mv composer.phar /usr/local/bin/composer
RUN composer config -g github-oauth.github.com 2c2d128d2ef8204efa6513dc48319a4e24289dc9

COPY ./src/composer.json $APP_PATH

COPY ./src /src

WORKDIR /src

RUN useradd -ms /bin/bash yii_docker
USER yii_docker

CMD ["app"]