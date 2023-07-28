<?php

namespace Drupal\custom_rgb\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;

/**
 * This class is used for fetching data and showing in the page.
 *
 * @FieldFormatter(
 *   id = "colored_text_formatter",
 *   label = @Translation("Colored Text"),
 *   field_types = {"custom_rgb_field"}
 * )
 *
 * @package Drupal\custom_rgb\Plugin\Field\FieldFormatter
 */
class ColoredText extends ColorFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    // Getting all elements.
    foreach ($items as $delta => $item) {
      if ($item->color_code) {

        $color = $this->getColor($items, $delta);

        $element[$delta]['color_code'] = [
          '#theme' => 'custom_rgb_color_picker',
          '#content' => $color,
          '#color' => $color,
        ];
      }
    }
    return $element;
  }

}
