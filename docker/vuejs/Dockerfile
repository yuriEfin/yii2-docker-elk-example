FROM node:8-slim
RUN apt-get update && apt-get -y dist-upgrade  && apt-get install -y nginx

WORKDIR /src/app

COPY ["package.json", "package-lock.json*", "npm-shrinkwrap.json*", "./"]

RUN npm install

COPY . .

RUN npm run build

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
	&& ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80
#RUN cp -r dist