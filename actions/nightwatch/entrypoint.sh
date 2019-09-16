#!/bin/sh -l

sh -c "echo '---Installing dependencies---'"
composer install

sh -c "echo '---Install site---'"
cd "${GITHUB_WORKSPACE}/web"
../vendor/bin/drush site-install my_profile --yes --db-url=sqlite://tmp/site.sqlite --config-dir=../config/sync

sh -c "echo '---Setting up web server---'"
php -S localhost:80 .ht.router.php &

sh -c "echo '---Installing additional dependencies---'"
cd "${GITHUB_WORKSPACE}/web/core"
cp .env.example .env
yarn install

sh -c "echo '---Running nightwatch.js tests---'"
export DRUPAL_TEST_BASE_URL=http://localhost
yarn test:nightwatch ../modules/custom/*
