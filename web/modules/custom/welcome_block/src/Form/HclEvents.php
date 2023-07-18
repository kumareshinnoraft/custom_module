<?php

namespace Drupal\welcome_block\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * This class takes the input from the user.
 *
 * @package Drupal\welcome_block\Form
 */
class HclEvents extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hcl_events_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['hcl_events_config_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('hcl_events_config_form.settings');

    // First getting the the values from the tag_values.
    $tag_values = $form_state->get('tag_values');

    if (empty($tag_values)) {

      // Getting values from the config factory.
      $tag_values = $config->get('tag_values') ?? [];

      // Setting the values in the form state as tag_values.
      $form_state->set('tag_values', $tag_values);
    }

    $form['tags'] = [
      '#type' => 'details',
      '#title' => "Tags",
      '#open' => TRUE,
      '#description' => $this->t("The description of the field."),
    ];

    $form['tags']['tag_values'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Group Name'),
        $this->t('Label 1'),
        $this->t('Value 1'),
        $this->t('Label 2'),
        $this->t('Value 2'),
        $this->t('Operations'),
      ],
      '#empty' => $this->t('No tags available.'),
    ];

    // Fetching the array from the tag_values.
    $tag_values = $form_state->get('tag_values');
    foreach ($tag_values as $key => $tag) {

      // Row of the tables.
      $form['tags']['tag_values'][$key]['group_name'] = [
        '#type' => 'textfield',
        '#default_value' => $tag['group_name'] ?? '',
        '#maxlength' => 20,
      ];

      $form['tags']['tag_values'][$key]['label1'] = [
        '#type' => 'textfield',
        '#default_value' => $tag['label1'] ?? '',
        '#maxlength' => 20,
      ];

      $form['tags']['tag_values'][$key]['value1'] = [
        '#type' => 'textfield',
        '#default_value' => $tag['value1'] ?? '',
        '#maxlength' => 20,
      ];

      $form['tags']['tag_values'][$key]['label2'] = [
        '#type' => 'textfield',
        '#default_value' => $tag['label2'] ?? '',
        '#maxlength' => 20,
      ];

      $form['tags']['tag_values'][$key]['value2'] = [
        '#type' => 'textfield',
        '#default_value' => $tag['value2'] ?? '',
        '#maxlength' => 20,
      ];

      // Remove tag button.
      $form['tags']['tag_values'][$key]['operations'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#name' => 'remove_' . $key,
        '#submit' => ['::removeTag'],
        '#attributes' => [
          'data-tag-index' => $key,
        ],
        '#ajax' => [
          'callback' => '::updateTagCallback',
          'wrapper' => 'tagfields-wrapper',
        ],
      ];
    }

    // Add more button.
    $form['tags']['addtag'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add one more'),
      '#submit' => ['::addOneTag'],
      '#ajax' => [
        'callback' => '::updateTagCallback',
        'wrapper' => 'tagfields-wrapper',
      ],
    ];

    $form['#cache'] = [
      'tags' => [
        'hcl_events_config_form',
      ],
    ];

    $form['tags']['#prefix'] = '<div id="tagfields-wrapper">';
    $form['tags']['#suffix'] = '</div>';

    return parent::buildForm($form, $form_state);
  }

  /**
   * This function add a new tag in the form.
   *
   * @param array $form
   *   This array contains form information's.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   This tracks the form states.
   *
   * @return void
   *   This function set the new fields in the array and rebuild the form.
   */
  public function addOneTag(array &$form, FormStateInterface $form_state) {
    // Fetching the values from.
    $tag_values = $form_state->get('tag_values');
    $tag_values[] = [
      'group_name' => '',
      'label1' => '',
      'value1' => '',
      'label2' => '',
      'value2' => '',
    ];
    $form_state->set('tag_values', $tag_values);
    $form_state->setRebuild(TRUE);
  }

  /**
   * Remove a tag from the array and rebuilding the form.
   *
   * @param array $form
   *   This array contains form information's.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   This tracks the form states.
   *
   * @return void
   *   This function set the new fields in the array and rebuild the form.
   */
  public function removeTag(array &$form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    $tag_index = $triggering_element['#attributes']['data-tag-index'];

    $tag_values = $form_state->get('tag_values');
    if (isset($tag_values[$tag_index])) {
      unset($tag_values[$tag_index]);
      $form_state->set('tag_values', $tag_values);
      $form_state->setRebuild(TRUE);
    }
  }

  /**
   * This function is called when form is getting rebuilt.
   *
   * @param array $form
   *   This array contains form information's.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   This tracks the form states.
   *
   * @return array
   *   This function returns the updated forms.
   */
  public function updateTagCallback(array &$form, FormStateInterface $form_state) {
    return $form['tags'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('hcl_events_config_form.settings');
    $tag_values = $form_state->getValue('tag_values');

    // Storing the values in the config factory.
    $config->set('tag_values', $tag_values)->save();

    Cache::invalidateTags(['hcl_events_config_form']);

    parent::submitForm($form, $form_state);
  }

}
