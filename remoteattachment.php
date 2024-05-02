<?php

require_once 'remoteattachment.civix.php';
// phpcs:disable
use CRM_RemoteAttachment_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function remoteattachment_civicrm_config(&$config): void {
  _remoteattachment_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function remoteattachment_civicrm_install(): void {
  _remoteattachment_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function remoteattachment_civicrm_enable(): void {
  _remoteattachment_civix_civicrm_enable();
}
