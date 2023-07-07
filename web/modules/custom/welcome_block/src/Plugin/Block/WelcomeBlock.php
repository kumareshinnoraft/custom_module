<?php

namespace Drupal\welcome_block\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a custom block.
 *
 * @Block(
 *   id = "welcome_block",
 *   admin_label = @Translation("Welcome Block"),
 *   category = @Translation("Custom Block")
 * )
 */
class WelcomeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current user.
   *
   * @var AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Initialize the objects.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current_user.
   */
  public function __construct(
    array $configuration, 
    $plugin_id, 
    $plugin_definition, 
    $currentUser,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $currentUser;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user')
    );
  }

  /**
   * This function will be used to make a welcome block which will be shown in
   * a custom page.
   */
  public function build() {
    
    // Returning user with a custom message. Rendering the array of roles and
    // showing last role of the user.
    return [
      '#title' => 'Welcome to the custom block ' . $this->currentUser->getRoles()[sizeof($this->currentUser->getRoles()) - 1]
    ];
  }
}
