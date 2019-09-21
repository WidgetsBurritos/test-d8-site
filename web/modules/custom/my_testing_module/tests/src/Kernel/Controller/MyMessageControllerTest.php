<?php

namespace Drupal\Tests\my_testing_module\Kernel\Controller;

use Drupal\Core\Logger\RfcLogLevel;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Drupal\my_testing_module\Controller\MyMessageController;

/**
 * Tests the functionality of my message controller.
 *
 * @group my_testing_module
 */
class MyMessageControllerTest extends KernelTestBase {

  use UserCreationTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['my_testing_module', 'dblog', 'system', 'user'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installSchema('dblog', ['watchdog']);
  }

  /**
   * Helper method to retrieve the last watchdog message by severity.
   *
   * @param string $severity
   *   Message severity.
   * @param int $row_ct
   *   Number of rows to return.
   *
   * @return array
   *   Associative array containing messages and variables.
   */
  protected function getLastWatchdogRowsBySeverity($severity, $row_ct = 1) {
    $query = $this->container->get('database')->select('watchdog', 'w');
    $query->fields('w', ['message', 'variables']);
    $query->condition('w.type', 'my_testing_module');
    $query->condition('w.severity', $severity);
    $query->orderBy('w.timestamp', 'DESC');
    $query->range(0, $row_ct);

    return $query->execute()->fetchAll(\PDO::FETCH_ASSOC);
  }

  /**
   * Tests admin user messages are properly logged, if logging enabled.
   *
   * @covers \Drupal\my_testing_module\Controller\MyMessageController::displayMessage
   */
  public function testGetMessageForAdminUserLogsMessagesWhenSet() {
    // Enable logging.
    $this->config(MyMessageController::SETTINGS)
      ->set('log_users', TRUE)
      ->save();

    // Setup Admin User (UID=1).
    $this->setUpCurrentUser(['uid' => 1]);
    $controller = MyMessageController::create($this->container);

    // Confirm response.
    $expected = [
      '#markup' => implode('<br>', [
        'You are logged in.',
        'You are special.',
        'You have yet another privilege.',
      ]),
    ];
    $this->assertEquals($expected, $controller->displayMessage());

    // Confirm log messages.
    $log_messages = $this->getLastWatchdogRowsBySeverity(RfcLogLevel::INFO, 3);
    $expected = [
      ['message' => 'super secret privilege granted', 'variables' => serialize([])],
      ['message' => 'yet another privilege granted', 'variables' => serialize([])],
    ];
    $this->assertEquals($expected, $log_messages);
  }

  /**
   * Tests admin user messages aren't logged, if logging disabled.
   *
   * @covers \Drupal\my_testing_module\Controller\MyMessageController::displayMessage
   */
  public function testGetMessageForAdminUserDoesNotLogMessagesWhenNotSet() {
    // Enable logging.
    $this->config(MyMessageController::SETTINGS)
      ->set('log_users', FALSE)
      ->save();

    // Setup Admin User (UID=1).
    $this->setUpCurrentUser(['uid' => 1]);
    $controller = MyMessageController::create($this->container);

    // Confirm response.
    $expected = [
      '#markup' => implode('<br>', [
        'You are logged in.',
        'You are special.',
        'You have yet another privilege.',
      ]),
    ];
    $this->assertEquals($expected, $controller->displayMessage());

    // Confirm log messages.
    $log_messages = $this->getLastWatchdogRowsBySeverity(RfcLogLevel::INFO, 3);
    $expected = [];
    $this->assertEquals($expected, $log_messages);
  }

  /**
   * Tests admin user messages are properly logged, if logging enabled.
   *
   * @covers \Drupal\my_testing_module\Controller\MyMessageController::displayMessage
   */
  public function testGetMessageForSpecialUserLogsMessagesWhenSet() {
    // Enable logging.
    $this->config(MyMessageController::SETTINGS)
      ->set('log_users', TRUE)
      ->save();

    // Setup Super Secret User.
    $this->setUpCurrentUser(['uid' => 2], ['my super secret privilege']);
    $controller = MyMessageController::create($this->container);

    // Confirm response.
    $expected = [
      '#markup' => implode('<br>', [
        'You are logged in.',
        'You are special.',
      ]),
    ];
    $this->assertEquals($expected, $controller->displayMessage());

    // Confirm log messages.
    $log_messages = $this->getLastWatchdogRowsBySeverity(RfcLogLevel::INFO, 3);
    $expected = [
      ['message' => 'super secret privilege granted', 'variables' => serialize([])],
    ];
    $this->assertEquals($expected, $log_messages);
  }

