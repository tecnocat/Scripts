<?php /* UTF-8 Verified (áéíóú) */

// $Id$

/*
 * Implements hook_form_alter(&$form, &$form_state, $form_id)
 */
function empresa_form_alter(&$form, &$form_state, $form_id) {

  if ($form_id == 'empresa_node_form') {

    // initialize steps
    if (empty($form_state['storage']['form_step'])) { $form_state['storage']['form_step'] = 2; }
    $step = $form_state['storage']['form_step'];

    // breadcrumb
    if (arg(2) == 'edit') {
      $step_breadcrumb = company_step_breadcrumb(($step - 1), array(t('Edit company data'),t('Edit self assessment')));
    }
    else {
      $step_breadcrumb = company_step_breadcrumb($step, array(t('Confirm authorization'),t('Company data'),t('Self assessment')));
    }

    // form prefix
    $form_prefix = '<div id="creacion-edicion-empresa-anf" class="creacion-edicion-empresa-anf">' . $step_breadcrumb . '&nbsp;<div class="cuadrogris">';

    // form suffix
    $form_suffix = '</div></div>';

    // steps
    switch ($step) {

      case 2: // datos de empresa

        // hide fieldsets
        $fieldsets = array(
          'attachments',
          'author',
          'comment_settings',
          'menu',
          'options',
          'path',
          'revision_information',
          'print',
        );
        foreach ($fieldsets as $fieldset) {
          $form[$fieldset]['#type'] = 'hidden';
        }

        // del competences
        foreach ($form as $field => $dummy) {
          if (ereg('^field_empresa_c[0-9]',$field)) {
            unset($form[$field]);
          }
        }

        // tunning web fields
        $form['#field_info']['field_empresa_web']['columns']['value']['sortable'] = false;
        $form['field_empresa_web']['field_empresa_web_add_more']['#value'] = t('Add another website');
        $form['field_empresa_web']['field_empresa_web_add_more']['#attributes'] = array(
          'class' => 'BigBtnRosa',
        );
        $content_type = content_types($form['#node']->type);
        foreach ($content_type['fields'] as $field_name => $field) {
          if ($field['multiple']) {
            $filled = count($form['#node']->$field_name);
            $index = max($filled, 1);
            unset($form[$field_name][$index]);
          }
        }

        // replace submit to next button
        $form['next'] = array(
          '#type' => 'submit',
          '#value' => t('Continue'),
          '#weight' => 100,
          '#attributes' => array(
            'class' => 'BigBtnRosa',
          ),
        );
        $form['#submit'][0] = 'empresa_form_submit';
        $form['buttons']['submit']['#type'] = 'hidden';
        $form['buttons']['captcha']['#weight'] = 99;
        unset($form['submit']);
        unset($form['buttons']['preview']);

        // add lifestream form
        $form['activitystream']['intro'] = array(
          '#type' => 'item',
          '#value' => t(variable_get('empresa_texto_activitystream','')),
          '#prefix' => '<div class="cuadrogrisderecha"><div class="cgbackgrund rlife">',
          '#suffix' => '</div></div>',
        );
        $form['activitystream'] += _company_get_lifestream_form($form['#node']->field_empresa_activitystream[0]['value'], $form_id);
        $form['activitystream']['#weight'] = 69;

        // hide activitystream field
        $form['#field_info']['field_empresa_activitystream']['type'] = 'hidden';
        $form['field_empresa_activitystream'][0]['#type'] = 'hidden';

        // hide employees that worked them field
        $form['#field_info']['field_empresa_empleados']['type'] = 'hidden';
        $form['field_empresa_empleados'][0]['#type'] = 'hidden';

        // hide employees that like this company field
        $form['#field_info']['field_empresa_admiradores']['type'] = 'hidden';
        $form['field_empresa_admiradores'][0]['#type'] = 'hidden';

        // add some js
        $add_js = "
        Drupal.behaviors.empresaBehavior = function(context) {
          $('#field_empresa_web_values tbody tr td.delta-order').remove();
          $('#edit-field-empresa-web-field-empresa-web-add-more').val('" . t('Add another website') . "');
          var webs = $('#field_empresa_web_values').find('input[name|=field_empresa_web]').length;
          if (webs == 3) {
            $('#edit-field-empresa-web-field-empresa-web-add-more').hide();
          }
          if ($('#edit-field-empresa-provincia-value').val() == 0) {
            UpdateListStates();
          }
          $('#edit-field-empresa-pais-value').change(
            function() {
              UpdateListStates();
            }
          );
          $('input#edit-field-empresa-logo-0-filefield-upload').hide();
        }
        function UpdateListStates() {
          if ($('#edit-field-empresa-pais-value').val() != 'ES') {
            $('#edit-field-empresa-provincia-value').html('<option value=\"none\">none</option>');
            $('#edit-field-empresa-provincia-value-wrapper').hide();
          }
          else {
            $('#edit-field-empresa-provincia-value').html('<option value=\"\">" . t('Loading...') . "</option>');
            $('#edit-field-empresa-provincia-value-wrapper').show();
            $.getJSON(
              document.location.protocol + \"//\" + document.location.host + Drupal.settings.basePath + \"localtion/populate\",
              {
                id: $('#edit-field-empresa-pais-value').val(),
                ajax: 'true'
              },
              function(j) {
                var states = '';
                for (var i = 0; i < j.length; i++) {
                  states += '<option value=\"' + j[i] + '\">' + j[i] + '</option>';
                }
                $('#edit-field-empresa-provincia-value').html(states);
              }
            );
          }
        }";
        drupal_add_js($add_js,'inline');
        break;

      case 3: // evaluacion

        // hide fieldsets
        $fieldsets = array(
          'attachments',
          'author',
          'comment_settings',
          'menu',
          'options',
          'path',
          'revision_information',
          'print',
        );
        foreach ($fieldsets as $fieldset) {
          $form[$fieldset]['#type'] = 'hidden';
        }

        // restore last form data
        $form_state['values'] = $form_state['storage']['form_data'];

        // remove fields
        $fields = array(
          'title',
          'body_field',
          '#content_extra_fields',
        );
        foreach ($form as $field => $fieldname) {
          if (in_array($field,$fields) || ereg('^field_empresa',$field)) {
            if (!ereg('^field_empresa_c[0-9]',$field)) {
              unset($form[$field]);
            }
          }
        }

        // tunning form
        $form['buttons']['submit']['#value'] = t('Finish');
        $form['buttons']['submit']['#attributes'] = array(
          'class' => 'BigBtnRosa',
        );

        unset($form['buttons']['captcha']);
        unset($form['buttons']['preview']);

        // add more form_prefix
        $form_prefix .=
          t(variable_get('empresa_texto_auto_evaluacion_competencias', '')) . '
          <div id="competences-title">' . t('Competences') . '</div>
        ';

        // add suggest competences
        $form['suggest_competences'] = array(
          '#type' => 'fieldset',
          '#title' => t('Suggest Competences'),
          '#weight' => 500,
          '#collapsible' => false,
          '#collapsed' => false,
        );
        for ($i = 1; $i <= 2; $i++) {
          $form['suggest_competences']['suggest_group_' . $i] = array(
            '#type' => 'textfield',
            '#title' => t('Competence\'s Group'),
          );
          $form['suggest_competences']['suggest_competence_' . $i] = array(
            '#type' => 'textfield',
            '#title' => t('Competence'),
          );
        }

        // add some js
        $add_js = "
        Drupal.behaviors.empresaBehavior = function(context) {
          $('select[name|=field_empresa_c0].form-select option').each(
            function() {
              if ($(this).val() == '') {
                $(this).remove();
              }
              if ($(this).val() == 0) {
                $(this).html('0 - " . t('not know') . "');
              }
              if ($(this).val() == 3) {
                $(this).html('3 - " . t('expert') . "');
              }
            }
          );
        }";
        drupal_add_js($add_js,'inline');
        break;

      case 4: // finish

        // unset, clear msgs and redirect
        unset($form);
        if ($form_state['storage']['node_created_type'] == 'add') {
          drupal_get_messages();
          drupal_goto('alta/empresa/finish/' . $form_state['storage']['node_created']);
        }
        if ($form_state['storage']['node_created_type'] == 'edit') {
          drupal_goto('node/' . $form_state['storage']['node_created']);
        }
        break;
      default:
        break;
    }
    // add prefix
    $form['#prefix'] = $form_prefix;

    // replace submit
    $form['buttons']['submit']['#submit'][0] = 'empresa_form_submit';
  }else if ($form_id == 'empresa_busqueda_empresas_form' || $form_id == 'empresa_busqueda_empresas_derecha_form') {
  $competenciastitle = $form['competencias']['#title'];
  $form['competencias']['#title'] = '';
  $form['competencias']['#prefix'] = '<div id="profile-competencias-buscador" class="form-item"><label for="profile-scompetencias-buscador">'.t($competenciastitle).': </label>';
  $form['competencias']['#suffix'] = '</div>';
  $sectortitle = $form['profile_sector']['#title'];
  $form['profile_sector']['#title'] = '';
  $form['profile_sector']['#prefix'] = '<div id="profile-sector-buscador" class="form-item"><label for="profile-sector-buscador">'.t($sectortitle).': </label>';
  $form['profile_sector']['#suffix'] = '</div>';
    }
}

