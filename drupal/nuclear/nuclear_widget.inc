<?php


/**
 * @file: Widget settings
 * @todo: Comentar todas las líneas de ejecución
 */


/**
 * Implementation of hook_widget_info().
 */
function nuclear_widget_info() {
  n();


  $info = array();

  $info['nuclear'] = array(
    'label' => t('Name and E-mail'),
    'field types' => array('nuclear'),
    'multiple values' => CONTENT_HANDLE_CORE,
/*    'callbacks' => array(
      'default value' => CONTENT_CALLBACK_DEFAULT,
    ),*/
  );

  return $info;
}


/**
 * Implementation of hook_widget_settings().
 */
function nuclear_widget_settings($op, $widget) {
  n();

  // Definimos todos los campos aqui, para ahorrar codigo
  $nuclear_fields = array('name', 'email');

  switch ($op) {

    case 'form':

      $form = array();

      if ($widget['type'] == 'nuclear') {

        $form = array();

        // Recorremos todos los campos para generar su input a nivel de widget
        foreach ($nuclear_fields as $id) {

          // Tomamos los parámetros recibidos para usarlos si procede
          // Si el campo es obligatorio...
          $default    = (bool) constant('NUCLEAR_IS_REQUIRED_' . strtoupper($id));
          $required   = (bool) $widget['widget_required_' . $id];
          $required   = ($required) ? $required : $default;

          // Su máxima longitud...
          $default    = (int) constant('NUCLEAR_MAX_LENGTH_' . strtoupper($id));
          $max_length = (int) $widget['widget_max_length_' . $id];
          $max_length = (is_numeric($max_length)) ? $max_length : NULL;
          $max_length = ($max_length != 0) ? $max_length : NULL;
          $default    = ($max_length) ? $max_length : $default;


          // Para los títulos y descripciones
          $replaces = array('!this' => $id);

          // Formamos el formulario para los campos de configuración
          $form['widget_max_length_' . $id] = array(
            '#type' => 'textfield',
            '#title' => t('Max length of !this', $replaces),
            '#default_value' => $default,
            '#required' => FALSE,
            '#element_validate' => array('_element_validate_integer_positive'),
            '#description' => t(
              'Set the max length for !this, must be positive integer',
              $replaces
            ),
          );

          // Campos de obligatoriedad
          $form['widget_required_' . $id] = array(
            '#type' => 'checkbox',
            '#title' => t('!this is required.', $replaces),
            '#default_value' => $required,
            '#required' => $required,
            '#description' => t(
              'Check this if you want that the !this field to be required',
              $replaces
            ),
          );
        }
      }

      return $form;

    case 'validate':
      return;

    // La lista de campos que vamos a salvar a nivel de widget
    case 'save':

      $save = array();

      // Generamos un array con todos los campos
      foreach ($nuclear_fields as $id) {
        $save[] = 'widget_max_length_' . $id;
        $save[] = 'widget_required_' . $id;
      }

      return $save;

  }
}


/**
 * Implementation of hook_widget().
 */
function nuclear_widget(&$form, &$form_state, $field, $items, $delta = 0) {
  n();


  $widget = array(
    '#type' => $field['widget']['type'],
    '#default_value' => isset($items[$delta]) ? $items[$delta] : '',
    '#title' => 'NUCLEAR: ' . __LINE__ . ' ' . $field['widget']['label'],
    '#weight' => $field['widget']['weight'],
    '#description' => $field['widget']['description'],
    '#required' => $field['required'],
    '#field' => $field,
  );

  return $widget;
}


