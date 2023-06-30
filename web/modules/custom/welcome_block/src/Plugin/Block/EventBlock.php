<?php 

namespace Drupal\welcome_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a hcl events block.
 *
 * @Block(
 *   id = "welcome_block_event",
 *   admin_label = @Translation("FLAGSHIP PROGRAMMES"),
 *   category = @Translation("Custom Module")
 * )
 */
class EventBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get the tag values from the configuration.
    $config = \Drupal::config('hcl_events_config_form.settings');
    $tagValues = $config->get('tag_values') ?? [];

    $build['#attached']['library'][] = 'welcome_block/welcome_block';

    // Build the block content.
    $build['content'] = [
      '#theme' => 'hcl_events_block',
      '#content' => $tagValues,
      '#cache' => [
        'tags' => ['hcl_events_config_form'] 
      ]
    ];

    return $build;
  }
}
