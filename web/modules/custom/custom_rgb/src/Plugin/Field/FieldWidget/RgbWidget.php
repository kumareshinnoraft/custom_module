<?php

namespace Drupal\custom_rgb\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Component\Utility\Color;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;

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
class RgbWidget extends WidgetBase
{
  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state)
  {
    // Get the current user.
    $current_user = \Drupal::currentUser();

    // Check if the user has the 'administrator' role.
    if ($current_user->hasRole('administrator')) {
      $values = Json::decode(isset($items[$delta]->rgb_value) ? $items[$delta]->rgb_value : '');

      $element['rgb_value'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['rgb-color-wrapper']],
        '#size' => 2,
      ];

      $element['rgb_value']['r'] = [
        '#type' => 'number',
        '#title' => $this->t('R'),
        '#default_value' => isset($items[$delta]->rgb_value) ? $values['r'] : null,
        '#min' => 0,
        '#max' => 255,
        '#step' => 1,
      ];

      $element['rgb_value']['g'] = [
        '#type' => 'number',
        '#title' => $this->t('G'),
        '#default_value' => isset($items[$delta]->rgb_value) ? $values['g'] : null,
        '#min' => 0,
        '#max' => 255,
        '#step' => 1,
      ];

      $element['rgb_value']['b'] = [
        '#type' => 'number',
        '#title' => $this->t('B'),
        '#default_value' => isset($items[$delta]->rgb_value) ? $values['b'] : null,
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
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state)
  {
    foreach ($values as $delta => $value) {

      $rbg = [$value['rgb_value']['r'], $value['rgb_value']['g'], $value['rgb_value']['b'],];
      $hex = Color::rgbToHex($rbg);

      if ($value['rgb_value'] === '') {
        $values[$delta]['rgb_value'] = NULL;
      } 
      elseif (!Color::validateHex($hex)) {
        $form_state->setErrorByName('rgb_value', 'Invalid rgb value');
      }

      // Converting the values of RGB value to JSON for storing in the database.
      $values[$delta]['rgb_value'] = Json::encode($value['rgb_value']);
    }
    return $values;
  }
}
