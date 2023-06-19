#syntax=docker/dockerfile:1.4

FROM node:lts-slim

WORKDIR /usr/app

RUN yarn global add yarn

RUN yarn global add @angular/cli

RUN yarn set version berry

RUN yarn config set --home enableTelemetry 0

RUN ng config -g cli.packageManager yarn

CMD ["ng", "serve", "--hmr", "--host=0.0.0.0", "--disable-host-check"]