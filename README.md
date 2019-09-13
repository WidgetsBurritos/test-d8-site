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

## Getting started

1. Fork the https://github.com/WidgetsBurritos/test-d8-site repo.
2. Clone your fork locally and change into that directory.
3. Install dependencies:
    ```bash
    composer install
    npm install
    ```
4. Do a basic site install:
    ```bash
    vendor/bin/drush si -y --db-url=sqlite://sites/example.com/files/.ht.sqlite --account-pass=admin
    ```
5. Import the config:
    ```bash
    vendor/bin/drush cim -y
    ```
6. Create some more users and set roles
    ```bash
    vendor/bin/drush user:create bobby --mail="bobby@example.com" --password="bobby"
    vendor/bin/drush user:create carol --mail="carol@example.com" --password="carol"
    vendor/bin/drush user-add-role "super_secret" bobby
    vendor/bin/drush user-add-role "yet_another_role" carol
    ```
7. Start a local web server:
    ```bash
    cd web
    php -S localhost:8888 .ht.router.php
    ```
8. Open the site in your browser:
    ```bash
    open http://localhost:8888
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
