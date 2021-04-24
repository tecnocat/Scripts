<?php

/**
 * Some trash from my hard disk !xD
 */


// Moodle courses check date
$course = 'Modafoka';
$start  = time() - (6 * 24 * 3600);
$end    = $start + (7 * 24 * 3600);
$end    = 1331739516;

if ($start > time()) {
  $information = $course . ' - ' . date('d-m', $start);
}
elseif ($start < time() AND $end > time()) {

  if (date('d', $end) != date('d')) {
    $information = $course . ' - ' . round(($end - time()) / (3600 * 24)) . 'd';
  }
  else {
    if ($end - time() >= 3600) {
      $information = $course . ' - ' . round(($end - time()) / 3600) . 'h';
    }
    else {
      $information = null;
    }
  }
}
else {
  $information = null;
}

echo "<p>Now: " . time() . "</p><p>Start: $start</p><p>End: $end</p>";
die($information);



// Date checks
$english_date = '2012-02-21';
$spanish_date = '21-02-2012';
echo 'UNIX: ' . strtotime($english_date) . ' - DATE: ' . date('d/m/Y H:i:s', strtotime($english_date));
echo 'UNIX: ' . strtotime($spanish_date) . ' - DATE: ' . date('d/m/Y H:i:s', strtotime($spanish_date));
die();



// Generate a Micro$oft Word with PHP
header('Content-type: application/vnd.ms-word');
header('Content-Disposition: attachment;Filename=document_name.doc');

echo '<html>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">';
echo '<body>';
echo '<b>My first document</b>';
echo '</body>';
echo '</html>';
die();