<?php

function script() {
  echo '<hr />';
  $file = getcwd() . '/../../Dropbox/Sublime Text 2/conf/Packages/User/Kate.tmTheme';
  echo '<strong>Theme</strong>: ' . $file . '<hr />';
  $config = explode("\n", file_get_contents($file));

  if (isset($_GET['index'])) {
    $reset = '';
    $hex   = substr($config[$_GET['index']], strpos($config[$_GET['index']], '#'), 7);
    if (isset($_GET['rand'])) {
      mt_srand((double) microtime() * 1000000);
      $color = '';
      while (strlen($color) < 6) {
        $color .= sprintf("%02X", mt_rand(0, 255));
      }
      $newhex = '#' . $color;
      if (isset($_GET['set'])) {
        $newhex = '#' . $_GET['set'];
      }
      if (!strstr($config[$_GET['index']], '<!--')) {
        $config[$_GET['index']] = str_replace($hex, $newhex . '<!--' . $hex . '-->', $config[$_GET['index']]);
      }
      else {
        $reset = '<a href="?index=' . $_GET['index'] . '&rand=ok&reset=ok">Reset?</a>';
        $config[$_GET['index']] = str_replace('>' . $hex, '>' . $newhex, $config[$_GET['index']]);
      }
      $hex = $newhex;
    }
    $span = '<span style="background-color:' . $hex . ';">&nbsp;&nbsp;</span>';
    $rand = '<a href="?index=' . $_GET['index'] . '&rand=ok">Randomize?</a>';
    echo '<strong>Current Color</strong>: ' . $span . ' ' . $hex . $rand . $reset . '<hr />';
    if (isset($_GET['reset'])) {
      $oldhex = explode('#', $config[$_GET['index']]);
      $oldhex = '#' . substr($oldhex[2], 0, 6);
      $span   = '<span style="background-color:' . $oldhex . ';">&nbsp;&nbsp;</span>';
      echo '<strong>Color Reset to Backup</strong>: ' . $span . ' ' . $oldhex . '<hr />';
      $config[$_GET['index']] = str_replace('>' . $hex, '>' . $oldhex, $config[$_GET['index']]);
    }
  }
  $tab = 0;
  echo '<strong>Theme Colors:</strong><br /><br />';
  echo '<div class="wrapper">';
  $order = array();
  foreach ($config as $index => $line) {
    if (strstr($line, '#')) {
      $hex = substr($line, strpos($line, '#'), 7);
      $order[$index] = $hex;
    }
  }
  asort($order);
  foreach ($order as $index => $hex) {
    $line = $config[$index];
    if ($tab++ == 18) {
      echo '</div><div class="wrapper">';
      $tab = 1;
    }
    if (strstr($line, '<!--')) {
      $class = 'backup';
    }
    else {
      $class = 'original';
    }
    if (isset($_GET['index'])) {
      if ($index == $_GET['index']) {
        $class .= ' active';
      }
    }
    echo '<div class="' . $class . '">';
    echo '<span style="background-color:' . $hex . ';">&nbsp;&nbsp;</span>';
    echo '<a class="' . $class . '" href="?index=' . $index . '">' . $hex . '</a>';
    echo '</div>';
    $colors[$index] = $line;
    $theme[$hex]    = $hex;
  }
  echo '</div>';
  echo '<hr />';
  echo '<p><strong>SublimeText 2 Palette</strong>: ' . count($theme) . '</p>';
  asort($theme);
  foreach ($theme as $hex) {
    if (isset($_GET['index'])) {
      echo '<a class="palette" href="?index=' . $_GET['index'] . '&rand=ok&set=' . str_replace('#', '', $hex) . '" title="' . $hex . '"><span style="background-color:' . $hex . ';">&nbsp;&nbsp;</span></a>';
    }
    else {
      echo '<span class="palette" style="background-color:' . $hex . ';">&nbsp;&nbsp;</span>';
    }
    //echo ' ' . $hex . '<br />';
  }
  if (isset($_GET['rand'])) {
    $fp = fopen($file, 'w');
    fwrite($fp, implode("\n", $config));
    fclose($fp);
  }
  echo '<hr /><strong>Kate Original Theme</strong>:<br />';
  $kate = array(
    'Background' =>         '#201F1F',
    'Normal Text' =>        '#FF8000',
    'PHP Text' =>           '#C3C3C3',
    'Function' =>           '#8E79A5',
    'Keyword'  =>           '#E8E800',
    'Control Structures' => '#78B753',
    'Loops'  =>             '#FF8000',
    'Return' =>             '#FF0000',
    'Special methods'  =>   '#1E90FF',
    'Magic Constants'  =>   '#1E90FF',
    'Super Variables'  =>   '#FF8000',
    'Variables'  =>         '#00C0C0',
    'Decimal'  =>           '#FF55EE',
    'Octal'  =>             '#FF8000',
    'Hex'  =>               '#00C000',
    'Float'  =>             '#FF55EE',
    'String' =>             '#E85848',
    'Booleans' =>           '#FF8000',
    'Comment'  =>           '#7F7F7F',
    'Backslash Code' =>     '#E8E800',
    'Other'  =>             '#78B753',
  );
  asort($kate);
  foreach ($kate as $description => $hex) {
    if (isset($_GET['index'])) {
      echo '<a class="palette" href="?index=' . $_GET['index'] . '&rand=ok&set=' . str_replace('#', '', $hex) . '" title="' . $hex . '"><span style="background-color:' . $hex . ';">&nbsp;&nbsp;</span></a>';
    }
    else {
      echo '<span class="palette" style="background-color:' . $hex . ';">&nbsp;&nbsp;</span>';
    }
    echo ' ' . $hex . ' (' . $description . ')<br />';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>SublimeText Color Changer</title>
  <meta charset="UTF-8"/>
  <style type="text/css">
    div {
      padding: 1px;
      margin: 1px;
    }
    div.wrapper {
      float: left;
      display: inline;
    }
    a {
      margin-left: 5px;
      padding-left: 5px;
    }
    a.backup {
      color: #268db2;
    }
    a.original {
      color: #bb3700;
    }
    div.active {
      background-color: #000000;
    }
    div.active a {
      color: #ffff00;
    }
    span {
      width: 10px;
      height: 10px;
    }
    hr {
      display: block;
      width: 100%;
    }
    .palette {
      margin: 1px;
      padding: 0;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <pre><?php script(); ?></pre>
</body>
</html>
