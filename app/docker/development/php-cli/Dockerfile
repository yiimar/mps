ARG ARG_PHP_CLI_VERSION

FROM $ARG_PHP_CLI_VERSION

MAINTAINER Yiimar

ARG ARG_PHP_INI_DIR
ARG ARG_XDEBUG_VERSION
ARG ARG_APP_DIR
ARG ARG_PHP_USER

ENV XDEBUG_VERSION=$ARG_XDEBUG_VERSION

RUN echo $XDEBUG_VERSION

RUN apk add --no-cache postgresql-dev bash coreutils git linux-headers \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug \
    && docker-php-ext-configure xdebug --enable-xdebug-dev \
    && docker-php-ext-install pdo_pgsql xdebug \
    && apk del git linux-headers

RUN mv $ARG_PHP_INI_DIR/php.ini-development $ARG_PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php/conf.d /usr/local/etc/php/conf.d

COPY ./common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --version=2.7.2 --quiet

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR $ARG_APP_DIR

USER $ARG_PHP_USER