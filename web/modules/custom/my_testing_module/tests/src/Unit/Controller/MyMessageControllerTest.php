<?php

namespace Drupal\Tests\my_testing_module\Unit\Controller;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Tests\UnitTestCase;
use Drupal\my_testing_module\Controller\MyMessageController;

/**
 * @coversDefaultClass \Drupal\my_testing_module\Controller\MyMessageController
 *
 * @group my_testing_module
 */
class MyMessageControllerTest extends UnitTestCase {

  use StringTranslationTrait;

  /**
   * The current user.
   *
   * @var \Drupal\user\Entity\UserInterface
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->user = $this->getMockBuilder('\Drupal\Core\Session\AccountInterface')
      ->getMock();
    $this->user->expects($this->any())
      ->method('getDisplayName')
      ->will($this->returnValue('John Doe'));
    $this->setStringTranslation($this->getStringTranslationStub());
  }

  /**
   * Confirm controller title is showing the correct user.
   *
   * @covers \Drupal\my_testing_module\Controller\MyMessageController::title
   */
  public function testTitleShowsCurrentUser() {
    $controller = new MyMessageController($this->user);
    $controller->setStringTranslation($this->getStringTranslationStub());
    $expected = $this->t('Hi @user.', ['@user' => 'John Doe']);
    $this->assertEquals($expected, $controller->title());
  }

}
