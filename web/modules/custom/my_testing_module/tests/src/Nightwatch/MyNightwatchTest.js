module.exports = {
  '@tags': ['my_testing_module'],
  before: function(browser) {
    browser
      .drupalInstall({
        setupFile: __dirname + '/fixtures/TestSiteInstallTestScript.php',
      })
  },
  after: function(browser) {
    browser
      .drupalUninstall();
  },
  'Visit the message module settings and toggle log settings': (browser) => {
    // Navigate to module admin setting.
    browser
      .drupalLoginAsAdmin()
      .drupalRelativeURL('/admin/config/system/my_testing_module/settings');

    // Confirm checkbox is checked.
    browser
      .assert.cssClassPresent('label[for=edit-log-users]', 'my-unchecked-class')
      .assert.cssClassNotPresent('label[for=edit-log-users]', 'my-checked-class')
      .execute(function() {
        return Drupal.myMessageLogging.myCheckbox.checked;
      }, [], function (result) {
        browser.assert.strictEqual(result.value, false);
      });

    // Click button and confirm toggle state is correct.
    browser
      .click('input[name=log_users]')
      .assert.cssClassPresent('label[for=edit-log-users]', 'my-checked-class')
      .assert.cssClassNotPresent('label[for=edit-log-users]', 'my-unchecked-class')
      .execute(function() {
        return Drupal.myMessageLogging.myCheckbox.checked;
      }, [], function (result) {
        browser.assert.strictEqual(result.value, true);
      });

    // Click button again and confirm toggle state is correct.
    browser
      .click('input[name=log_users]')
      .assert.cssClassPresent('label[for=edit-log-users]', 'my-unchecked-class')
      .assert.cssClassNotPresent('label[for=edit-log-users]', 'my-checked-class')
      .execute(function() {
        return Drupal.myMessageLogging.myCheckbox.checked;
      }, [], function (result) {
        browser.assert.strictEqual(result.value, false);
      });

    // Clean up our session.
    browser
      .end()
  },
};
