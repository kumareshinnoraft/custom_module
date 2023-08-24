<?php

namespace Drupal\practice_test\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Returns responses for practice_test routes.
 */
class PracticeTestController extends ControllerBase {

  /**
   * This is entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * This is a constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   This will be used to fetch the nodes.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Builds the response.
   */
  public function build(Request $request) {

    $headers = $request->headers->get('api-key');
    if ($headers === 'abc') {

      $og_chart = $this->entityTypeManager()->getStorage('node')->getQuery()
        ->condition('type', 'org_chart')
        ->accessCheck(FALSE);
      $nodes = $og_chart->execute();
      $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple($nodes);

      // Construct the data you want to return in the API response.
      $data = [
        'title' => NULL,
        'data' => [],
      ];

      // Populate 'data' with information from each node.
      foreach ($nodes as $node) {
        $image_field = $node->get('field_oc_image');
        $image_entity = $image_field->entity;
        $attachment_field = $node->get('field_attachment');
        $attachment_entity = $attachment_field->entity;

        // Modify the following lines to extract the actual values from your nodes.
        $data['data'][] = [
          'oc_uuid' => $node->uuid(),
          'oc_title' => $node->getTitle(),
          'oc_image' => [
            'file_uuid' => $image_entity->uuid(),
            'file_name' => $image_entity->get('filename')->value,
            'file_url' => $image_entity->get('uri')->value,
            'file_display_name' => '',
            'file_mime' => $image_entity->get('filemime')->value,
            'file_size' => $image_entity->get('filesize')->value,
            'file_path' => $image_entity->getFileUri(),
          ],
          'attachment' => [
            'file_uuid' => $attachment_entity->uuid(),
            'file_name' => $attachment_entity->get('filename')->value,
            'file_url' => $attachment_entity->get('uri')->value,
            'file_display_name' => $attachment_entity->getFilename(),
            'file_mime' => $attachment_entity->get('filemime')->value,
            'file_size' => $attachment_entity->get('filesize')->value,
            'file_path' => $attachment_entity->getFileUri(),
          ],
        ];
      }

      // Return the data as a JSON response.
      return new JsonResponse($data);

    }
    else {
      throw new AccessDeniedHttpException();
    }

  }

}
