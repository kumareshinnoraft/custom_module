<?php

namespace Drupal\event_dashboard\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an AJAX form to enter a taxonomy term name.
 */
class TermInfoAjaxForm extends FormBase {

  /**
   * Renderer global object.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;
  /**
   * Entity manager interface global object.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * TermInfoAjaxForm constructor.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   */
  public function __construct(RendererInterface $renderer, EntityTypeManagerInterface $entityTypeManager) {
    $this->renderer = $renderer;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('renderer'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'term_info_ajax_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['term_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter the name of the taxonomy term'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::loadTermInfo',
        'event' => 'change',
        'wrapper' => 'term-info-wrapper',
        'method' => 'replace',
      ],
    ];

    $form['term_id'] = [
      '#type' => 'markup',
      '#prefix' => '<div id="term-id-wrapper">',
      '#suffix' => '</div>',
    ];

    $form['term_uuid'] = [
      '#type' => 'markup',
      '#prefix' => '<div id="term-uuid-wrapper">',
      '#suffix' => '</div>',
    ];

    $form['term_nodes'] = [
      '#type' => 'markup',
      '#prefix' => '<div id="term-nodes-wrapper">',
      '#suffix' => '</div>',
    ];

    return $form;
  }

  /**
   * AJAX callback to load term information.
   */
  public function loadTermInfo(array &$form, FormStateInterface $form_state) {
    $term_name = $form_state->getValue('term_name');

    // Get the term information.
    $term_info = $this->getTermInformation($term_name);

    $response = new AjaxResponse();
    if ($term_info) {

      $nodes_markup = '<ul>';
      foreach ($term_info['nodes'] as $node) {
        $nodes_markup .= '<li><a href="' . $node['url'] . '">' . $node['title'] . '</a></li>';
      }
      $nodes_markup .= '</ul>';

      // Update the term-info-wrapper with the term information.
      $response->addCommand(new HtmlCommand('#term-id-wrapper', $term_info['id']));
      $response->addCommand(new HtmlCommand('#term-uuid-wrapper', $term_info['uuid']));
      $response->addCommand(new HtmlCommand('#term-nodes-wrapper', $nodes_markup));
    }
    else {
      // If the term is not found, display an error message.
      $response->addCommand(new ReplaceCommand('#term-info-wrapper', '<p class="term-info-error">' . $this->t('Term not found.') . '</p>'));
    }

    return $response;
  }

  /**
   * Helper function to get term information.
   */
  protected function getTermInformation($term_name) {
    // Find the term by name.
    $term = $this->entityTypeManager
      ->getStorage('taxonomy_term')
      ->loadByProperties(['name' => $term_name]);

    // If the term is found, get its ID, UUID, and referenced nodes.
    if (!empty($term)) {
      $term = reset($term);
      $term_id = $term->id();
      $term_uuid = $term->uuid();

      $nodes = $this->entityTypeManager
        ->getStorage('node')
        ->loadByProperties(['field_tags' => $term_id]);

      // Collect node titles and URLs using the term.
      $node_info = [];
      foreach ($nodes as $node) {
        $node_info[] = [
          'title' => $node->getTitle(),
          'url' => $node->toUrl()->toString(),
        ];
      }
      // Return the term information.
      return [
        'id' => $term_id,
        'uuid' => $term_uuid,
        'nodes' => $node_info,
      ];
    }

    // If the term is not found, return NULL.
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // No need to implement submit handler for this AJAX form.
  }

}
