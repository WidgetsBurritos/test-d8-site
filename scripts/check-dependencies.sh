#!/usr/bin/env bash

# Ensure drupal is properly bootstrapped.
echo "Attempting to load Drupal:"
./vendor/bin/drush status bootstrap | grep -cq Successful
if [ $? -eq 0 ]
then
  echo "✓ Success"
else
  echo "× Failure"
  exit 1
fi

# Attempt to run tests
echo "Attempting to run Drupal tests:"
php ./web/core/scripts/run-tests.sh --die-on-fail --url 'http://localhost:8888' my_testing_module > /dev/null
if [ $? -eq 0 ]
then
  echo "✓ Success"
else
  echo "× Failure"
  exit 2
fi

# Attempt to run behat tests
echo "Attempting to run behat tests:"
BEHAT_PARAMS='{"extensions" : {"Behat\\MinkExtension" : {"base_url" : "http://localhost:8888"}}}' ./vendor/bin/behat > /dev/null
if [ $? -eq 0 ]
then
  echo "✓ Success"
else
  echo "× Failure"
  exit 3
fi

# Attempt to run nightwatch.js tests
echo "Attempting to run nightwatch.js tests:"
cd web/core
export DRUPAL_TEST_BASE_URL=http://localhost:8888
yarn install > /dev/null
if [ ! -f ".env" ]; then
  cp .env.example .env
fi
yarn test:nightwatch ../modules/custom/my_testing_module/tests/src/Nightwatch > /dev/null
if [ $? -eq 0 ]
then
  echo "✓ Success"
else
  echo "× Failure"
  exit 4
fi
