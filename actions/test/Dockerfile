FROM drupal:8-apache
RUN apt-get update
RUN apt-get install -y git zip unzip curl gnupg
RUN curl -sL https://deb.nodesource.com/setup_8.x  | bash -
RUN apt-get -y install nodejs
COPY --from=composer /usr/bin/composer /usr/bin/composer

ADD entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
