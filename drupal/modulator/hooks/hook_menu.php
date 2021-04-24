/**
 * Implements hook_menu() - TOKEN_USUARIO
 */
function TOKEN_HOOK_menu() {

  $menu = array();

  $menu['admin/build/administer-custom-settings'] = array(
    'title'            => 'Administer TOKEN_PROYECTO Settings',
    'description'      => 'Custom settings for TOKEN_PROYECTO',
    'access arguments' => array('administer site configuration'),
    'page callback'    => 'drupal_get_form',
    'page arguments'   => array('TOKEN_MODULO_custom_settings'),
    'file'             => 'TOKEN_MODULO.admin.inc',
    'type'             => MENU_NORMAL_ITEM,
  );

  return $menu;
}