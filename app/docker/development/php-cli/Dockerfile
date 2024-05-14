FROM php:8.3-cli-alpine

MAINTAINER Yiimar

ENV PHP_INI_DIR /usr/local/etc/php
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV XDEBUG_VERSION 3.3.0

RUN apk add --no-cache postgresql-dev bash coreutils git linux-headers \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug \
    && docker-php-ext-configure xdebug --enable-xdebug-dev \
    && docker-php-ext-install xdebug \
    && apk del git

RUN apk add --no-cache unzip

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php/conf.d /usr/local/etc/php/conf.d


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

ENV COMPOSER_ALLOW_SUPERUSER 1
COPY ./common/wait-for-it.sh /usr/local/bin/
RUN chmod 555 /usr/local/bin/wait-for-it.sh

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /data/app

USER app


## Add PHP-FPM base image
#FROM php:8.3-cli-alpine
#ARG TIMEZONE=Europe/Moscow
#
#ENV XDEBUG_VERSION 3.3.1
#
#RUN apk add --no-cache \
#    bash \
#    coreutils \
#    git \
#    linux-headers \
#    && git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug \
#    && docker-php-ext-configure xdebug --enable-xdebug-dev \
#    && docker-php-ext-install pdo pdo_mysql xdebug \
#    && apk del git linux-headers
#
#RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
#
#COPY ./common/php/conf.d /usr/local/etc/php/conf.d
#COPY ./development/php/conf.d /usr/local/etc/php/conf.d
#
#COPY ./common/wait-for-it.sh /usr/local/bin/wait-for-it
#RUN chmod 555 /usr/local/bin/wait-for-it
#
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --version=2.7.2 --quiet
#
#RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app
#
#WORKDIR ${APP_DIR}
#
#USER app
#
#CMD ["php"]