<?php

namespace Drupal\config_form\Form;

use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Config Form is for the administrator.
 *
 * @package Drupal\config_form\Form
 */
class ConfigForm extends ConfigFormBase
{
  /**
   * This is the name of module and will be used from anywhere in the project
   * by calling them directly through the class name.
   */
  public const MODULE_NAME = 'config_form';

  /**
   * This is the key of mail this class is sending which is checked in the hook
   * for further processing.
   */
  public const MAIL_KEY = 'config_form_mail';
  /**
   * Mail Manager is used for sending mail in a secure way.
   *
   * @var MailManagerInterface
   */
  private $mailManager;

  /**
   * Constructor accepting the service of mail manager.
   *
   * @param object $mail_manager_interface
   *   Mail Manager Interface provides ability to send email.
   */
  public function __construct(MailManagerInterface $mail_manager_interface)
  {
    $this->mailManager = $mail_manager_interface;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static($container->get('plugin.manager.mail'));
  }

  /**
   * Creating a unique form id for the config form.
   *
   * @return string
   *   Unique form id.
   */
  public function getFormId()
  {
    return 'config_form_custom';
  }

  /**
   * This function is used to tell drupal what type of form it is.
   *
   * @return array
   *   This array contains the config form.
   */
  protected function getEditableConfigNames()
  {
    return ['config_form.settings'];
  }

  /**
   * Building form for the required fields.
   *
   * @param array $form
   *   This is the array which will contains fields with associative array.
   * @param FormStateInterface $form_state
   *   This variable uses the shows the different form states.
   *
   * @return array
   *   Form contains the array which having all fields.
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['full_name'] = [
      '#prefix' => '<div class="row"><div class="col-12 mb-4">',
      '#type' => 'textfield',
      '#title' => $this->t('Full Name'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::ajaxSubmit',
        'event' => 'submit',
      ],
      '#suffix' => '<span id="full-name-error" class="error"></span></div></div>'
    ];

    $form['phone_number'] = [
      '#prefix' => '<div class="row"><div class="col-12 mb-4">',
      '#type' => 'tel',
      '#title' => $this->t('Phone Number'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::ajaxSubmit',
        'event' => 'submit',
      ],
      '#suffix' => '<span id="phone-number-error" class="error"></span></div></div>'
    ];

    $form['email'] = [
      '#prefix' => '<div class="row"><div class="col-12 mb-4">',
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::ajaxSubmit',
        'event' => 'submit',
      ],
      '#suffix' => '<span id="email-error" class="error"></span></div></div>'
    ];

    $form['gender'] = [
      '#prefix' => '<div class="row"><div class="col-12 mb-4">',
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => [
        'male' => $this->t('Male'),
        'female' => $this->t('Female'),
        'others' => $this->t('Other'),
      ],
      '#ajax' => [
        'callback' => '::ajaxSubmit',
        'event' => 'submit',
      ],
      '#required' => TRUE,
      '#suffix' => '<span id="gender-error" class="error"></span></div></div>'
    ];

    $form['action']['submit'] = [
      '#prefix' => '<div class="text-center"><p>',
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::ajaxSubmit',
      ],
      '#suffix' => '<span class="contact-form-result-message"></span></div>'
    ];

    return $form;
  }

  /**
   * This function is called when ajax submission is required.
   *
   * @param array $form
   *   Form array containing the of the form elements.
   * @param  mixed $form_state
   *   Form state holds the values of input data.
   * 
   * @return Response
   *   This response is the ajax response data.
   */
  public function ajaxSubmit(array &$form, FormStateInterface $form_state)
  {
    // Initiate response.
    $response = new AjaxResponse();
    $result = $this->validate($response, $form_state);
    $triggering_element = $form_state->getTriggeringElement();


    if ($result === TRUE && $triggering_element['#type'] === 'submit') {

      // Make email green, for if it was red before.
      $response->addCommand(new CssCommand('#edit-email', ['border' => '1px solid #ced4da']));
      // Make message green, for if it was red.
      $response->addCommand(new CssCommand('.contact-form-result-message', ['color' => 'green']));
      // Empty all fields, flood control.
      $response->addCommand(new InvokeCommand('#edit-full-name', 'val', ['']));
      $response->addCommand(new InvokeCommand('#edit-last-name', 'val', ['']));
      $response->addCommand(new InvokeCommand('#edit-email', 'val', ['']));
      $response->addCommand(new InvokeCommand('#edit-phone-number', 'val', ['']));

      // Success message.
      $message = $this->t('Thanks! For Submitting The Form.');
      $response->addCommand(new HtmlCommand('.contact-form-result-message', $message));
    } 

    // Deleting all messenger messages to avoid confusions.
    \Drupal::messenger()->deleteAll();

    return $response;
  }

