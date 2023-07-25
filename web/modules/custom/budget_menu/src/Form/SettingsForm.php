<?php

namespace Drupal\budget_menu\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure budget_menu settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'budget_menu_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['budget_menu.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['budget_friendly_amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Budget Friendly amount'),
      '#default_value' => $this->config('budget_menu.settings')->get('budget_friendly_amount'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('budget_friendly_amount') < 100) {
      $form_state->setErrorByName('budget_friendly_amount', $this->t('The budget must be gretter than 100.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    Cache::invalidateTags(['node_view']);
    $this->config('budget_menu.settings')
      ->set('budget_friendly_amount', $form_state->getValue('budget_friendly_amount'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
