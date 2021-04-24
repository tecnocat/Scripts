<?php /* UTF-8 Verified (áéíóú) */

// $Id$

$seed_lenght = 88;

function md6($password, $hash = '') {

  global $seed_lenght;

  $sha1 = utf8_encode($password);

  if ($hash == '') {
    for ($i = 0; $i < $seed_lenght; $i++) {
      $hash .= dechex(rand(0,15));
    }
  }

  for ($i = 0; $i < 3000; $i++) {
    $sha1 = sha1($hash . $sha1);
  }

  return $hash . $sha1;
}

// Test
$string = 'this is my long password';
$hash   = md6($string);
$test   = md6($string, substr($hash, 0, $seed_lenght));
echo '<pre>Hashing: ' . $string . '<br />';
echo 'Hash 1: ' . $hash . '<br />';
echo 'Hash 2: ' . $test . '<br />';
echo 'Length: ' . strlen($hash) . '<br />';
echo 'Seed: ' . $seed_lenght . '</pre>';