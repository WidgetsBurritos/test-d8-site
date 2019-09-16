#!/bin/sh -l

sh -c "echo '---Installing dependencies---'"
composer install

sh -c "echo '---Install site---'"
cd "${GITHUB_WORKSPACE}/web"
../vendor/bin/drush site-install --verbose --yes --db-url=sqlite://tmp/site.sqlite

sh -c "echo '---Setting up web server---'"
php -S localhost:80 .ht.router.php  &

sh -c "echo '---Running drupal tests---'"
php ./core/scripts/run-tests.sh \
  --php /usr/local/bin/php \
  --sqlite /tmp/test.sqlite \
  --verbose \
  --color \
  --url http://localhost \
  my_testing_module
