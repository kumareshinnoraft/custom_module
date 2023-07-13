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
 *   id = "hex_widget",
 *   label = @Translation("Hex Field"),
 *   field_types = {"custom_rgb_field"},
 *   settings = {
 *     "access" = "administer site configuration"
 *   }
 * )
 *
 * @package Drupal\custom_rgb\Plugin\Field\FieldWidget
 */
class HexWidget extends FieldWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Check if the user has the 'administrator' role.
    if ($this->isAdminUser()) {

      $color = $items[$delta]->color_code;

      if (!Color::validateHex($items[$delta]->color_code)) {
        $value = Json::decode($items[$delta]->color_code);
        $color = $value['r'] . $value['g'] . $value['b'];
      }

      $element['color_code'] = [
        '#type' => 'textfield',
        '#title' => $this->t('6-digit hex code'),
        '#default_value' => $color ?? NULL,
        '#size' => 6,
        '#max' => 5,
        '#step' => 1,
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
      elseif (!Color::validateHex($value['color_code'])) {
        $form_state->setErrorByName('color_code', 'Invalid hex value');
      }
    }
    return $values;
  }

}
