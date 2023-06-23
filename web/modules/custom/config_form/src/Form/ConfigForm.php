<?php

namespace Drupal\config_form\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Mail\MailManagerInterface;
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
      '#type' => 'textfield',
      '#title' => $this->t('Full Name'),
      '#required' => true,
    ];

    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone Number'),
      '#required' => true,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => true,
    ];

    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => [
        'male' => $this->t('Male'),
        'female' => $this->t('Female'),
        'others' => $this->t('Other'),
      ],
      '#required' => TRUE,
    ];

    $form['action']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::ajaxSubmitCallback',
        'wrapper' => 'ajax-wrapper',
        'effect' => 'fade',
      ],
    ];

    return $form;
  }

  /**
   * This function validate the form input data and checks the conditions.
   *
   * @param array
   *   Form is the array with a reference.
   * @param FormStateInterface
   *   Holds the values of the input data.
   *
   * @return void
   *   This function is validating the input data.
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $phone_number = $form_state->getValue('phone_number');
    $email = $form_state->getValue('email');

    // TODO: There are lot of public domains are available for now four
    // are checked later if require code will be modified.
    $public_domains = ['yahoo.com', 'gmail.com', 'outlook.com', '126', 'innoraft.com'];
    $email_domain = substr(strrchr($email, "@"), 1);

    if (!preg_match('/^[0-9]{10}$/', $phone_number)) {
      $form_state->setErrorByName('phone_number', $this->t('Invalid phone number. Please enter a 10-digit Indian number.'));
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Invalid email format'));
    } elseif (!in_array($email_domain, $public_domains)) {
      $form_state->setErrorByName('email', $this->t('Only public email domains like Yahoo, Gmail, and Outlook are allowed.'));
    } elseif (substr($email, -strlen('.com')) !== '.com') {
      $form_state->setErrorByName('email', $this->t('Email does not ends with .com'));
    }
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
    $params['subject'] = 'My Subject';
    $params['body'] = 'Hello, this is the email body.';
    $email = (string) $form_state->getValue('email');

    $result = $this->mailManager->mail(ConfigForm::MODULE_NAME, ConfigForm::MAIL_KEY, $email, \Drupal::currentUser()->getPreferredLangcode(), $params, null, true);

    if ($result['result'] !== TRUE) {
      \Drupal::messenger()->addError($this->t('Failed to send email.'));
    } else {
      // Setting the email value in the configuration.
      $config = $this->config('config_form.settings');
      $config->set('email', $form_state->getValue('email'));
      $config->save();
    }
  }

  /**
   * Ajax callback is used to get call after submitForm execution.
   *
   * @param array $form
   *  Form that contains all data inserted into the form.
   * @param  mixed $form_state
   *  Form state handles the different state of the form form.
   * @return object
   *  Returning the response to the kernel for output.
   */
  public function ajaxSubmitCallback(array &$form, FormStateInterface $form_state)
  {
    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#form-wrapper', $this->t('Form submitted successfully.')));

    // Fetching the data from the configuration.
    \Drupal::messenger()->addMessage($this->t('Email sent successfully. And the mail from the configuration data is ' . \Drupal::config('config_form.settings')->get('email')));

    return $response;
  }
}