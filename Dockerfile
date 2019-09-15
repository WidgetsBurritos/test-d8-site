FROM drupal:8-apache
RUN apt-get update
RUN apt-get install -y git zip unzip curl gnupg
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get -y install nodejs
COPY --from=composer /usr/bin/composer /usr/bin/composer
ADD . /github/workspace
ENV GITHUB_WORKSPACE /github/workspace

ADD actions/test/entrypoint.sh /entrypoint.sh
CMD /entrypoint.sh && tail -f /dev/null
