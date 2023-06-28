<?php

namespace Drupal\welcome\Controller;

use Drupal;
use Drupal\user\Entity\User;
use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for welcome routes.
 *
 * @package Drupal\welcome\Controller
 */
class WelcomeController extends ControllerBase {

  /**
   * Builds the response for the welcome page.
   * 
   * @return array
   *   This array contains the title and markup message.
   */
  public function build() {

    // Getting all information about the current user
    $currentUser        = Drupal::currentUser();

    // This provides fully User entity.
    $account = User::load($currentUser->id());
    
    // Cache tag has been used to invalidate the cache when the user:1 tag is
    // changed.
    return [
      '#title'  => $this->t('Welcome ' . $currentUser->getAccountName()),
      '#markup' => $this->t('This is the home page'),
      '#cache'  => [
        'tags' => $account->getCacheTags(),
      ]
    ];    
  }
}
