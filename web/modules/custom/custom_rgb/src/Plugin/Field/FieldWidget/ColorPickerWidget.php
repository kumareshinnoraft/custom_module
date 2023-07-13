<?php

namespace Drupal\custom_rgb\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

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
class ColorPickerWidget extends FieldWidgetBase implements ContainerFactoryPluginInterface {
  /**
   * This object is the storage of the user entity.
   *
   * @var object
   */
  private $currentUser;

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Check if the user has the 'administrator' role.
    if ($this->isAdminUser()) {

      $element['color_picker'] = [
        '#type' => 'color',
        '#title' => $this->t('Color Picker'),
        '#default_value' => $items[$delta]->color_picker ?? NULL,
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
      if ($value['color_picker'] === '') {
        $values[$delta]['color_picker'] = NULL;
      }
    }
    return $values;
  }

}