/**
 * Implements hook_form_submit().
 */
function empresa_form_submit($form, &$form_state) {
  if ($form_state['storage']['form_step'] == 2) {
    foreach ($form_state['values'] as $activitystream => $dummy) {
      if (ereg('^activitystream_',$activitystream)) {
        $activitystreams[$activitystream] = $form_state['values'][$activitystream];
      }
    }
    $form_state['values']['field_empresa_activitystream'][0]['value'] = serialize($activitystreams);
    $form_state['storage']['form_data'] = $form_state['values'];
  }
  if ($form_state['storage']['form_step'] == 3) {
    $diff = array_diff_key($form_state['values'],$form_state['storage']['form_data']);
    $form_state['storage']['form_data'] += $diff;
    $form_state['values'] = $form_state['storage']['form_data'];
    global $user;
    if ($user->uid != 1) {
      $data = array(
        'empresa_created' => $form_state['values']['title'],
        'empresa_ip' => ip_address(),
      );
    }
    user_save($user, $data);
    if (empty($form_state['storage']['node_created'])) {
      $node = (object) $form_state['values'];
      if (arg(2) == 'edit') {
        $form_state['storage']['node_created_type'] = 'edit';
        $node->is_new = false;
        $node->nid = arg(1);
        empresa_ultimos_cambios('compare', $node);
      }
      else {
        $form_state['storage']['node_created_type'] = 'add';
        $node->is_new = true;
        $node->nid = null;
      }
      $node->status = 1;
      node_save($node);
      $form_state['storage']['node_created'] = $node->nid;
    }
    for ($i = 1; $i <= 2; $i++) {
      if ($form_state['storage']['form_data']['suggest_group_' . $i] && $form_state['storage']['form_data']['suggest_competence_' . $i]) {
        $email['body'] .= t('Group suggested competences:')  . ' ' . $form_state['storage']['form_data']['suggest_group_' . $i] . "\n";
        $email['body'] .= t('Suggested competence:')  . ' ' . $form_state['storage']['form_data']['suggest_competence_' . $i] . "\n\n";
      }
    }
    if ($email['body']) {
      global $user;
      $email['id'] = 'suggest_competence_email';
      $email['to'] = variable_get('site_mail', ini_get('sendmail_from'));
      $email['subject'] = t('New suggested competences from !user',array('!user' => $user->name));
      if (drupal_mail_send($email)) {
        drupal_set_message(t('Congratulations!, your message with your suggested competences have been sent!, thank you.'));
      }
      else {
        drupal_set_message(t('Oops...! There is a problem when trying to sent your suggested competences, contact the Administrator.','error'));
      }
    }
  }
  $form_state['storage']['form_step']++;
}

