<?php

namespace Drupal\custom_hook\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "custom_hook_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("Custom")
 * )
 */
class ExampleBlock extends BlockBase {

  public const SHARED_DATA = [
    'name' => 'Kumaresh Baksi',
    'college' => 'BBIT',
    'department' => 'CSE'
  ];

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Calling custom hook whenever this block will be present in a page this
    // hook will be called. 
    \Drupal::moduleHandler()->invokeAll('items_list', [ExampleBlock::SHARED_DATA]);

    return [
      '#title' => "This block calls a hook"
    ];
  }
}
