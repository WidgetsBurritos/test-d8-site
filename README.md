# Test D8 Site

This is a test D8 site which can be used for practicing test writing and
running. This is to be used in tandem with the
_[Testing. Testing. 1... 2... 3... *tap* *tap* *tap* - Is this thing on?](https://2019.texascamp.org/sessions/testing-testing-1-2-3-tap-tap-tap--is-this-thing-on-training)_ training
at TexasCamp 2019.

## Dependencies

You'll need the following installed on your system:

1. Bash or some other comparable shell
2. git
3. PHP 7.1-7.3
4. Composer
5. Sqlite
6. Node.js 8+
7. yarn
8. Google Chrome

## Getting started

1. Fork the https://github.com/WidgetsBurritos/test-d8-site repo.
2. Clone your fork locally and change into that directory.
3. Install dependencies:
    ```bash
    composer install
    ```
4. Determine your version of Google Chrome (e.g. 77.0.3865.90)
5. Install the nightwatch dependencies (note: you need to set your major chrome version)

    ```bash
    export CHROMEVERSION=77
    cd web/core
    yarn install
    yarn remove chromedriver
    yarn add chromedriver@${CHROMEVERSION}
    cp -f .env.example .env
    cd ../..
    ```
6. Do a basic site install based on the existing config:
    ```bash
    vendor/bin/drush si my_profile -y --db-url=sqlite://sites/example.com/files/.ht.sqlite --config-dir=../config/sync --account-pass=admin
    ```
7. Create some more users and set roles
    ```bash
    vendor/bin/drush user:create bobby --mail="bobby@example.com" --password="bobby"
    vendor/bin/drush user:create carol --mail="carol@example.com" --password="carol"
    vendor/bin/drush user:create david --mail="david@example.com" --password="david"
    vendor/bin/drush user-add-role "administrator" admin
    vendor/bin/drush user-add-role "super_secret" bobby
    vendor/bin/drush user-add-role "yet_another_role" carol
    ```
8. Start a local web server:
    ```bash
    cd web
    php -S localhost:8888 .ht.router.php
    ```
9. Open the site in your browser:
    ```bash
    open http://localhost:8888
    ```
10. At this point you can run a quick test to see if you have everything setup correctly by running:
    ```bash
    composer run check:dependencies
    ```

## Notable Modules:

1. _my_testing_module_:
    Most of what we will be working with is located in a custom module called
    my_testing_module. Go to that module's [README](web/modules/custom/my_testing_module)
    to find out more information about what it does.

2. _simpletest_:
    This module is provided by Drupal core. This test will be used for kernel,
    functional, and javascript tests. It also provides a UI in the admin panel
    that can be used to run tests (although we will be focused on test running
    via the command line).

## Test Runners

### PHPUnit/Simpletest (PHP Testing)

#### Run all tests:
```bash
php ./web/core/scripts/run-tests.sh --color --verbose --url 'http://localhost:8888' my_testing_module
```

#### Run specific tests:
```bash
php ./web/core/scripts/run-tests.sh --color --verbose --url 'http://localhost:8888' --class 'Drupal\Tests\my_testing_module\Functional\MyFunctionalTest'
```

If you are only running unit/kernel tests, you can leave off `--url 'http://localhost:8888'`.

### Nightwatch.js (Javascript Testing)

The first time you attempt to run nightwatch.js please ensure you follow the getting started instructions above and ensure that `composer run check:dependencies` runs as expected.

#### Run all tests (from inside the `web/core` directory):
```bash
cd web/core
export DRUPAL_TEST_BASE_URL=http://localhost:8888
yarn test:nightwatch ../modules/custom/my_testing_module/tests/src/Nightwatch
```

#### Run specific tests (from inside the `web/core` directory):
```bash
cd web/core
export DRUPAL_TEST_BASE_URL=http://localhost:8888
yarn test:nightwatch ../modules/custom/my_testing_module/tests/src/Nightwatch/MyNightwatchTest.js
```

### Behat (Behavioral Testing w/ Cucumber)

```bash
BEHAT_PARAMS='{"extensions" : {"Behat\\MinkExtension" : {"base_url" : "http://localhost:8888"}}}' ./vendor/bin/behat
```
