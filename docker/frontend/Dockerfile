FROM nginx:latest

USER root

RUN apt-get update && apt-get -y dist-upgrade && apt list --upgradable && apt-get -y install nano
# CONFIGURE
RUN apt-get update && \
    apt-get install --fix-missing -y  \
                zip \
                unzip  \
                build-essential  \
                wget  \
                locales  \
                mc  \
                nano  \
                gnupg \
                libzip-dev \
                g++ \
                ca-certificates \
                lsb-release \
                software-properties-common

RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv D27D666CD88E42B4 && \
echo "deb http://artifacts.elastic.co/packages/oss-8.x/apt stable main" | tee -a /etc/apt/sources.list.d/elastic-8.x.list
RUN apt-get update && apt-get install apt-transport-https  filebeat
COPY ./filebeat.yml /etc/filebeat/
RUN chmod go-w /etc/filebeat/filebeat.yml

COPY ./nginx/frontend.conf /etc/nginx/conf.d/
COPY ./nginx/backend.conf /etc/nginx/conf.d/
