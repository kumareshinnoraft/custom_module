<?php

namespace Drupal\movie;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of award movies.
 */
class AwardMovieListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['movie_name'] = $this->t('Movie name');
    $header['year'] = $this->t('Year');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $id = $entity->get('movie_name')[0]['target_id'];
    $item = \Drupal::entityTypeManager()->getStorage('node')->load($id);
    $row['movie_name'] = $item->label() ?? '';
    $row['year'] = $entity->get('year');
    return $row + parent::buildRow($entity);
  }

}
