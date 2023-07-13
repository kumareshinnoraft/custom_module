<?php

namespace Drupal\config_form\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This form is for generating the one time login link for the user.
 *
 * @package Drupal\config_form\Form
 */
class GenerateLoginLink extends FormBase {

  /**
   * This object is the storage of the user entity.
   *
   * @var object
   */
  private $userData;

  /**
   * Constructor accepting the service of Entity type manager.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   Entity manager is used to get the user entity.
   */
  public function __construct(EntityTypeManager $entity_type_manager) {
    $this->userData = $entity_type_manager->getStorage('user');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'generate_login_link';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user_id'] = [
      '#prefix' => '<div class="row"><div class="col-12">',
      '#type'   => 'number',
      '#title'  => $this->t('Please Enter Your User Id'),
      '#ajax'   => [
        'callback' => '::findUser',
        'effect'   => 'fade',
        'event'    => 'keyup',
      ],
      '#suffix' => '<span id="user-id" class="error"></span></div>',
    ];

    return $form;
  }

  /**
   * Finding user from user entered value.
   *
   * @param array $form
   *   This array contains the for information.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state interface contains the different form state.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   This ajax response is used to update the user.
   */
  public function findUser(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    // Getting the value from the user.
    $val = Html::escape($form_state->getValue('user_id'));

    // Loading the user from the user ID.
    $account = $this->userData->load($val);

    // If the user is NULL return an error message.
    if ($account === NULL) {
      $response->addCommand(new CssCommand('#user-id', ['color' => 'red']));
      $response->addCommand(new HtmlCommand('#user-id', 'User id is not correct'));

      // Returning the error message.
      return $response;
    }
    $response->addCommand(new CssCommand('#user-id', ['color' => 'green']));
    if ($account instanceof User) {
      $response->addCommand(new HtmlCommand('#user-id', $this->generateLoginLink($account)));
    }
    // Returning the URL link.
    return $response;
  }

  /**
   * Generates a one-time login link for the given user.
   *
   * @param \Drupal\user\Entity\User $user
   *   The user for which to generate the login link.
   *
   * @return string
   *   Generates the link of the user link.
   */
  public function generateLoginLink(User $user) {
    return user_pass_reset_url($user) . '/login';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // This submit function is inheriting the Form Base.
  }

}
