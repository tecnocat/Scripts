<?php /* UTF-8 Verified (áéíóú) */

// $Id$

if ($_GET['run'] == 'corre') {
  $competencias = array(
    'Desarrollo de aplicaciones con HTML y CSS',
    'Desarrollo de aplicaciones con XSTL',
    'Desarrollo de aplicaciones con Flash Action Script',
    'Desarrollo de aplicaciones con Web Services',
  );
  $grupos = array(
    'Plataformas web' => $competencias,
    'Plataformas móviles' => $competencias,
    'Plataformas de TV Interactiva' => $competencias,
    'Tecnologías server-side' => $competencias,
    'Desarrollo de software' => $competencias,
    'Gestión de servicios' => $competencias,
    'Dominio de aplicaciones' => $competencias,
    'Diseño y experiencia de usuario' => $competencias,
    'Contenidos' => $competencias,
    'Géneros' => $competencias,
  );
  _empresa_import_competences($grupos);
}

function _empresa_import_basic_array() {
  return array (
    'name' => 'Empresa',
    'type' => 'empresa',
    'description' => 'Empresa para el portal de negocios COMPANY',
    'title_label' => 'Nombre de la empresa',
    'body_label' => '',
    'min_word_count' => '0',
    'help' => '',
    'node_options' =>
    array (
      'status' => true,
      'promote' => true,
      'sticky' => true,
      'revision' => true,
    ),
    'language_content_type' => '0',
    'upload' => '0',
    'old_type' => 'empresa',
    'orig_type' => '',
    'module' => 'node',
    'custom' => '1',
    'modified' => '1',
    'locked' => '0',
    'content_profile_use' => 1,
    'comment' => '0',
    'comment_default_mode' => '4',
    'comment_default_order' => '1',
    'comment_default_per_page' => '50',
    'comment_controls' => '3',
    'comment_anonymous' => 0,
    'comment_subject_field' => '0',
    'comment_preview' => '0',
    'comment_form_location' => '0',
    'print_display' => 0,
    'print_display_comment' => 0,
    'print_display_urllist' => 0,
    'i18n_newnode_current' => 0,
    'i18n_required_node' => 0,
    'i18n_lock_node' => 0,
    'i18n_node' => 1,
    'i18nsync_nodeapi' =>
    array (
      'name' => false,
      'status' => false,
      'promote' => false,
      'moderate' => false,
      'sticky' => false,
      'revision' => false,
      'parent' => false,
      'taxonomy' => false,
      'comment' => false,
      'files' => false,
      'field_empresa_direccion' => false,
      'field_empresa_ciudad' => false,
      'field_empresa_cp' => false,
      'field_empresa_pais' => false,
      'field_empresa_provincia' => false,
      'field_empresa_sector' => false,
      'field_empresa_telefono' => false,
      'field_empresa_web' => false,
      'field_empresa_email' => false,
      'field_empresa_descripcion' => false,
      'field_empresa_logo' => false,
      'field_empresa_video' => false,
      'field_empresa_activitystream' => false,
    ),
  );
}
/**
 * Function to import groups and fields as competence's group and competences
 */
