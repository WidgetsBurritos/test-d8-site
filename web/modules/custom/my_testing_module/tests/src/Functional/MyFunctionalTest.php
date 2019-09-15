<?php

namespace Drupal\Tests\my_testing_module\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Functional tests for my_testing_module.
 *
 * @group my_testing_module
 */
class MyFunctionalTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public $profile = 'testing';

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'my_testing_module',
  ];

  /**
   * Functional test of importing web page archive entities with preset uuid.
   */
  public function testMessageControllerIsLoading() {
    $assert = $this->assertSession();
    $this->drupalGet('my-message');
    $assert->pageTextContains('Hi Anonymous.');
  }

}
