/**
 * Implements hook_theme() - TOKEN_USUARIO
 */
function TOKEN_HOOK_theme($existing, $type, $theme, $path) {

  $theme = array();

  // When call this theme use: theme('my-custom-theme', $arg_1, $arg_2);
  $theme['my_custom_theme'] = array(
    'arguments' => array('arg_1' => null, 'arg_2' => null),
    'template' => 'my-custom-theme',
  );

  return $theme;
}