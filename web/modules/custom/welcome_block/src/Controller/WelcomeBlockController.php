<?php

namespace Drupal\welcome_block\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Welcome Block routes.
 * 
 * @package Drupal\welcome_block\Controller
 */
class WelcomeBlockController extends ControllerBase {

  /**
   * Demo route controller.
   * 
   * @return array
   *   This render array returns the output of the page.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
    ];
    
    return $build;
  }
}
