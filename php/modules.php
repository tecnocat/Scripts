<?php

$basepath = '/home/tecnocat/workspace/company/sites/all/';
$modules  = scandir_tree($basepath);

function scandir_tree($fullpath = './') {

  foreach (scandir($fullpath) as $item) {

    $temp = $fullpath . $item;

    if ($item == '.' OR $item == '..' OR $item == '.svn') {
      continue;
    }

    if (array_pop(explode('.', $item)) == 'info') {

      //echo "<h3>$temp</h3>";

      $lines = explode("\n", file_get_contents($temp));

      foreach ($lines as $line) {

        if (strstr($line, '=')) {

          $data = explode('=', $line);
          $info = str_replace('"', '', trim($data[0]));
          $desc = str_replace('"', '', trim($data[1]));

          if ($info == 'description') {

            echo "
              <input type='textfield' value='$info' size='32' name='dummy1' /> =
              <input type='textfield' value='$desc' size='96' name='dummy2' />
              <br />
            ";
            echo array_shift(explode('.', $item)) . " - $desc<br />";
          }
        }
      }
    }

    if (is_dir($temp)) {
      scandir_tree($temp . '/');
    }
  }
}