function _empresa_confirma(&$form, $form_state) {
  global $user;
  if ($form_state['clicked_button']['#value'] == t('Confirm')) {
    $data = array(
      'empresa_confirm' => 'si',
    );
    user_save($user, $data);
    drupal_goto('node/add/empresa');
  }
  else {
    drupal_goto('<front>');
  }
}

function _empresa_confirmar_autorizacion($form_state) {
  global $user;
  profile_load_profile($user);
  $replaces = array(
    '%username' => $user->name . ' ' . $user->profile_apellido,
    '%company' => $user->profile_empresa,
    '%city' => $user->profile_state,
    '%ip' => ip_address(),
  );
  $form['form_empresa_confirm']['ok'] = array(
    '#type' => 'submit',
    '#value' => t('Confirm'),
    '#prefix' => company_step_breadcrumb(1, array(t('Confirm authorization'),t('Company data'),t('Self assessment'))) . t(variable_get('empresa_texto_confirmacion_autorizacion', ''),$replaces),
  );
  $form['form_empresa_confirm']['cancel'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
  );
  $form['#submit'][] = '_empresa_confirma';
  return $form;
}

function _empresa_finalizar_proceso($form_state) {
  $replaces = array(
    '!link' => l(t('company\'s profile'),'node/' . arg(3))
  );
  $form['form_empresa_finish']['ok'] = array(
    '#type' => 'hidden',
    '#value' => t('Ok'),
    '#prefix' => company_step_breadcrumb(4, array(t('Confirm authorization'),t('Company data'),t('Self assessment'))) . t(variable_get('empresa_texto_confirmacion_finalizado', ''),$replaces),
  );
  return $form;
}


