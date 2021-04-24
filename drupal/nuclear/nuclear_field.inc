<?php


/**
 * @file: Field settings
 * @todo: Comentar todas las líneas de ejecución
 */


/**
 * Implementation of hook_field_info().
 */
function nuclear_field_info() {
  n();


  $info = array();

  // Definimos el nombre del nuevo tipo de campo (nuclear)
  $info['nuclear'] = array(
    'label' => t('Name and E-mail'),
    'description' => t('Store Names and E-mail in database.'),
  );

  return $info;
}


/**
 * Implementation of hook_field_settings().
 */
function nuclear_field_settings($op, $field) {
  n();


  // Definimos todos los campos aqui, para ahorrar codigo
  $nuclear_fields = array('name', 'email');

  switch ($op) {

    // Construcción del formulario de configuración global del campo
    case 'form':

      $form = array();

      // Recorremos todos los campos para generar su input a nivel de field
      foreach ($nuclear_fields as $id) {

        // Tomamos los parámetros recibidos para usarlos si procede
        // Si el campo es obligatorio...
        $default    = (bool) constant('NUCLEAR_IS_REQUIRED_' . strtoupper($id));
        $required   = (bool) $field['widget_required_' . $id];
        $required   = ($required) ? $required : $default;

        // Su máxima longitud...
        $default    = (int) constant('NUCLEAR_MAX_LENGTH_' . strtoupper($id));
        $max_length = (int) $field['widget_max_length_' . $id];
        $max_length = (is_numeric($max_length)) ? $max_length : NULL;
        $max_length = ($max_length != 0) ? $max_length : NULL;
        $default    = ($max_length) ? $max_length : $default;

        // Para los títulos y descripciones
        $replaces = array('!this' => $id);

        // Formamos el formulario para los campos de configuración
        $form['field_max_length_' . $id] = array(
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
        $form['field_required_' . $id] = array(
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

      return $form;

    // No tenemos nada que validar, si no viene dato no ponemos límite de texto
    // y si no viene ningún check pues es... que no esta marcado obviamente ;-)
    case 'validate':
      return;

    // La lista de campos que vamos a salvar a nivel de field
    case 'save':

      $save = array();

      // Generamos un array con todos los campos
      foreach ($nuclear_fields as $id) {
        $save[] = 'field_max_length_' . $id;
        $save[] = 'field_required_' . $id;
      }

      return $save;

    // Definimos la estructura de los campos en base de datos, generado al vuelo
    case 'database columns':

      $columns = array();

      // Recorremos todos los campos para generar sus campos en base de datos
      foreach ($nuclear_fields as $id) {

        // Es importantísimo que tenga un nombre distinto al widget ya que en
        // caso contrario tomaría los valores del widget sobreescribiendolos
        $length = $field['field_max_length_' . $id];

        // Segun su configuración definimos un tipo de dato u otro
        if (empty($length) OR (int) $length > 255) {
          $columns[$id . '_value'] = array(
            'type' => 'text',
            'size' => 'big',
            'not null' => FALSE,
            'sortable' => TRUE,
            'views' => TRUE,
          );
        }
        else {
          $columns[$id . '_value'] = array(
            'type' => 'varchar',
            'length' => $length,
            'not null' => FALSE,
            'sortable' => TRUE,
            'views' => TRUE,
          );
        }

        // Un boolean para los campos requeridos (TINYINT(1) realmente en MySQL)
        $columns[$id . '_required'] = array(
          'type' => 'char',
          'length' => 1,
          'not null' => TRUE,
          'default' => 0,
          'sortable' => TRUE,
          'views' => TRUE,
        );

      }

      return $columns;

    case 'filters':
      return;

    case 'views data':
      return;

  }
}


/**
 * Implementation of hook_field().
 */
function nuclear_field($op, &$node, $field, &$items, $teaser, $page) {
  n();


  switch ($op) {

    case 'load':
      return;

    case 'validate':
      return;

    case 'presave':
      return;

    case 'insert':
      return;

    case 'update':
      return;

    case 'delete':
      return;

    case 'delete revision':
      return;

    case 'sanitize':

      foreach ($items as $delta => $item) {
        $items[$delta]['safe'] = check_plain($item['email']);
      }
      break;

    case 'preprare translation':
      return;

  }
}
