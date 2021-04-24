<?php

if ($_GET['token'] == 'TS1SjnjgtDCRsKztWC13EKjAo9EMnOVnjewShAFNNv0') {

  $file = 'IP-' . $_SERVER['REMOTE_ADDR'] . '.ip';
  if (!is_file($file)) {
    $fp = fopen($file, 'w');
    fwrite($fp, print_r($_SERVER, TRUE));
    fclose($fp);
  }
}