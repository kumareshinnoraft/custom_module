<?php

namespace Drupal\custom_rgb\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This class is the helper class of the other widget.
 *
 * @package Drupal\custom_rgb\Plugin\Field\FieldWidget.
 */
class FieldWidgetBase extends WidgetBase implements ContainerFactoryPluginInterface {
  /**
   * This object is the storage of the user entity.
   *
   * @var object
   */
  private $currentUser;

  /**
   * Constructs a Drupal list object.
   *
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param mixed $field_definition
   *   To describe field definition this parameter is used.
   * @param mixed $settings
   *   Settings of the plugin.
   * @param mixed $third_party_settings
   *   If any third party settings are present that will be here.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current_user.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, AccountInterface $current_user) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($plugin_id, $plugin_definition, $configuration['field_definition'], $configuration['settings'], $configuration['third_party_settings'], $container->get('current_user'));
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // No use of it.
  }

  /**
   * This function check weather the user is admin or not.
   *
   * @return bool
   *   Based on the user it returns the boolean value.
   */
  public function isAdminUser() {
    if (in_array('administrator', $this->currentUser->getRoles())) {
      return TRUE;
    }
    return FALSE;
  }

}
