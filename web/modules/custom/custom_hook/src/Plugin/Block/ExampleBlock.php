<?php

namespace Drupal\custom_hook\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Extension\ModuleHandler;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "custom_hook_example",
 *   admin_label = @Translation("Custom Hook"),
 *   category = @Translation("Custom Module")
 * )
 */
class ExampleBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * This object is the storage of the user entity.
   *
   * @var object
   */
  private $moduleHandler;

  /**
   * Constructs a Drupalist object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Extension\ModuleHandler $module_handler
   *   The current_user.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ModuleHandler $module_handler
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('module_handler')
    );
  }

  public const SHARED_DATA = [
    'name' => 'Kumaresh Baksi',
    'college' => 'BBIT',
    'department' => 'CSE',
  ];

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Calling custom hook whenever this block will be present in a page this
    // hook will be called.
    $this->moduleHandler->invokeAll('items_list', [ExampleBlock::SHARED_DATA]);

    return [
      '#title' => "This block calls a hook",
    ];
  }

}
