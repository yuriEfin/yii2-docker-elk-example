FROM docker.io/bitnami/logstash:8

USER root

RUN mkdir -p /etc/logstash
COPY ./logstash.conf /etc/logstash

RUN bin/logstash -f /etc/logstash/logstash.conf
RUN bin/logstash-plugin install --no-verify