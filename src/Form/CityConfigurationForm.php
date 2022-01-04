<?php

namespace Drupal\time_block\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 */
class CityConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'time_block.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('time_block.settings');
    $form['country'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Country'),
      '#default_value' => $config->get('country'),
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('City'),
      '#default_value' => $config->get('city'),
    ];
    $form['timezone'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#title' => $this->t('Time Zone'),
      '#default_value' => $config->get('timezone'),
      '#options' => [
        'America/Chicago' => 'America/Chicago',
        'America/New_York' => 'America/New_York',
        'Asia/Tokyo' => 'Asia/Tokyo',
        'Asia/Dubai' => 'Asia/Dubai',
        'Asia/Kolkata' => 'Asia/Kolkata',
        'Europe/Amsterdam' => 'Europe/Amsterdam',
        'Europe/Oslo' => 'Europe/Oslo',
        'Europe/London' => 'Europe/London',
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $inputs = $form_state->getUserInput();
    foreach (['country', 'city', 'timezone'] as $values) {
      $this->config('time_block.settings')
        ->set($values, $inputs[$values]);
    }
    $this->config('time_block.settings')->save();
  }

}