/**  modificaciones realizadas por Ricardo Higuera*/

/** Para WF 320*/
function empresa_busqueda_empresas_form()
{
    //texto mostrado por pantalla
    //$etq_expandir    = t('Expand all');
    //$etq_contraer    = t('Collapse all');

    $form = drupal_retrieve_form('empresa_busqueda_empresas_derecha_form');

   $form['titulo']["#value"]  = "<p class ='titulo_form' id='titulo_formulario'>".t("Advanced search")."</p>";
   $form['titulo']["#weight"] =  -3;
   $form['free']["#weight"]   =  -5;
   $form['name']["#weight"]   =  -2;

    //cambios para adaptar el formulario a diseño
    $form['#prefix'] = "";
    $form['titulo']['#prefix'] = "<div class='cuadrogris'>";
    $form['free']['#prefix'] ="<div id='busquedalibre' class='cuadrogris container-inline'>";
    $form['free']['#suffix'] ="<button class='SmallBtnRosa'>".t("Search")."</button></div>";
  /*
    //botones expandir y contraer
    drupal_add_js(
    	"function expand_all(){
        $('fieldset.collapsible').each(
            function()
            {
                if( $(this).hasClass('collapsed') )
                    Drupal.toggleFieldset( this);
            }
    	);
    }
    function collapse_all(){
        $('fieldset.collapsible').each(
            function()
            {
                if( !$(this).hasClass('collapsed') )
                    Drupal.toggleFieldset( this);
            }
    	);
    }",
    	"inline"
    );

    $form['competencias']['#title'].= "<br/><a href='javascript:expand_all();'>$etq_expandir</a><br/><a href='javascript:collapse_all();'>$etq_contraer</a>";
    //*/
    return $form;
}

