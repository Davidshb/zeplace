#syntax=docker/dockerfile:1.4

FROM node:lts-slim

WORKDIR /usr/app

RUN yarn global add yarn

RUN yarn set version stable

RUN yarn config set --home enableTelemetry 0

RUN npm i -g @angular/cli

RUN yarn add

RUN ng config -g cli.packageManager yarn

ENTRYPOINT ["ng", "serve", "--hmr", "--host=0.0.0.0", "--disable-host-check"]