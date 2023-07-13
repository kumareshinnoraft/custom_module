<?php

namespace Drupal\custom_rgb\Plugin\Field\FieldFormatter;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * This class is used for fetching data and showing in the page.
 *
 * @FieldFormatter(
 *   id = "custom_rgb_field_default",
 *   label = @Translation("Default"),
 *   field_types = {"custom_rgb_field"}
 * )
 *
 * @package Drupal\custom_rgb\Plugin\Field\FieldFormatter
 */
class CustomFieldDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    // Getting all elements.
    foreach ($items as $delta => $item) {
      if ($item->six_digit_hex_code) {

        $color = $item->six_digit_hex_code;

        $element[$delta]['color_picker'] = [
          '#theme' => 'custom_rgb_color_picker',
          '#content' => '#' . $color,
        ];
      }

      if ($item->rgb_value) {

        $values = Json::decode($item->rgb_value);

        $element[$delta]['rgb_value'] = [
          '#theme' => 'custom_rgb_color_picker',
          '#content' => 'rgb(' . $values['r'] . ',' . $values['g'] . ',' . $values['b'] . ')',
        ];
      }

      if ($item->color_picker) {
        $color = $item->color_picker;

        $element[$delta]['color_picker'] = [
          '#theme' => 'custom_rgb_color_picker',
          '#content' => $color,
        ];
      }
    }
    return $element;
  }

}
