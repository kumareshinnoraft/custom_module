<?php

namespace Drupal\hello_user\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for welcome routes.
 *
 * @package Drupal\Core\Controller\ControllerBase.
 */
class HelloUserController extends ControllerBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The user storage.
   *
   * @var \Drupal\user\UserStorageInterface
   */
  protected $userStorage;

  /**
   * This constructor initialize the services.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   * @param \Drupal\user\UserStorageInterface $user_storage
   *   The user storage.
   */
  public function __construct(AccountProxyInterface $current_user, UserStorageInterface $user_storage) {
    $this->currentUser = $current_user;
    $this->userStorage = $user_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('entity_type.manager')->getStorage('user')
    );
  }

  /**
   * Greets user function is used to greet the user.
   *
   * @return array
   *   This array contains the title and markup message.
   */
  public function greetUser() {
    // Getting the current user entity.
    $current_user = $this->userStorage->load($this->currentUser->id());

    // Getting current logged user.
    $current_user = Drupal::currentUser();

    return [
      '#title'  => $this->t('Welcome ' . $current_user->getDisplayName()),
      '#markup' => $this->t('This is the home page')
    ];
  }

}
