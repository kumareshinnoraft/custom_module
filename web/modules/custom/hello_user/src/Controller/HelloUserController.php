<?php

namespace Drupal\hello_user\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;

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

    return [
      '#title'  => $this->t('Welcome ' . $current_user->getDisplayName()),
      '#markup' => $this->t('This is the home page')
    ];
  }
}

