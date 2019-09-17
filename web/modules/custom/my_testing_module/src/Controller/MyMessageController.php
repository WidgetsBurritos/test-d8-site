<?php

namespace Drupal\my_testing_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for custom messages.
 */
class MyMessageController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $user;

  /**
   * Constructs a new MyMessageController.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $user
   *   Current user.
   */
  public function __construct(AccountProxyInterface $user) {
    $this->user = $user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  /**
   * Retrieves the message for the current user.
   */
  public function getMessageForCurrentUser() {
    if ($this->user->hasPermission('my super secret privilege')) {
      return $this->t("You aren't all that special.");
    }
    elseif ($this->user->hasPermission('yet another privilege')) {
      return $this->t('You have yet another privilege.');
    }
    return $this->t('You might be logged in.');
  }

  /**
   * Renders modal content.
   */
  public function displayMessage() {
    return [
      '#markup' => $this->getMessageForCurrentUser(),
    ];
  }

  /**
   * Returns the title of the route.
   */
  public function title() {
    return $this->t('Hi @user.', ['@user' => $this->user->getDisplayName()]);
  }

}
