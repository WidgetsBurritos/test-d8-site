#!/bin/sh -l

sh -c "echo '---Installing dependencies---'"
composer install
npm install
chown -Rf www-data:www-data ./web

sh -c "echo '---Install site---'"
vendor/bin/drush si my_profile -y --db-url=sqlite://sites/example.com/files/.ht.sqlite --config-dir=../config/sync --account-pass=admin

sh -c "echo '---Setting up web server---'"
rm -rf /var/www/html
ln -s "${GITHUB_WORKSPACE}/web" /var/www/html
/etc/init.d/apache2 restart

sh -c "echo '---Running drupal tests---'"
cd /var/www/html/
php ./core/scripts/run-tests.sh \
  --php /usr/local/bin/php \
  --url http://localhost:80/ \
  my_testing_module
