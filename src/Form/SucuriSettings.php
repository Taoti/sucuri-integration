<?php

declare(strict_types = 1);

namespace Drupal\sucuri_integration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure social media links.
 */
class SucuriSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sucuri_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('sucuri_integration.sucurisettings');

    $form['api'] = [
      '#type' => 'fieldset',
      '#title' => t('API Keys'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];

    $form['api']['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#maxlength' => 128,
      '#size' => 64,
      '#default_value' => $config->get('api_key'),
    ];

    $form['api']['api_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Secret'),
      '#maxlength' => 128,
      '#size' => 64,
      '#default_value' => $config->get('api_secret'),
    ];

    $form['links'] = [
      '#type' => 'fieldset',
      '#title' => t('Quick Links'),
    ];

    $form['links']['clear_cache'] = [
      '#type'   => 'markup',
      '#markup' => '<div class="link"><a href="https://waf.sucuri.net/api?k='. $config->get('api_key') . '&s='. $config->get('api_secret') .'&a=clearcache">Clear Cache</a></div>',
    ];

    $form['links']['allow_ip'] = [
      '#type'   => 'markup',
      '#markup' => '<div class="link"><a href="https://waf.sucuri.net/api?k='. $config->get('api_key') . '&s='. $config->get('api_secret') .'&a=allowlist">Allow IP</a></div>',
    ];

    $form['links']['audit_trails'] = [
      '#type'   => 'markup',
      '#markup' => '<div class="link"><a href="https://waf.sucuri.net/api?k='. $config->get('api_key') . '&s='. $config->get('api_secret') .'&a=auditshow">Audit Trails</a></div>',
    ];

    $form['links']['add_ip'] = [
      '#type'   => 'markup',
      '#markup' => '<div class="link"><a href="https://waf.sucuri.net/api?k='. $config->get('api_key') . '&s='. $config->get('api_secret') .'&a=devmode_ip">Add IP to Dev. Mode</a></div>',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('sucuri_integration.sucurisettings')
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('api_secret', $form_state->getValue('api_secret'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'sucuri_integration.sucurisettings',
    ];
  }

}
