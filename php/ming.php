<?php

var_dump(getswfsize('eBook-page-1.swf'));

function bin2dec($binstring) {
  $decvalue = 0;
  for ($i = 0; $i < strlen($binstring); $i++) {
    $decvalue += ((int) substr($binstring, strlen($binstring) - $i - 1, 1)) * pow(2, $i);
  }
  return $decvalue;
}

function getswfsize($file) {
  $handle = fopen($file, 'rb');
  $data = fread($handle, filesize($file));
  fclose($handle);
  $signature = substr($data, 0, 3);
  switch ($signature) {

    case 'FWS':
      $compressed = false;
      break;

    case 'CWS':
      $compressed = true;
      break;

    default:
      return array('error' => 'Expecting "FWS" or "CWS", found "' . $signature . '"');
  }

  if ($compressed) {
    $head = substr($data, 0, 8);
    $data = substr($data, 8);
    if ($decompressed = gzuncompress($data)) {
      $data = $head . $decompressed;
    }
    else {
      return array('error' => 'Error decompressing compressed SWF data');
    }
  }

  $bits = (ord(substr($data, 8, 1)) & 0xF8) >> 3;
  $length = ceil((5 + (4 * $bits)) / 8);
  $string = str_pad(decbin(ord(substr($data, 8, 1)) & 0x07), 3, '0', STR_PAD_LEFT);
  for ($i = 1; $i < $length; $i++) {
    $string .= str_pad(decbin(ord(substr($data, 8 + $i, 1))), 8, '0', STR_PAD_LEFT);
  }
  list($X1, $X2, $Y1, $Y2) = explode("\n", wordwrap($string, $bits, "\n", 1));
  $width = intval(round(bin2dec($X2) / 20));
  $height = intval(round(bin2dec($Y2) / 20));
  return array('width' => $width, 'height' => $height);
}