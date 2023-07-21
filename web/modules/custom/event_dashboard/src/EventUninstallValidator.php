<?php

namespace Drupal\event_dashboard;

use Drupal\Core\Extension\ModuleUninstallValidatorInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Prevents blog module from being uninstalled whilst any blog nodes exist.
 */
class EventUninstallValidator implements ModuleUninstallValidatorInterface {

  use StringTranslationTrait;

  /**
   * Constructs a new validator.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   */
  public function __construct(TranslationInterface $string_translation) {
    $this->setStringTranslation($string_translation);
  }

  /**
   * {@inheritdoc}
   */
  public function validate($module) {
    $reasons = [];
    if ($module === 'event_dashboard' && event_dashboard_counter() != 0) {
      $reasons[] = $this->t('To uninstall event module, first delete all event posts content.');
    }
    return $reasons;
  }

}
