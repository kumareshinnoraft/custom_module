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

    // There are three ways to get current user.



    // 1st
    // $currentUser = $this->container()->get('current_user');

    //2nd, get the current user and necessary information.
    $currentUser        = Drupal::currentUser();

    // Alternative functions can be called.
    $administerContent  = $currentUser->hasPermission('administer content');
    $authenticated      = $currentUser->isAuthenticated();
    $anonymous          = $currentUser->isAnonymous();

    // 3rd, get complete user entity.
    $account = User::load($currentUser->id());

    // Alternative approach.
    // $build['#title'] = $this->t('Welcome User');
 
    return [
      '#title'  => $this->t('Welcome ' . $account->getDisplayName()),
      '#markup' => $this->t('This is the home page')
    ];    
  }
}
