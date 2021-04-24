<?php

/**
 * This Script have a strange language, it's a little silly don't worry ;-)
 */

/**
 * Vars
 */
$form = '';
$body = '';


/**
 * Config
 */
$sr = 'localhost';
$us = 'root';
$pw = 'drupal';
$db = 'reportes';


/**
 * Connect
 */
$link = mysqli_connect($sr, $us, $pw, $db);
if (mysqli_connect_errno()) {
  die('Connect failed: ' . mysqli_connect_error());
}
else {
  $null = mysqli_query($link, 'SET NAMES utf8');
  //$null = mysqli_query($link, 'INSERT INTO `reportes` (title, body, timestamp) VALUES ("' . md5(time()) . '", "' . md5(md5(time())) . '", "' . time() . '")');
  if ($_REQUEST) {
    if (isset($_REQUEST['submitNiuwProjectio']) OR isset($_REQUEST['switchar'])) {
      mysqli_query($link, 'INSERT INTO `reportes` (title, body, timestamp) VALUES ("' . $_REQUEST['title'] . '", "' . $_REQUEST['body'] . '", "' . time() . '")');
    }
  }
  $data = mysqli_query($link, 'SELECT * FROM `reportes` ORDER BY `timestamp` DESC');
  while ($a = mysqli_fetch_assoc($data)) {
    $time = (isset($time)) ? $time : $a['timestamp'];
    $long = (isset($last)) ? $last - $a['timestamp'] : time() - $a['timestamp'];
    $last = (isset($last)) ? $a['timestamp'] : time();
    $a['long'] = $long;
    a($a);
  }
  mysqli_free_result($data);
  mysqli_close($link);
}
$time = array(
  date('Y', $time),
  (date('n', $time) - 1),
  date('j', $time),
  date('H', $time),
  date('i', $time),
  date('s', $time),
);


/**
 * Functions
 */
function a($array) {
  t(l('Switchar a ' . $array['title'], $array) . ' ' . d($array['long']));
}


function t($text) {
  global $body;
  $body .= "
    $text<br />\n";
}


function l($text, $link) {
  if (is_array($link)) {
    $args = array();
    foreach ($link as $id => $val) {
      $args[] = "$id=$val";
    }
    $args = implode('&', $args);
  }
  return "<a class='projectio' href='?switchar=1&$args'>$text</a>";
}

function d($seconds, $acuracy = 7) {
  $periods = array(
    'year'   => 31536000,
    'month'  => 2419200,
    'week'   => 604800,
    'day'    => 86400,
    'hour'   => 3600,
    'minute' => 60,
    'second' => 1,
  );
  $i = 1;
  $return = array();
  foreach ($periods as $period => $limit) {
    $duration = floor($seconds / $limit);
    $seconds  = ($seconds % $limit);
    if ($duration == 0) {
      continue;
    }
    $return[] = $duration . ' ' . $period . ($duration > 1 ? 's' : '');
    $i++;
    if ($i > $acuracy) {
      break;
    }
  }
  return implode(' ', $return);
}

/**
 * PHP HTML
 */
$form = '
    <form id="projectio" name="projectio" method="POST" action="?' . md5(time()) . '">
      <legend>Niuw projectio</legend>
      <input type="text" name="title" value="" /><br />
      <textarea name="body" rows="5" cols="60"></textarea><br />
      <input type="submit" name="submitNiuwProjectio" value="Projectiar" />
    </form>';

if (isset($_REQUEST['title'])) {
  $body = '
    <h2>Switchado a projectio ' . $_REQUEST['title'] . ' <span id="contador"></span></h2>' . $body;
}
else {
  $body = '
    <h2>No aktibe projectio</h2>' . $body;
}

/**
 * HTML
 */
?>
<html>
  <head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <title>Reportes</title>
    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="jquery.countdown.pack.js"></script>
    <script type="text/javascript" src="jquery.countdown-es.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        var start = new Date(<?php echo implode(',', $time) ?>);
        $('#contador').countdown( {
          since: start,
          compact: true,
          format: 'DHMS',
          description: ''
        });
      });
    </script>
    <style type="text/css">
      * {
        font-family: Tahoma, Verdana, DejaVu-Sans;
      }
      body {
        margin: 10px;
        background-color: #adcbcb;
      }
      a {
        color: green;
      }
      a:hover {
        color: red;
      }
      .projectio {
        font-size: 18px;
        line-height: 28px;
        font-weight: bold;
      }
    </style>
  </head>
  <body>
    <?php echo $form ?>
    <?php echo $body ?>
  </body>
</html>