<?php

/**
 * roles:
 * 4 - revisor pedagógico
 * 5 - diseño
 * 6 - estilo
 * 7 - calidad
 *
 * state:
 * 1 - pedagogico
 * 2 - diseño
 * 3 - estilo
 * 4 - calidad
 */
function editor_contenidos_views_query_alter(&$view, &$query) {

  global $user;

  $display_ids = array(
    'views_allrevisions',
    'views_allrevisions_count'
  );

  if(in_array($view->current_display, $display_ids)){

    $role_state = array(4 => 1, 5 => 2, 6 => 3, 7 => 4);
    $role       = array_intersect_key($role_state, $user->roles);

    foreach($query->where[0]['conditions'] as $id => $condition){
      if ($condition['field'] == 'field_data_field_state.field_state_value') {
        $query->where[0]['conditions'][$id]['value'] = array_values($role);
      }
    }
  }
}