<?php

namespace Drupal\hello_user\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;

/**
 * Returns responses for welcome routes.
 *
 * @package Drupal\Core\Controller\ControllerBase.
 */
class HelloUserController extends ControllerBase {

  /**
   * Greets user function is used to greet the user.
   * 
   * @return array
   *   This array contains the title and markup message.
   */
  public function greetUser() {

    // Getting current logged user.
    $current_user = Drupal::currentUser();

    // This provides fully User entity.
    $account = User::load($current_user->id());

    // Returning the tags for getting new user name when it is changed.
    return [
      '#title'  => $this->t('Welcome ' . $current_user->getDisplayName()),
      '#markup' => $this->t('This is the home page'),
      '#cache'  => [
        'tags' => $account->getCacheTags()
      ]
    ];
  }
}

