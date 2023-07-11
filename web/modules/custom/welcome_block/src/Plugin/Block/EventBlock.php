<?php

namespace Drupal\welcome_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a hcl events block.
 *
 * @Block(
 *   id = "welcome_block_event",
 *   admin_label = @Translation("FLAGSHIP PROGRAMMES"),
 *   category = @Translation("Custom Module")
 * )
 */
class EventBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs an EventBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get the tag values from the configuration.
    $config = $this->configFactory->get('hcl_events_config_form.settings');
    $tagValues = $config->get('tag_values') ?? [];

    $build['#attached']['library'][] = 'welcome_block/welcome_block';

    // Build the block content.
    $build['content'] = [
      '#theme' => 'hcl_events_block',
      '#content' => $tagValues,
      '#cache' => [
        'tags' => ['hcl_events_config_form'],
      ],
    ];
    return $build;
  }

}
