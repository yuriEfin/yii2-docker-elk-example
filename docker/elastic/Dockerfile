FROM elasticsearch:8.1.3

USER root

COPY ./elasticsearch.yml /etc/elasticsearch/elasticsearch.yml
COPY ./jvm.options /etc/elasticsearch/jvm.options
COPY ./log4j2.properties /etc/elasticsearch/log4j2.properties

RUN mkdir -p /var/log/elasticsearch
RUN mkdir -p /var/lib/elasticsearch

RUN chown -R elasticsearch /etc/elasticsearch
RUN chown -R elasticsearch /var/log/elasticsearch
RUN chown -R elasticsearch /var/lib/elasticsearch
USER elasticsearch