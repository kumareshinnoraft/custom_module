<?php

namespace Drupal\event_dashboard\Services;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\event_dashboard\EventCountsServiceInterface;

/**
 * This service class will provide different statistics.
 */
class EventCountsService implements EventCountsServiceInterface {

  /**
   * Entity type manager will allow to query database.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Initialize the object entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getYearlyEventCounts() {
    $counts = [];
    $entityType = 'node';
    // Change 'node' to the correct entity type if needed.
    $query = $this->entityTypeManager->getStorage($entityType)->getQuery()
      ->condition('type', 'event')
      // ->groupBy('created_year')
      ->accessCheck(FALSE);

    $results = $query->execute();

    foreach ($results as $value) {
      $event = $this->getEventById($value);
      $date = $event->get('field_date')->value;
      $dateObject = new DrupalDateTime($date);
      $year = $dateObject->format('Y');

      // Increment the count for the year.
      if (!isset($counts[$year])) {
        $counts[$year] = 1;
      }
      else {
        $counts[$year]++;
      }
    }

    // Format the data as an array of arrays with year and count.
    $formattedCounts = [];
    foreach ($counts as $year => $count) {
      $formattedCounts[] = [$year, $count];
    }

    return $formattedCounts;
  }

  /**
   * This function will return the event from the ID.
   *
   * @param string $id
   *   This is the id of the event.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   This is the node object of the event.
   */
  protected function getEventById($id) {
    $event = NULL;

    if (!empty($id)) {
      $eventStorage = $this->entityTypeManager->getStorage('node');
      $event = $eventStorage->load($id);
      if ($event instanceof NodeInterface && $event->getType() !== 'event') {
        // If the loaded entity is not of type 'event', set the event to NULL.
        $event = NULL;
      }
    }

    return $event;
  }

  /**
   * {@inheritdoc}
   */
  public function getQuarterlyEventCounts() {
    $counts = [];
    // Change 'node' to the correct entity type if needed.
    $entityType = 'node';

    $query = $this->entityTypeManager->getStorage($entityType)->getQuery()
      ->condition('type', 'event')
      ->groupBy('created_year')
      ->groupBy('created_month')
      ->accessCheck(FALSE);

    $results = $query->execute();

    foreach ($results as $value) {
      $event = $this->getEventById($value);
      $date = $event->get('field_date')->value;
      $dateObject = new DrupalDateTime($date);
      $year = $dateObject->format('Y');
      $quarter = ceil($dateObject->format('n') / 3);

      // Increment the count for the quarter and year.
      if (!isset($counts[$year][$quarter])) {
        $counts[$year][$quarter] = 1;
      }
      else {
        $counts[$year][$quarter]++;
      }
    }

    // Format the data as an array of arrays with year, quarter, and count.
    $formattedCounts = [];
    foreach ($counts as $year => $quarters) {
      foreach ($quarters as $quarter => $count) {
        $formattedCounts[] = [$year, $quarter, $count];
      }
    }

    return $formattedCounts;
  }

  /**
   * {@inheritdoc}
   */
  public function getEventTypeCounts() {
    $counts = [];
    // Change 'node' to the correct entity type if needed.
    $entityType = 'node';

    $query = $this->entityTypeManager->getStorage($entityType)->getQuery()
      ->condition('type', 'event')
      ->groupBy('type')
      ->accessCheck(FALSE);

    $results = $query->execute();

    foreach ($results as $value) {
      $event = $this->getEventById($value);
      $event_type = $event->get('field_event_type')->value;
      $event_type = ucfirst(strtolower($event_type));

      // Increment the count for the year.
      if (!isset($counts[$event_type])) {
        $counts[$event_type] = 1;
      }
      else {
        $counts[$event_type]++;
      }
    }

    // Format the data as an array of arrays with year and count.
    $formattedCounts = [];
    foreach ($counts as $event_type => $count) {
      $formattedCounts[] = [$event_type, $count];
    }

    return $formattedCounts;

  }

}