/** Para WF 321*/
function empresa_busqueda_empresas_derecha_form()
{
    //texto mostrado por pantalla
    $etq_competencia = t('Competences');

    //extraemos el formulario de usuarios para modificarlo
    $form = drupal_retrieve_form("company_search_users_form");
    drupal_prepare_form("company_search_users_form",$form);

    //borramos aquellos campos que no son necesarios
    unset($form['profile_apellido']);
    unset($form['profile_cargo']);
    unset($form['soy']);
    unset($form['es']);

    $form['titulo'] = array(
    	"#weight" => "-300",
        "#value"  => "<p class ='titulo_form' id='titulo_formulario'>".t("Search filter")."</p>",
    );
    $form['submit']['#attributes'] = array('class' => 'SmallBtnRosa');
    //cambiamos los parametros url que se generaran y algunos titulos
    $form['profile_sector']['#name'] = 'sector';
    $form['profile_sector']['#type'] = "fieldset";
    $form['profile_sector']['#attributes'] = array("class" => "fieldset-que-busque");
    $form['Personal data']['profile_country']['#name'] = 'pais';
    $form['Personal data']['profile_state']['#name'] = 'provincia';
    $form['profile_empresa']['#name'] = 'ciudad';
    $form['profile_empresa']['#title'] = t('City');
    //capturamos los parametros
    $form['profile_empresa']['#default_value']                  = $_REQUEST['ciudad'];
    if( isset($_REQUEST['pais']))
        $form['Personal data']['profile_country']['#default_value'] = $_REQUEST['pais'];
    if( isset($_REQUEST['provincia']))
        $form['Personal data']['profile_state']['#default_value']   = $_REQUEST['provincia'];

    //cambiamos sector de select a fieldset relleno de checkboxes
    foreach($form['profile_sector']['#options'] as $key => $value ){
        $form['profile_sector'][$key] = array(
            "#type"         => "checkbox",
            "#title"        => $value,
            "#name"         => "sector[]",
            "#return_value" => $key,
        );
    }
     //si hay sectores seleccionados en el request, los marcamos
    foreach( $_REQUEST['sector'] as $sector )
        $form['profile_sector'][$sector]['#default_value'] = 1;


    $primero = "Administration/Institution";
    $ultimo =  "TV / Video / Film / Animation";
    $form['profile_sector'][$primero]['#prefix'] = '<div class="formsoy">';
    $form['profile_sector'][$ultimo]['#suffix']  = "</div>";

    unset($form['profile_sector']['#options']);
    unset($form['profile_sector']['#description']);
    unset($form['profile_sector']['#multiple']);
    unset($form['profile_sector']['#size']);

    //aquí van los grupos de competencias y las competencias
     $form['competencias'] = array(
 		'#type'        => 'fieldset',
        '#title'       => $etq_competencia,
        '#collapsible' => FALSE,
        '#collapsed'   => FALSE,
    );

    //extraemos todos los campos
    $resultado = db_query(
        "SELECT field_name, label FROM {content_node_field_instance} WHERE type_name = 'empresa'"
    );
    $campos = array();

    //y construimos un array para consultar posteriormente
    while ( $campo = db_fetch_object($resultado)  ){
        $campos[$campo->field_name] = $campo->label;
    }

    /* *
      CONSULTA: (la lista de campos se devuelve unida por comas)
        SELECT gf.group_name, GROUP_CONCAT(field_name) as fields, g.label as group_label
    FROM content_group_fields gf
    LEFT JOIN content_group g ON gf.group_name = g.group_name
    WHERE gf.type_name ='empresa'
    GROUP BY gf.group_name
     * */

    //extraemos la lista de grupos con sus campos asociados
    $resultado = db_query("SELECT gf.group_name, GROUP_CONCAT(field_name) as fields, g.label as group_label FROM content_group_fields gf LEFT JOIN content_group g ON gf.group_name = g.group_name WHERE gf.type_name ='empresa'  GROUP BY gf.group_name");


    //para cada grupo
    while ( $grupo = db_fetch_object($resultado)  ){

        //fijar sus parametros (fieldset)
        $form['competencias'][$grupo->group_name] = array(
       '#type' => 'fieldset',
            '#title' => t(  $grupo->group_label ) ,
            '#collapsible' => TRUE,
          '#collapsed' => TRUE,
        );

        //escribir sus campos
        foreach( explode(',',$grupo->fields) as $field_name  ){

            $form['competencias'][$grupo->group_name][$field_name] = array(
            	'#type' => 'checkbox',
  				'#title' => t( $campos[$field_name] ),
            );
        }
    }

    //si esta seleccioando en el request, lo marcamos
    foreach($_REQUEST as $campo => $value){
        if( preg_match("/field_empresa_c/" , $campo) ){
            //print_r($campo);
            $grupo = db_result(db_query("SELECT group_name FROM {content_group_fields} WHERE field_name = '%s'",$campo));
            $form['competencias'][$grupo][$campo]['#default_value'] = 1;
            $form['competencias'][$grupo]['#collapsed'] = FALSE;
        }
    }

    return $form;
}