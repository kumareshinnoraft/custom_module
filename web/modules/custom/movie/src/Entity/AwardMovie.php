<?php

namespace Drupal\movie\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\movie\AwardMovieInterface;

/**
 * Defines the award_movie entity type.
 *
 * @ConfigEntityType(
 *   id = "award_movie",
 *   label = @Translation("AwardMovie"),
 *   label_collection = @Translation("AwardMovies"),
 *   label_singular = @Translation("award_movie"),
 *   label_plural = @Translation("award_movies"),
 *   label_count = @PluralTranslation(
 *     singular = "@count award_movie",
 *     plural = "@count award_movies",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\movie\AwardMovieListBuilder",
 *     "form" = {
 *       "add" = "Drupal\movie\Form\AwardMovieForm",
 *       "edit" = "Drupal\movie\Form\AwardMovieForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "award_movie",
 *   admin_permission = "administer award_movie",
 *   links = {
 *     "collection" = "/admin/structure/award_movie",
 *     "add-form" = "/admin/structure/award_movie/add",
 *     "edit-form" = "/admin/structure/award_movie/{award_movie}",
 *     "delete-form" = "/admin/structure/award_movie/{award_movie}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "movie_name" = "movie_name",
 *     "year" = "year",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "movie_name",
 *     "year"
 *   }
 * )
 */
class AwardMovie extends ConfigEntityBase implements AwardMovieInterface {

  /**
   * The award_movie ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The award_movie label.
   *
   * @var string
   */
  protected $label;

  /**
   * The award_movie movie_name.
   *
   * @var object
   */
  protected $movieName;

  /**
   * The award_movie year.
   *
   * @var \DateTime
   */
  protected $year;

}
