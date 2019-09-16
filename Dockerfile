FROM drupal:8-apache
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update
RUN apt-get install -y git zip unzip curl gnupg vim libnss3 nodejs yarn
COPY --from=composer /usr/bin/composer /usr/bin/composer
ADD . /github/workspace
ENV GITHUB_WORKSPACE /github/workspace

ADD actions/phpcs/entrypoint.sh /entrypoint-phpcs.sh
ADD actions/test/entrypoint.sh /entrypoint-test.sh
ADD actions/behat/entrypoint.sh /entrypoint-behat.sh
ADD actions/nightwatch/entrypoint.sh /entrypoint-nightwatch.sh
CMD tail -f /dev/null
