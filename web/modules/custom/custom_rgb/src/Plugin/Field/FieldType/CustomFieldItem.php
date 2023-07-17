<?php

namespace Drupal\custom_rgb\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * This class defines the 'custom_rgb_field' field type.
 *
 * @FieldType(
 *   id = "custom_rgb_field",
 *   label = @Translation("Custom Field"),
 *   category = @Translation("General"),
 *   default_widget = "color_picker_widget",
 *   default_formatter = "colored_text_formatter"
 * )
 *
 * @package Drupal\custom_rgb\Plugin\Field\FieldType
 */
class CustomFieldItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    if ($this->color_code !== NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $properties['color_code'] = DataDefinition::create('string')
      ->setLabel(t('Color code for the field'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $columns = [
      'color_code' => [
        'type' => 'varchar',
        'length' => 255,
      ],
    ];

    $schema = [
      'columns' => $columns,
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {

    $random = new Random();

    $values['color_code'] = $random->word(mt_rand(1, 255));

    return $values;
  }

}
