<?php


/**
 * @file: Formatter settings
 * @todo: Comentar todas las líneas de ejecución
 */


/**
 * Implementation of hook_field_formatter_info().
 */
function nuclear_field_formatter_info() {
  n();


  $info = array();

  $info['email'] = array(
    'label' => t('With email (here)'),
    'field types' => array('nuclear_widget'), // Importante, nombre del widget!!
    'multiple values' => CONTENT_HANDLE_CORE,
  );

  $info['name'] = array(
    'label' => t('Without email'),
    'field types' => array('nuclear_widget'), // Importante, nombre del widget!!
    'multiple values' => CONTENT_HANDLE_CORE,
  );

  return $info;
}


/**
 * Implementation of hook_formatter_settings().
 */
function nuclear_formatter_settings($form_state, $field, $options, $views_form) {
  n();

}