  /**
   * Tests special user messages aren't logged, if logging disabled.
   *
   * @covers \Drupal\my_testing_module\Controller\MyMessageController::displayMessage
   */
  public function testGetMessageForSpecialUserDoesNotLogMessagesWhenNotSet() {
    // Enable logging.
    $this->config(MyMessageController::SETTINGS)
      ->set('log_users', FALSE)
      ->save();

    // Setup Super Secret User.
    $this->setUpCurrentUser(['uid' => 2], ['my super secret privilege']);
    $controller = MyMessageController::create($this->container);

    // Confirm response.
    $expected = [
      '#markup' => implode('<br>', [
        'You are logged in.',
        'You are special.',
      ]),
    ];
    $this->assertEquals($expected, $controller->displayMessage());

    // Confirm log messages.
    $log_messages = $this->getLastWatchdogRowsBySeverity(RfcLogLevel::INFO, 3);
    $expected = [];
    $this->assertEquals($expected, $log_messages);
  }

  /**
   * Tests yet another user messages are properly logged, if logging enabled.
   *
   * @covers \Drupal\my_testing_module\Controller\MyMessageController::displayMessage
   */
  public function testGetMessageForYetAnotherUserLogsMessagesWhenSet() {
    // Enable logging.
    $this->config(MyMessageController::SETTINGS)
      ->set('log_users', TRUE)
      ->save();

    // Setup Super Secret User.
    $this->setUpCurrentUser(['uid' => 2], ['yet another privilege']);
    $controller = MyMessageController::create($this->container);

    // Confirm response.
    $expected = [
      '#markup' => implode('<br>', [
        'You are logged in.',
        'You have yet another privilege.',
      ]),
    ];
    $this->assertEquals($expected, $controller->displayMessage());

    // Confirm log messages.
    $log_messages = $this->getLastWatchdogRowsBySeverity(RfcLogLevel::INFO, 3);
    $expected = [
      ['message' => 'yet another privilege granted', 'variables' => serialize([])],
    ];
    $this->assertEquals($expected, $log_messages);
  }

  /**
   * Tests yet another user messages aren't logged, if logging disabled.
   *
   * @covers \Drupal\my_testing_module\Controller\MyMessageController::displayMessage
   */
  public function testGetMessageForYetAnotherUserDoesNotLogMessagesWhenNotSet() {
    // Enable logging.
    $this->config(MyMessageController::SETTINGS)
      ->set('log_users', FALSE)
      ->save();

    // Setup Super Secret User.
    $this->setUpCurrentUser(['uid' => 2], ['yet another privilege']);
    $controller = MyMessageController::create($this->container);

    // Confirm response.
    $expected = [
      '#markup' => implode('<br>', [
        'You are logged in.',
        'You have yet another privilege.',
      ]),
    ];
    $this->assertEquals($expected, $controller->displayMessage());

    // Confirm log messages.
    $log_messages = $this->getLastWatchdogRowsBySeverity(RfcLogLevel::INFO, 3);
    $expected = [];
    $this->assertEquals($expected, $log_messages);
  }

  /**
   * Tests regular user messages are properly logged, if logging enabled.
   *
   * @covers \Drupal\my_testing_module\Controller\MyMessageController::displayMessage
   */
  public function testGetMessageForRegularUserLogsMessagesWhenSet() {
    // Enable logging.
    $this->config(MyMessageController::SETTINGS)
      ->set('log_users', TRUE)
      ->save();

    // Setup Super Secret User.
    $this->setUpCurrentUser(['uid' => 2]);
    $controller = MyMessageController::create($this->container);

    // Confirm response.
    $expected = ['#markup' => 'You are logged in.'];
    $this->assertEquals($expected, $controller->displayMessage());

    // Confirm log messages.
    $log_messages = $this->getLastWatchdogRowsBySeverity(RfcLogLevel::WARNING, 3);
    $expected = [
      ['message' => 'unprivileged access', 'variables' => serialize([])],
    ];
    $this->assertEquals($expected, $log_messages);
  }

  /**
   * Tests regular user messages aren't logged, if logging disabled.
   *
   * @covers \Drupal\my_testing_module\Controller\MyMessageController::displayMessage
   */
  public function testGetMessageForRegularUserDoesNotLogMessagesWhenNotSet() {
    // Enable logging.
    $this->config(MyMessageController::SETTINGS)
      ->set('log_users', FALSE)
      ->save();

    // Setup Super Secret User.
    $this->setUpCurrentUser(['uid' => 2]);
    $controller = MyMessageController::create($this->container);

    // Confirm response.
    $expected = ['#markup' => 'You are logged in.'];
    $this->assertEquals($expected, $controller->displayMessage());

    // Confirm log messages.
    $log_messages = $this->getLastWatchdogRowsBySeverity(RfcLogLevel::INFO, 3);
    $expected = [];
    $this->assertEquals($expected, $log_messages);
  }

}
