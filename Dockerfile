FROM php:7.4-fpm

# CONFIGURE
RUN apt-get update && \
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
                gnupg \
                libzip-dev \
                g++ \
                ca-certificates \
                lsb-release \
                software-properties-common

RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv D27D666CD88E42B4 && \
echo "deb http://artifacts.elastic.co/packages/oss-8.x/apt stable main" | tee -a /etc/apt/sources.list.d/elastic-8.x.list
RUN apt-get update && apt-get install apt-transport-https  filebeat

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/

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
                                      xsl \
                                      zip \
                                      bcmath \
                                      intl

RUN docker-php-ext-configure intl && docker-php-ext-configure sockets

RUN pecl install channel://pecl.php.net/igbinary \
                 imagick \
                 memcached \
                 msgpack \
                 redis \
                 mcrypt-1.0.2

RUN docker-php-ext-enable igbinary imagick memcached msgpack redis

RUN sed -i 's/# \(ru_RU.UTF-8 UTF-8\)/\1/g' /etc/locale.gen && locale-gen && update-locale "ru_RU.UTF-8" && \
    export LC_CTYPE=en_US.UTF-8 && \
    export LC_ALL=en_US.UTF-8 && \
    locale-gen

RUN pecl install xdebug-2.9.8 && docker-php-ext-enable xdebug

COPY ./docker/filebeat/filebeat.yml /etc/filebeat/
RUN chmod go-w /etc/filebeat/filebeat.yml

COPY ./docker/scripts/install/app.sh /usr/bin/app
RUN chmod +x /usr/bin/app

# CREATE LOG DIR
RUN mkdir -p /etc/supervisor && \
    mkdir -p /var/log/php-fpm && \
    mkdir -p /var/log/nginx && \
    rm -rf /var/lib/apt/lists/* && \
    mkdir -p /src

# SETUP PERMAMENT VOLUMES
VOLUME /var/log/rest
VOLUME /var/log/php-fpm
VOLUME /var/log/php-fpm/supervisor
VOLUME /var/log/nginx

# CONFIGURATION PHP
COPY ./docker/php/fpm/ /usr/local/etc/php-fpm.d/
COPY ./src/composer.json $APP_PATH
COPY ./src /src

# INSTALL TOOLS
RUN curl -L https://getcomposer.org/installer >> composer-setup.php && \
      php composer-setup.php && \
      mv composer.phar /usr/local/bin/composer && \
      composer config -g github-oauth.github.com 2c2d128d2ef8204efa6513dc48319a4e24289dc9

WORKDIR /src

RUN chmod -R 0777 /src/frontend/web/assets && \
    chmod -R 0777 /src/backend/web/assets && \
    service filebeat restart

#RUN useradd -ms /bin/bash yii_docker
#USER yii_docker

CMD ["app"]