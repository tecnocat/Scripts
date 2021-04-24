<?php

echo '<pre>';
echo '
  $iso639 = array(

    // ISO => array(ISO, ISO3, ISO-Numeric, tld, Country, array(Languages));
';
$data = explode("\n", file_get_contents('countryinfo.csv'));
foreach ($data as $row) {

  $info  = explode(',' , $row);
  $info[0] = strtolower($info[0]);
  $info[1] = strtolower($info[1]);
  $info[3] = array_pop(explode('.', $info[3]));
  if (isset($info[5])) {
    if (count(explode('\'', $row)) > 1) {
      $langs = explode('\'', $row);
      $langs = "array('" . strtolower(str_replace(',', "', '", $langs[1])) . "')";
    }
    else {
      $langs = "array('" . array_pop(explode(',', $row)) . "')";
    }
  }
  else {
    $langs = 'null';
  }
  echo "
    // $row
    '{$info[0]}' => array(
      'iso' => '{$info[0]}',
      'iso3' => '{$info[1]}',
      'numeric' => '{$info[2]}',
      'tdl' => '{$info[3]}',
      'name' => '{$info[4]}',
      'langs' => $langs,
    ),
";
}
echo '
  );';