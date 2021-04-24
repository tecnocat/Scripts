<?php

function http_digest_parse($txt) {
  $needed_parts = array(
    'nonce' => 1,
    'nc' => 1,
    'cnonce' => 1,
    'qop' => 1,
    'username' => 1,
    'uri' => 1,
    'response' => 1
  );
  $data = array();
  $keys = implode('|', array_keys($needed_parts));
  preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
  foreach ($matches as $m) {
    $data[$m[1]] = $m[3] ? $m[3] : $m[4];
    unset($needed_parts[$m[1]]);
  }
  return $needed_parts ? false : $data;
}

/**
 * CONFIG
 *
 * @param $realm Título de la zona a proteger
 * @param $users Array de usuarios y password
 */
$realm = 'Area restringida';
$users = array(
  'admin' => '1234' // usuario / password
);

/**
 * AUTH HTTP HEADER
 */
if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
  header('HTTP/1.1 401 Unauthorized');
  header('WWW-Authenticate: Digest realm="' . $realm . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
  unset($_SERVER['PHP_AUTH_DIGEST']);
  die('Acceso denegado.');
}

if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) || !isset($users[$data['username']])) {
  unset($_SERVER['PHP_AUTH_DIGEST']);
  die('Acceso denegado.');
}

$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);
$valid_response = md5($A1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $A2);

if ($data['response'] != $valid_response) {
  unset($_SERVER['PHP_AUTH_DIGEST']);
  die('Acceso denegado.');
}

/**
 * SECURED AREA
 *
 * @param phpinfo
 * @return información de la instalación de PHP
 *
 */
phpinfo();