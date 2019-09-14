<?php

namespace Drupal\my_testing_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

/**
 * Controller for custom messages.
 */
class MyMessageController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Retrieves the message for the current user.
   */
  public function getMessageForCurrentUser() {
    $user = \Drupal::currentUser();
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
      '#markup' => $this->getMessageForCurrentUser(),
    ];
  }

  /**
   * Returns the title of the route.
   */
  public function title() {
    $user = \Drupal::currentUser();
    return $this->t('Hi @user.', ['@user' => $user->getDisplayName()]);
  }

}
