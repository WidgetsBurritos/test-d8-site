#!/bin/sh -l

sh -c "echo '---Installing dependencies---'"
composer install
npm install
chown -Rf www-data:www-data ./web

sh -c "echo '---Install site---'"
cd "${GITHUB_WORKSPACE}/web"
../vendor/bin/drush site-install my_profile --yes --db-url=sqlite://tmp/site.sqlite --config-dir=../config/sync

sh -c "echo '---Setting up web server---'"
php -S localhost:80 .ht.router.php &

sh -c "echo '---Running behat tests---'"
cd ${GITHUB_WORKSPACE}
export BEHAT_PARAMS='{"extensions" : {"Behat\\MinkExtension" : {"base_url" : "http://localhost"}}}'
vendor/bin/behat
