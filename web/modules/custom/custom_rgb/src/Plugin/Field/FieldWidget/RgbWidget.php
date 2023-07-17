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
 *   id = "rgb_widget",
 *   label = @Translation("RGB Field"),
 *   field_types = {"custom_rgb_field"},
 *   settings = {
 *     "access" = "administer site configuration"
 *   }
 * )
 */
class RgbWidget extends FieldWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Check if the user has the 'administrator' role.
    if ($this->isAdminUser()) {

      if (!Color::validateHex($items[$delta]->color_code)) {
        $values = Json::decode($items[$delta]->color_code ?? '');
      }
      else {
        $values = Color::hexToRgb($items[$delta]->color_code);
        $values['r'] = $values['red'];
        $values['g'] = $values['green'];
        $values['b'] = $values['blue'];
      }

      $element['color_code'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['rgb-color-wrapper']],
        '#size' => 2,
      ];

      $element['color_code']['r'] = [
        '#type' => 'number',
        '#title' => $this->t('R'),
        '#default_value' => isset($items[$delta]->color_code) ? $values['r'] : NULL,
        '#min' => 0,
        '#max' => 255,
        '#step' => 1,
      ];

      $element['color_code']['g'] = [
        '#type' => 'number',
        '#title' => $this->t('G'),
        '#default_value' => isset($items[$delta]->color_code) ? $values['g'] : NULL,
        '#min' => 0,
        '#max' => 255,
        '#step' => 1,
      ];

      $element['color_code']['b'] = [
        '#type' => 'number',
        '#title' => $this->t('B'),
        '#default_value' => isset($items[$delta]->color_code) ? $values['b'] : NULL,
        '#min' => 0,
        '#max' => 255,
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
      $rgb = [
        $value['color_code']['r'],
        $value['color_code']['g'],
        $value['color_code']['b'],
      ];

      if ($value['color_code']['r'] === '' && $value['color_code']['g'] === '' && $value['color_code']['b'] === '') {
        $values[$delta]['color_code'] = NULL;
      }
      elseif (!Color::validateHex(Color::rgbToHex($rgb))) {
        $form_state->setErrorByName($this->fieldDefinition->getName(), $this->t('Invalid RGB value for field @field', ['@field' => $this->fieldDefinition->getLabel()]));
      }
      else {
        // Convert the values of RGB value to JSON for storing in the database.
        $values[$delta]['color_code'] = Json::encode($value['color_code']);
      }
    }

    return $values;
  }

}
