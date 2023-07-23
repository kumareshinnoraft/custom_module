<?php

namespace Drupal\event_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\event_dashboard\EventCountsServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for event_dashboard routes.
 */
class EventDashboardController extends ControllerBase {

  /**
   * Event count service will have functions of statics data.
   *
   * @var \Drupal\event_dashboard\EventCountsServiceInterface
   */
  protected $eventCountsService;

  /**
   * Initialize the object of event count service.
   */
  public function __construct(EventCountsServiceInterface $eventCountsService) {
    $this->eventCountsService = $eventCountsService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('event_dashboard.event_counts_service')
    );
  }

  /**
   * Builds the response.
   */
  public function build() {
    // Count events yearly.
    $yearly_counts = $this->eventCountsService->getYearlyEventCounts();

    // Count events per quarter.
    $quarterly_counts = $this->eventCountsService->getQuarterlyEventCounts();

    // Count events of each type.
    $type_counts = $this->eventCountsService->getEventTypeCounts();

    return [
      '#theme' => 'statistics',
      '#yearly_counts' => $yearly_counts,
      '#quarterly_counts' => $quarterly_counts,
      '#type_counts' => $type_counts,
      '#attached' => [
        'library' => [
          'event_dashboard/event_dashboard_styles',
        ],
      ],
      '#cache' => [
        'tags' => ['node_list'],
      ],
    ];
  }

}
