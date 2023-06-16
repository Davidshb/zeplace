#syntax=docker/dockerfile:1.4

FROM node:lts-alpine

WORKDIR /usr/app

COPY --link ../../angular ./

RUN apt-get update && apt-get upgrade -y

RUN yarn global add yarn

RUN yarn set version berry

RUN yarn config set --home enableTelemetry 0

ENTRYPOINT ["tail", "-f", "/dev/null"]