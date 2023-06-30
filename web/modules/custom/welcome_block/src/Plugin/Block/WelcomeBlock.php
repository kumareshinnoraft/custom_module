<?php

namespace Drupal\welcome_block\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom block.
 *
 * @Block(
 *   id = "welcome_block",
 *   admin_label = @Translation("Welcome Block"),
 *   category = @Translation("Custom Block")
 * )
 */
class WelcomeBlock extends BlockBase {

  /**
   * This function will be used to make a welcome block which will be shown in
   * a custom page.
   */
  public function build() {

    // Fetching current user and it's information.
    $currentUser = Drupal::currentUser();
    
    // Returning user with a custom message. Rendering the array of roles and
    // showing last role of the user.
    return [
      '#title' => 'Welcome to the custom block ' . $currentUser->getRoles()[sizeof($currentUser->getRoles()) - 1]
    ];
  }

}
