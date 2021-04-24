/**
 * Implements hook_form_alter() - TOKEN_USUARIO
 */
function TOKEN_HOOK_form_alter(&$form, &$form_state, $form_id) {

  switch ($form_id) {

    case 'my_form':

      // Custom modifications in the $form_id form
      // DO NOT USE unset() to hide fields, use ['#access'] = false instead
      break;

  }
}