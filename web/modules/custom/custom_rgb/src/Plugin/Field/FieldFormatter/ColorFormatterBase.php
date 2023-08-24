<?php

namespace Drupal\custom_rgb\Plugin\Field\FieldFormatter;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * This class represents the base formatter where common functions will be used.
 *
 * @package Drupal\custom_rgb\Plugin\Field\FieldFormatter;
 */
class ColorFormatterBase extends FormatterBase {

  /**
   * This function returns a valid color name.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *   This array contains the value of the color_code.
   * @param int $delta
   *   Count of the foreach loop.
   *
   * @return string
   *   This returns the valid color.
   */
  public function getColor($items, $delta) {

    $color = $items[$delta]->color_code;
    if (!Color::validateHex($items[$delta]->color_code)) {

      $value = Json::decode($items[$delta]->color_code);
      $color = 'rgb(' . $value['r'] . ',' . $value['g'] . ',' . $value['b'] . ')';
    }
    else {
      if (!strpos($color, '#') === 0) {
        $color = '#' . $color;
      }
    }

    return $color;
  }

  /**
   * {@inheritDoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    // Implementing the abstract method.
  }

}
