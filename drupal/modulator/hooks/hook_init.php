/**
 * Implements hook_init() - TOKEN_USUARIO
 */
function TOKEN_HOOK_init() {

  if ($_GET['q'] == 'admin' OR $_GET['q'] == 'admin/build') {
    $path = drupal_get_path('module', 'TOKEN_HOOK');
    drupal_add_css($path . '/settings.css');
  }
}