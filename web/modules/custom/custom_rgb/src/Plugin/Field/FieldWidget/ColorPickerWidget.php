<?php

namespace Drupal\custom_rgb\Plugin\Field\FieldWidget;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'custom_rgb_field' field widget.
 *
 * @FieldWidget(
 *   id = "color_picker_widget",
 *   label = @Translation("Color Picker"),
 *   field_types = {"custom_rgb_field"},
 *   settings = {
 *     "access" = "administer site configuration"
 *   }
 * )
 *
 * @package Drupal\custom_rgb\Plugin\Field\FieldWidget
 */
class ColorPickerWidget extends FieldWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    if ($this->isAdminUser()) {

<<<<<<< HEAD
      $color = $items[$delta]->color_code;

      if (!Color::validateHex($items[$delta]->color_code)) {
        $value = Json::decode($items[$delta]->color_code);
        $rgb_value['red'] = $value['r'];
        $rgb_value['green'] = $value['g'];
        $rgb_value['blue'] = $value['b'];
        $color = Color::rgbToHex($rgb_value);
      }

      if (!strpos($color, '#') === 0) {
        $color = '#' . $color;
=======
      $color = NULL;

      if (!empty($items[$delta]->color_code)) {
        $color = $items[$delta]->color_code;

        if (!Color::validateHex($items[$delta]->color_code)) {
          $value = Json::decode($items[$delta]->color_code);
          $rgb_value['red'] = $value['r'];
          $rgb_value['green'] = $value['g'];
          $rgb_value['blue'] = $value['b'];
          $color = Color::rgbToHex($rgb_value);
        }
        if (strpos($color, '#') === 0) {
          $color = '#' . $color;
        }
>>>>>>> FT2023-327
      }

      $element['color_code'] = [
        '#type' => 'color',
        '#title' => $this->t('Color Picker'),
        '#default_value' => $color ?? NULL,
        '#size' => 20,
      ];

      $element['#theme_wrappers'] = ['container', 'form_element'];
      $element['#attributes']['class'][] = 'container-inline';
      $element['#attributes']['class'][] = 'custom-rgb-field-elements';
      $element['#attached']['library'][] = 'custom_rgb/custom_rgb_field';

      return $element;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      if ($value['color_code'] === '') {
        $values[$delta]['color_code'] = NULL;
      }
    }
    return $values;
  }

}
