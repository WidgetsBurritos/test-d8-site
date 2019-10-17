FROM php:7.2-alpine

RUN apk add --no-cache \
      composer \
      libpng \
      libpng-dev \
      git \
      sqlite
RUN docker-php-ext-install gd

ADD entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
