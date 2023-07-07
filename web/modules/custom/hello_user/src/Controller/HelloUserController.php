<?php

namespace Drupal\hello_user\Controller;

use Drupal\user\UserStorageInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for welcome routes.
 *
 * @package Drupal\Core\Controller\ControllerBase.
 */
class HelloUserController extends ControllerBase {

  /**
   * The current user.
   *
   * @var AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The user storage.
   *
   * @var UserStorageInterface
   */
  protected $userStorage;

  /**
   * This constructor initialize the services.
   *
   * @param AccountProxyInterface
   *   The current user.
   * @param UserStorageInterface
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

    // Returning the tags for getting new user name when it is changed.
    return [
      '#title'  => $this->t('Welcome ' . $current_user->getDisplayName()),
      '#markup' => $this->t('This is the home page'),
      '#cache'  => [
        'tags' => $this->currentUser()->getCacheTags()
      ]
    ];
  }
}