function _empresa_import_competences($grupos) {
  unset($data);
  $data['type'] = _empresa_import_basic_array();
  foreach ($grupos as $grupo => $competencias) {
    unset($competencia_inc);
    $grupo_inc++;
    $gnum = str_repeat('0',3 - strlen($grupo_inc)) . $grupo_inc;
    $data['groups'][] = array (
      'label' => $grupo,
      'group_type' => 'standard',
      'settings' =>
      array (
        'form' =>
        array (
          'style' => 'fieldset_collapsed',
          'description' => '',
        ),
        'display' =>
        array (
          'description' => '',
          'teaser' =>
          array (
            'format' => 'fieldset',
            'exclude' => 0,
          ),
          'full' =>
          array (
            'format' => 'fieldset',
            'exclude' => 0,
          ),
          4 =>
          array (
            'format' => 'fieldset',
            'exclude' => 0,
          ),
          2 =>
          array (
            'format' => 'fieldset',
            'exclude' => 0,
          ),
          3 =>
          array (
            'format' => 'fieldset',
            'exclude' => 0,
          ),
          5 =>
          array (
            'format' => 'fieldset',
            'exclude' => 0,
          ),
          'token' =>
          array (
            'format' => 'fieldset',
            'exclude' => 0,
          ),
          'label' => 'above',
        ),
      ),
      'weight' => (100 + $grupo_inc),
      'group_name' => 'group_empresa_g000' . $gnum,
    );
    if (is_array($competencias)) {
      foreach ($competencias as $competencia) {
        $competencia_inc++;
        $cnum = str_repeat('0',3 - strlen($competencia_inc)) . $competencia_inc;
        $data['type']['i18nsync_nodeapi']['field_empresa_c' . $gnum . $cnum] = false;
        $data['fields'][] = array (
          'label' => $competencia,
          'field_name' => 'field_empresa_c' . $gnum . $cnum,
          'type' => 'text',
          'widget_type' => 'optionwidgets_select',
          'change' => 'Change basic information',
          'weight' => (100 + $competencia_inc),
          'description' => '',
          'default_value' =>
          array (
            0 =>
            array (
              'value' => '0',
            ),
          ),
          'default_value_php' => '',
          'default_value_widget' => NULL,
          'group' => 'group_empresa_g000' . $gnum,
          'required' => 0,
          'multiple' => '0',
          'text_processing' => '0',
          'max_length' => '',
          'allowed_values' => "0\n1\n2\n3",
          'allowed_values_php' => '',
          'op' => 'Guardar configuraciones del campo',
          'module' => 'text',
          'widget_module' => 'optionwidgets',
          'columns' =>
          array (
            'value' =>
            array (
              'type' => 'text',
              'size' => 'big',
              'not null' => false,
              'sortable' => true,
              'views' => true,
            ),
          ),
          'display_settings' =>
          array (
            'label' =>
            array (
              'format' => 'above',
              'exclude' => 0,
            ),
            'teaser' =>
            array (
              'format' => 'default',
              'exclude' => 0,
            ),
            'full' =>
            array (
              'format' => 'default',
              'exclude' => 0,
            ),
            4 =>
            array (
              'format' => 'default',
              'exclude' => 0,
            ),
            2 =>
            array (
              'format' => 'default',
              'exclude' => 0,
            ),
            3 =>
            array (
              'format' => 'default',
              'exclude' => 0,
            ),
            5 =>
            array (
              'format' => 'default',
              'exclude' => 0,
            ),
            'token' =>
            array (
              'format' => 'default',
              'exclude' => 0,
            ),
          ),
        );
      }
    }
    else {
      $competencia_inc++;
      $cnum = str_repeat('0',3 - strlen($competencia_inc)) . $competencia_inc;
      $data['type']['i18nsync_nodeapi']['field_empresa_c' . $gnum . $cnum] = false;
      $data['fields'][] = array (
        'label' => $competencias,
        'field_name' => 'field_empresa_c' . $gnum . $cnum,
        'type' => 'text',
        'widget_type' => 'optionwidgets_select',
        'change' => 'Change basic information',
        'weight' => (100 + $competencia_inc),
        'description' => '',
        'default_value' =>
        array (
          0 =>
          array (
            'value' => '0',
          ),
        ),
        'default_value_php' => '',
        'default_value_widget' => NULL,
        'group' => 'group_empresa_g000' . $gnum,
        'required' => 0,
        'multiple' => '0',
        'text_processing' => '0',
        'max_length' => '',
        'allowed_values' => "0\n1\n2\n3",
        'allowed_values_php' => '',
        'op' => 'Guardar configuraciones del campo',
        'module' => 'text',
        'widget_module' => 'optionwidgets',
        'columns' =>
        array (
          'value' =>
          array (
            'type' => 'text',
            'size' => 'big',
            'not null' => false,
            'sortable' => true,
            'views' => true,
          ),
        ),
        'display_settings' =>
        array (
          'label' =>
          array (
            'format' => 'above',
            'exclude' => 0,
          ),
          'teaser' =>
          array (
            'format' => 'default',
            'exclude' => 0,
          ),
          'full' =>
          array (
            'format' => 'default',
            'exclude' => 0,
          ),
          4 =>
          array (
            'format' => 'default',
            'exclude' => 0,
          ),
          2 =>
          array (
            'format' => 'default',
            'exclude' => 0,
          ),
          3 =>
          array (
            'format' => 'default',
            'exclude' => 0,
          ),
          5 =>
          array (
            'format' => 'default',
            'exclude' => 0,
          ),
          'token' =>
          array (
            'format' => 'default',
            'exclude' => 0,
          ),
        ),
      );
    }
  }
  $data['extra']  = array (
    'title' => '-5',
    'menu' => '9',
    'revision_information' => '10',
    'author' => '11',
    'options' => '12',
    'path' => '13',
    'print' => '14',
    'comment_settings' => '15',
  );
  // dump($data);
  _empresa_import_competences_run($data);
  _empresa_import_competences_run($data);
}
/**
 *  Submit handler for import form.
 *   For each submitted field:
 *     1) add new field to the database
 *     2) execute the imported field macro to update the settings to the imported values
 */
