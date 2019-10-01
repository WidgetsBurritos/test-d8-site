#!/bin/sh -l

sh -c "echo '---Installing dependencies---'"
composer install

sh -c "echo '---Install site---'"
cd "${GITHUB_WORKSPACE}/web"
../vendor/bin/drush site-install my_profile --yes --db-url=sqlite://tmp/site.sqlite --config-dir=../config/sync

sh -c "echo '---Setting up web server---'"
php -S localhost:80 .ht.router.php > /dev/null &> /dev/null &

sh -c "echo '---Installing additional dependencies---'"
cd "${GITHUB_WORKSPACE}/web/core"
cp .env.example .env
yarn remove chromedriver
npm install
npm install chromedriver --chromedriver_filepath=/usr/bin/chromedriver

sh -c "echo '---Running nightwatch.js tests---'"
export DRUPAL_TEST_BASE_URL=http://localhost
export DRUPAL_TEST_WEBDRIVER_CHROME_ARGS='--headless --no-sandbox --disable-dev-shm-usage --disable-setuid-sandbox'
yarn test:nightwatch --verbose ../modules/custom/*/tests/src/Nightwatch
