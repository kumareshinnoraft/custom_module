<?php

namespace Drupal\movie\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\movie\MovieInterface;

/**
 * Defines the movie entity type.
 *
 * @ConfigEntityType(
 *   id = "movie",
 *   label = @Translation("Movie"),
 *   label_collection = @Translation("Movies"),
 *   label_singular = @Translation("movie"),
 *   label_plural = @Translation("movies"),
 *   label_count = @PluralTranslation(
 *     singular = "@count movie",
 *     plural = "@count movies",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\movie\MovieListBuilder",
 *     "form" = {
 *       "add" = "Drupal\movie\Form\MovieForm",
 *       "edit" = "Drupal\movie\Form\MovieForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "movie",
 *   admin_permission = "administer movie",
 *   links = {
 *     "collection" = "/admin/structure/movie",
 *     "add-form" = "/admin/structure/movie/add",
 *     "edit-form" = "/admin/structure/movie/{movie}",
 *     "delete-form" = "/admin/structure/movie/{movie}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description"
 *   }
 * )
 */
class Movie extends ConfigEntityBase implements MovieInterface {

  /**
   * The movie ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The movie label.
   *
   * @var string
   */
  protected $label;

  /**
   * The movie status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The movie description.
   *
   * @var string
   */
  protected $description;

}
