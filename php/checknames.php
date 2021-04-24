<?php

$data = file_get_contents('NOMBRE - Propuestas.csv');
$data = explode("\n", $data);
$data = array_slice($data, 3);

foreach ($data as $row) {

  $name = strtolower(array_shift(explode(',', $row)));
  if (strstr($name, ' ')) {
    $name = str_replace(' ', '-', $name);
    check($name);
    $name = str_replace('-', '', $name);
  }
  check($name);
}
function check($name) {

  echo "\n\t\t\t\t\t\t\t\t\t\tCOMPROBANDO $name...\n";

  $twitter = 'Twitter: ' . $name . ' = ' . system('wget -t 3 -T 10 "http://twitter.com/' . $name . '" 2>&1|egrep "HTTP|Length|saved"');
  $domain  = 'Domain: ' . $name . ' = ' . system('wget -t 3 -T 10 "http://www.' . $name . '.com" 2>&1|egrep "HTTP|Length|saved"');
  if (!strstr($twitter, '200')) {
    echo "\t\t\t\t\t\t\t\t\t\t\tTWITTER LIBRE: @$name\n";
  }
  if (!strstr($domain, '200')) {
    echo "\t\t\t\t\t\t\t\t\t\t\tDOMINIO LIBRE: $name.com";
  }
}