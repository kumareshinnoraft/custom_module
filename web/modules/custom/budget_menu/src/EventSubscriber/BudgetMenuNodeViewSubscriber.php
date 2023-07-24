<?php

namespace Drupal\budget_menu\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Budget Menu Node View Subscriber.
 */
class BudgetMenuNodeViewSubscriber implements EventSubscriberInterface {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * BudgetMenuNodeViewSubscriber constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, MessengerInterface $messenger, RouteMatchInterface $route_match) {
    $this->configFactory = $config_factory;
    $this->messenger = $messenger;
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[KernelEvents::VIEW][] = ['onViewRenderArray', 1];

    return $events;
  }

  /**
   * Adds the budget status message to the node view for movie nodes.
   */
  public function onViewRenderArray(ViewEvent $event) {
    $request = $event->getRequest();

    // Check if this is a node view and the node is of type "movie".
    if ($this->routeMatch->getRouteName() === 'entity.node.canonical' && $request->attributes->has('node')) {
      $node = $request->attributes->get('node');
      if ($node instanceof NodeInterface && $node->getType() === 'movie') {
        // Get the budget amount from the config form.
        $config = $this->configFactory->get('budget_menu.settings');
        $budget_amount = $config->get('budget_friendly_amount');

        // Get the budget amount from the node field.
        $node_budget_amount = $node->get('field_price')->value;

        if ($node_budget_amount && $budget_amount) {

          // Compare the budget amounts and set the corresponding status.
          if ($budget_amount > $node_budget_amount) {
            $status = 'The movie is under budget';
          }
          elseif ($budget_amount < $node_budget_amount) {
            $status = 'The movie is over budget';
          }
          else {
            $status = 'The movie is within budget';
          }
          $this->messenger->addMessage($status);
        }
      }
    }
  }

}
