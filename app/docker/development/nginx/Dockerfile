ARG ARG_NGINX_VERSION

FROM $ARG_NGINX_VERSION

ARG ARG_APP_DIR

RUN apk add --no-cache curl

COPY ./common/nginx/conf.d /etc/nginx/conf.d

WORKDIR $ARG_APP_DIR

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s CMD curl --fail http://127.0.0.1/health || exit 1