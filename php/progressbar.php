<?php

/**
 * With this Script you can create a nice progressbars with strings!
 */

for ($i = 0; $i <= 256; $i++) {
  echo progressbar($i, 256, md5(time()));
}

function progressbar($progress, $total, $string = null) {

  if (!$string) {
    $sring = str_repeat('|', 32);
  }

  $percent     = round(($progress / $total) * 100, 2);
  $color       = round((strlen($string) * $percent) / 100, 0);
  $completed   = substr($string, 0, $color);
  $remain      = str_replace($completed, '', $string);
  $progressbar = ' [<info>' . $completed . '</info><comment>' . $remain . '</comment>] ' . sprintf('%3s', $percent) . '%: ';

  return $progressbar;
}