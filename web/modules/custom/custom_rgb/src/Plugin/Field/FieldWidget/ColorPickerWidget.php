<?php

namespace Drupal\custom_rgb\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;

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
class ColorPickerWidget extends WidgetBase
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

      $element['color_picker'] = [
        '#type' => 'color',
        '#title' => $this->t('Color Picker'),
        '#default_value' => isset($items[$delta]->color_picker) ? $items[$delta]->color_picker : null,
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
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state)
  {
    foreach ($values as $delta => $value) {
      if ($value['color_picker'] === '') {
        $values[$delta]['color_picker'] = NULL;
      }
    }
    return $values;
  }
}