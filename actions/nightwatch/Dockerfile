FROM php:7.2-alpine

RUN apk add --no-cache \
      bash \
      chromium \
      chromium-chromedriver \
      nss \
      freetype \
      freetype-dev \
      harfbuzz \
      ca-certificates \
      ttf-freefont \
      nodejs \
      npm \
      yarn \
      composer \
      libpng \
      libpng-dev \
      git \
      sqlite
RUN docker-php-ext-install gd

ADD entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
