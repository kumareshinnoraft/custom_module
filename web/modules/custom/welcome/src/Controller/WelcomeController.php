<?php

namespace Drupal\welcome\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\user\UserStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for welcome routes.
 *
 * @package Drupal\welcome\Controller
 */
class WelcomeController extends ControllerBase {

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
   * Initilize the objects.
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
   * Builds the response for the welcome page.
   *
   * @return array
   *   This array contains the title and markup message.
   */
  public function build() {
    // Getting the current user entity.
    $current_user = $this->userStorage->load($this->currentUser->id());

    // Cache tag has been used to invalidate the cache when the user:1 tag is
    // changed.
    return [
      '#title' => $this->t('Welcome @name', ['@name' => $this->currentUser->getAccountName()]),
      '#markup' => $this->t('This is the home page'),
      '#cache'  => [
        'tags' => $current_user->getCacheTags(),
      ],
    ];
  }

}
