<?php

namespace Drupal\event_dashboard;

/**
 * This interface will have all entity statistics data.
 */
interface EventCountsServiceInterface {

  /**
   * Returns the yearly event counts.
   *
   * @return array
   *   An array of yearly event counts.
   */
  public function getYearlyEventCounts();

  /**
   * Returns the quarterly event counts.
   *
   * @return array
   *   An array of quarterly event counts.
   */
  public function getQuarterlyEventCounts();

  /**
   * Returns the event type counts.
   *
   * @return array
   *   An array of event type counts.
   */
  public function getEventTypeCounts();

}
