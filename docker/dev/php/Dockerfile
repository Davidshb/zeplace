FROM php:8.2-apache-buster

# apt
RUN apt-get update && apt-get install -y \
  cron \
  git \
  gzip \
  libbz2-dev \
  libfreetype6-dev \
  libicu-dev \
  libjpeg62-turbo-dev \
  libmcrypt-dev \
  libpng-dev \
  libsodium-dev \
  libssh2-1-dev \
  libxslt1-dev \
  libzip-dev \
  lsof \
  mariadb-client \
  vim \
  bash \
  xvfb \
  wget \
  zip unzip \
  acl \
  iproute2 \
  fonts-freefont-ttf \
  fontconfig \
  dbus \
  openssh-client \
  libzip-dev \
  sendmail \
  zip

RUN a2enmod rewrite headers

RUN docker-php-ext-configure \
  gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/


RUN docker-php-ext-install \
  bcmath \
  bz2 \
  calendar \
  exif \
  gd \
  gettext \
  intl \
  mysqli \
  opcache \
  pcntl \
  pdo \
  pdo_mysql \
  soap \
  sockets \
  sysvmsg \
  sysvsem \
  sysvshm \
  xsl \
  iconv \
  zip

COPY --link docker/dev/php/config/php.ini /usr/local/etc/php/php.ini
COPY --link docker/dev/apache/VirtualHost.conf /etc/apache2/sites-available/000-default.conf
COPY --link docker/dev/apache/apache2.conf /etc/apache2/apache2.conf
COPY --link docker/dev/apache/mods-enabled/deflate.conf /etc/apache2/mods-enabled/deflate.conf

RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
