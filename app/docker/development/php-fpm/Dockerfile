# Add PHP-FPM base image
FROM php:8.3-fpm-alpine

MAINTAINER Yiimar

ARG TIMEZONE=Europe/Moscow
ENV PHP_INI_DIR /usr/local/etc/php
ENV APP_DIR=/data/app
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV XDEBUG_VERSION 3.3.0

RUN apk add \
    bash coreutils git linux-headers \
    && git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug \
    && docker-php-ext-configure xdebug --enable-xdebug-dev \
    && docker-php-ext-install xdebug \
    && apk del git

RUN docker-php-ext-install pdo_mysql

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR ${APP_DIR}

USER app