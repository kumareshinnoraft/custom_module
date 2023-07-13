<?php

namespace Drupal\routing\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Routing Controller handles the routing page.
 *
 * @package Drupal\Core\Controller\ControllerBase
 */
class RoutingController extends ControllerBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * This constructor initialize the services.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   */
  public function __construct(AccountProxyInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
    );
  }

  /**
   * If user is not content editor then this page will be shown.
   *
   * @return array
   *   If the conditions are same then it shows welcome message.
   */
  public function build() {
    // Returning a simply welcome message to the user.
    return [
      '#title' => $this->t('Welcome'),
    ];
  }

  /**
   * This function is used for the dynamic value from the URL.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   This is the request that holds the client side requested data.
   *
   * @return array
   *   Returns array with global value translate variable.
   */
  public function parameter(Request $request) {
    return [
      '#title' => $this->t('Welcome @num', ['@num' => $request->get('num')]),
    ];
  }

  /**
   * Custom access callback for the route.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   Depending on the user permission, user will be redirected.
   */
  public function access() {
    // Checking if the user has the permission to access the page.
    if ($this->currentUser()->hasPermission('access the custom page')) {
      // User has the permission, allow access.
      return AccessResult::neutral();
    }
    return AccessResult::forbidden();
  }

}