  /**
   * This function validates the form for ajax and normal calls.
   *
   * @param FormStateInterface $form_state
   *   This variable holds the form state.
   * @param AjaxResponse $response
   *   This ajax response is for altering the HTML fields in the form.
   * 
   * @return mixed
   *   On successful validation this function returns TRUE, otherwise array.
   */
  public function validate(AjaxResponse $response, FormStateInterface $form_state)
  {
    $phone_number = $form_state->getValue('phone_number');
    $email = $form_state->getValue('email');

    $flag = TRUE;

    // Listing public domains
    $public_domains = ['yahoo.com', 'gmail.com', 'outlook.com', '126', 'innoraft.com'];
    $email_domain = substr(strrchr($email, "@"), 1);

    if (empty($form_state->getValue('full_name'))) {
      $flag = FALSE;
      $response->addCommand(new CssCommand('#full-name-error', ['color' => 'red']));
      $response->addCommand(new HtmlCommand('#full-name-error', $this->t('Please enter full name.')));
    } 
    else {
      $response->addCommand(new HtmlCommand('#full-name-error', ''));
    }
    
    if (!preg_match('/^[0-9]{10}$/', $phone_number)) {
      $flag = FALSE;
      $response->addCommand(new CssCommand('#phone-number-error', ['color' => 'red']));
      $response->addCommand(new HtmlCommand('#phone-number-error', $this->t('Invalid phone number. Please enter a 10-digit Indian number.')));
    }
    else {
      $response->addCommand(new HtmlCommand('#phone-number-error', ''));
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $flag = FALSE;
      $response->addCommand(new CssCommand('#email-error', ['color' => 'red']));
      $response->addCommand(new HtmlCommand('#email-error', $this->t('Invalid email format.')));
    } 
    elseif (!in_array($email_domain, $public_domains)) {
      $flag = FALSE;
      $response->addCommand(new CssCommand('#email-error', ['color' => 'red']));
      $response->addCommand(new HtmlCommand('#email-error', $this->t('Only public email domains like Yahoo, Gmail, and Outlook are allowed.')));
    } 
    elseif (substr($email, -strlen('.com')) !== '.com') {
      $flag = FALSE;
      $response->addCommand(new CssCommand('#email-error', ['color' => 'red']));
      $response->addCommand(new HtmlCommand('#email-error', $this->t('Email does not ends with .com')));
    } 
    else {
      $response->addCommand(new HtmlCommand('#email-error', ''));
    }
    
    if (empty($form_state->getValue('gender'))) {
      $flag = FALSE;
      $response->addCommand(new CssCommand('#gender-error', ['color' => 'red']));
      $response->addCommand(new HtmlCommand('#gender-error', $this->t('Gender should not be empty.')));

      return [$this->t('Gender should not be empty'), '#gender-error'];
    }
    else {
      $response->addCommand(new HtmlCommand('#gender-error', ''));
    }

    return $flag;
  }

  /**
   * This form simply sends the message to the user for successful validation
   * checks.
   *
   * @param array $form
   *   This is the referenced array of form.
   * @param mixed $form_state
   *   Form state holds the values of input data.
   *
   * @return void
   *   This function returns the message using messenger.
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // As ajax is used in this form, submit form left as empty.
  }
}