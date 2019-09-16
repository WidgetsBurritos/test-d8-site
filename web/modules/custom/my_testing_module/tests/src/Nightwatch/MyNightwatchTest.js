module.exports = {
  '@tags': ['my_testing_module'],
  before: function(browser) {
    browser
      .drupalInstall();
  },
  after: function(browser) {
    browser
      .drupalUninstall();
  },
  'Visit the home page and ensure Powered By Drupal is present': (browser) => {
    browser
      .drupalRelativeURL('/')
      .end();
  },

};
