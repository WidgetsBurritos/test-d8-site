FROM php:7.2-alpine

RUN apk add --no-cache \
      # Dev convenience
      bash \

      # Chrome packages
      chromium \
      chromium-chromedriver \
      nss \
      freetype \
      freetype-dev \
      harfbuzz \
      ca-certificates \
      ttf-freefont \

      # Node/Yarn packages
      nodejs \
      yarn \

      # Drupal dependencies
      composer \
      libpng \
      libpng-dev \
      git \
      sqlite
RUN docker-php-ext-install gd

ADD . /github/workspace
ENV GITHUB_WORKSPACE /github/workspace

ADD actions/phpcs/entrypoint.sh /entrypoint-phpcs.sh
ADD actions/test/entrypoint.sh /entrypoint-test.sh
ADD actions/behat/entrypoint.sh /entrypoint-behat.sh
ADD actions/nightwatch/entrypoint.sh /entrypoint-nightwatch.sh
CMD tail -f /dev/null