function _empresa_import_competences_run($data) {
  $type_name = 'empresa';
  $type_label = node_get_types('name', $type_name);
  module_load_include('inc', 'content', 'includes/content.crud');
  $content_info = _content_type_info();
  $imported_type = $data['type'];
  $imported_type_name = $imported_type['type'];
  $imported_type_label = $imported_type['name'];
  $imported_fields = isset($data['fields']) ? $data['fields'] : array();
  $not_enabled = array();
  $imported_groups = array();
  if (isset($data['groups']) && module_exists('fieldgroup')) {
    $imported_groups = (array) $data['groups'];
  }
  elseif (isset($data['groups']) && is_array($data['groups'])) {
    $not_enabled[] = 'fieldgroup';
  }
  foreach ($imported_fields as $import) {
    $field = content_field_instance_collapse($import);
    if (empty($field['module']) || empty($field['widget_module'])) {
      $not_enabled[] = $field['field_name'];
    }
    else {
      if (!module_exists($field['module'])) {
        $not_enabled[] = $field['module'];
      }
      if (!module_exists($field['widget_module'])) {
        $not_enabled[] = $field['widget_module'];
      }
    }
  }
  if ($not_enabled) {
    $errors = true;
    drupal_set_message(t('The following modules must be enabled for this import to work: %modules.', array(
        '%modules' => implode(', ', array_unique($not_enabled)))),'error');
  }
  if ($errors) {
    drupal_set_message(t('Exiting. No import performed.'), 'error');
    return;
  }
  if (module_exists('fieldgroup') && $imported_groups) {
    foreach ($imported_groups as $group) {
      $group_name = $group['group_name'];
      fieldgroup_save_group($type_name, $group);
    }
    fieldgroup_groups('', FALSE, TRUE);
  }
  $rebuild = FALSE;
  foreach ($imported_fields as $field) {
    $field_name   = $field['field_name'];
    $field['type_name'] = $type_name;
    if (!empty($field['field_name']) && isset($content_info['content types'][$type_name]['fields'][$field_name])) {
      drupal_set_message(t('The imported field %field_label (%field_name) was not added to %type because that field already exists in %type.', array(
        '%field_label' => $field['label'], '%field_name' => $field_name, '%type' => $type_label)));
    }
    else {
      $field = content_field_instance_create($field, FALSE);
      $rebuild = TRUE;
      drupal_set_message(t('The field %field_label (%field_name) was added to the content type %type.', array(
        '%field_label' => $field['widget']['label'], '%field_name' => $field_name, '%type' => $type_label)));
    }
    if (module_exists('fieldgroup') && isset($imported_groups)) {
      fieldgroup_update_fields($field);
    }
  }
  if ($rebuild) {
    content_clear_type_cache(TRUE);
    menu_rebuild();
  }
  if (isset($data['extra'])) {
    variable_set('content_extra_weights_'. $type_name, $data['extra']);
  }
}
