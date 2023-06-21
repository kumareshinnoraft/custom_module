<?php

namespace Drupal\routing\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * This controller is used to pass dynamic values from parameter and also it 
 * checks if the current user or not if it is a current user then it will block
 * the request and show user access denied page.
 * 
 * @package Drupal\Core\Controller\ControllerBase
 */
class RoutingController extends ControllerBase
{

  /**
   * Build plugin is called when /routing page is hit in the browser and it 
   * checks the current user role and if it is content editor then simply it
   * shows access denied page.
   * 
   * @return array
   *   If the conditions are same then it shows welcome message.
   */
  public function build()
  {
    // Getting the current user.
    $currentUser = Drupal::currentUser();

    // Find the roles from the array.
    if (in_array('content_editor', $currentUser->getRoles())) {
      throw new AccessDeniedHttpException();
    }

    // Returning a simply welcome message to the user.
    return [
      '#title' => $this->t('Welcome')
    ];
  }

  /**
   * This function is used for the dynamic value from the URL.
   * 
   * @param Request $request
   *   This is the request that holds the client side requested data.
   * 
   * @return array
   *   Returns array with global value translate variable.
   */
  public function parameter(Request $request)
  {
    return [
      '#title' => $this->t('Welcome ' . $request->get('num'))
    ];
  }
}
