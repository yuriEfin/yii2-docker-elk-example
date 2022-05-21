FROM nginx:latest

USER root

RUN apt-get update && apt-get -y dist-upgrade && apt list --upgradable && apt-get -y install nano

COPY ./nginx/frontend.conf /etc/nginx/conf.d/
COPY ./nginx/backend.conf /etc/nginx/conf.d/
