<?php

namespace Drupal\my_testing_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for custom messages.
 */
class MyMessageController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $user;

  /**
   * Constructs a new MyMessageController.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   Current user.
   */
  public function __construct(AccountInterface $user) {
    $this->user = $user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')->getAccount()
    );
  }

  /**
   * Retrieves the message for the specified user.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   User account.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   User message.
   */
  public function getMessageForUser(AccountInterface $user) {
    if ($user->hasPermission('my super secret privilege')) {
      return $this->t("You aren't all that special.");
    }
    elseif ($user->hasPermission('yet another privilege')) {
      return $this->t('You have yet another privilege.');
    }
    return $this->t('You might be logged in.');
  }

  /**
   * Renders modal content.
   */
  public function displayMessage() {
    return [
      '#markup' => $this->getMessageForUser($this->user),
    ];
  }

  /**
   * Returns the title of the route.
   */
  public function title() {
    return $this->t('Hi @user.', ['@user' => $this->user->getDisplayName()]);
  }

}
