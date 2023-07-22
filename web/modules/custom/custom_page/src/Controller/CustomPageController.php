<?php

namespace Drupal\custom_page\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for custom_page routes.
 */
class CustomPageController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t(''),
    ];

    return $build;
  }

}
