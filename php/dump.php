<?php /* UTF-8 Verified (áéíóú) */

//$demo = getvalue();
//$demo = get_defined_vars();
//echo dump($demo);
//dump2($demo);

function getvalue($deep = 10, $loop = false) {
  $return = array();
  if ($loop) {
    switch (rand(1,7)) {
      case 1: // array
        return array('array' => array('key' => 'value'));
      case 2: // boolean
        return (rand(0,1)) ? true : false;
      case 3: // integer
        return rand(1,9999999);
      case 4: // float
        return (float)(rand(1,9999999)/7);
      case 5: // object
        return (object)(array('object' => array('key' => 'value')));
      case 6: // null
        return null;
      case 7: // string
        return "This dump it's best that var_dump!";
    }
  }
  for ($i = 1; count($return) <= $deep; $i++) {
    $return[] = getvalue($i, true);
  }
  return $return;
}

/**
 * New look for best debugging var_dump
 */
function dump(&$variable, $loop = false, $encoding = 'ISO-8859-15') {
  $backup = $variable;
  $variable = $seed = md5(uniqid() . rand());
  $name = false;
  $variable_name = 'unknown';
  foreach ($GLOBALS as $key => $value) {
    if ($value === $seed) { $variable_name = $key; }
  }
  $variable = $backup;
  $html = '';
  $color = array(
    'array' => 'dummy',
    'boolean' => '#92008d',
    'integer' => '#ff0000',
    'double' => '#0099c5',
    'float' => '#0099c5',
    'object' => 'dummy',
    'NULL' => '#0000ff',
    'string' => '#008000',
  );
  $td_style = 'font: 9pt sans-serif; padding-top: 1px; padding-bottom: 1px; padding-left: 5px; padding-right: 5px; border-right: solid 1px #cccccc; border-bottom: solid 1px #cccccc;';
  $table_style = '
    font: 9pt sans-serif;
    text-align: left;
    display: block;
    background: white;
    color: black;
    border-top: solid 1px #ccc;
    border-left: solid 1px #ccc;
    margin: 25px;';
  $type = gettype($variable);
  $style = 'color: ' . $color[$type] . '; font-weight: normal; ' . $td_style;
  switch ($type) {
    case 'array':
    case 'object':
      $html .= ($loop ? "<td style='$td_style'>" : "<table style='$table_style' cellspacing='0' cellpadding='2'><tr><td style='$td_style' valign='top'><strong>$$variable_name</strong></td><td style='$td_style' valign='top'>$type (" . count($variable) . ")</td><td style='$td_style'>");
      $html .= '<table style="font: 9pt sans-serif; border-top: solid 1px #cccccc; border-left: solid 1px #cccccc;; margin: 2px;" cellspacing="0" cellpadding="2">';
      foreach ($variable as $key => $value) {
        if ($key === 'GLOBALS') { continue; } // avoid *RECURSION*
        $html .= '<tr><td style="font: 9pt sans-serif; padding-top: 1px; padding-bottom: 1px; padding-left: 5px; padding-right: 5px; border-right: solid 1px #cccccc; border-bottom: solid 1px #cccccc;" valign="top"><b>'.str_replace("\x00", ' ', $key).'</b></td>';
        $html .= '<td style="font: 9pt sans-serif; padding-top: 1px; padding-bottom: 1px; padding-left: 5px; padding-right: 5px; border-right: solid 1px #cccccc; border-bottom: solid 1px #cccccc;" valign="top">'.gettype($value);
        if (is_array($value) OR is_object($value)) {
          $html .= '&nbsp;('.count($value).')';
        } elseif (is_string($value)) {
          $html .= '&nbsp;('.strlen($value).')';
        }
        $html .= '</td>'.dump($value, true, $encoding).'</tr>';
      }
      $html .= '</table>';
      $html .= ($loop ? '</td>' : '</td></tr></table>');
      break;

    case 'boolean':
      $html .= ($loop ? '<td style="' . $style . '">' : '').($variable ? 'TRUE' : 'FALSE').($loop ? '</td>' : '');
      break;

    case 'integer':
      $html .= ($loop ? '<td style="' . $style . '">' : '').$variable.($loop ? '</td>' : '');
      break;

    case 'double':
    case 'float':
      $html .= ($loop ? '<td style="' . $style . '">' : '').$variable.($loop ? '</td>' : '');
      break;

    case 'objectoooo':
      $html .= ($loop ? '<td style="' . $style . '">' : '').dump($variable, true, $encoding).($loop ? '</td>' : '');
      break;

    case 'string':
      $variable = str_replace("\x00", ' ', $variable);
      $varlen = strlen($variable);
      for ($i = 0; $i < $varlen; $i++) {
        $html .= htmlentities($variable{$i}, ENT_QUOTES, $encoding);
      }
      $html = ($loop ? '<td style="' . $style . '">' : '').nl2br($html).($loop ? '</td>' : '');
      break;

    case 'NULL':
      $html .= ($loop ? '<td style="' . $style . '">' : '').'NULL'.($loop ? '</td>' : '');
      break;

    default:
      $html .= ($loop ? '<td style="' . $style . '">' : '').nl2br(htmlspecialchars(str_replace("\x00", ' ', $variable))).($loop ? '</td>' : '');
      break;
  }
  return $html;
}


/*
 * Best debugging var_dump
 */
function dump2(&$variable, $info = false) {
  $backup = $variable;
  $variable = $seed = md5(uniqid() . rand());
  $variable_name = 'unknown';
  foreach ($GLOBALS as $key => $value) {
    if ($value === $seed) { $variable_name = $key; }
  }
  $variable = $backup;

  echo '<pre style="
    font: 9pt sans-serif;
    text-align: left;
    margin: 25px;
    display: block;
    background: white;
    color: black;
    border:1px solid #ccc;
    padding:5px;
    margin: 25px;
    font-size: 11px;
    line-height: 14px;
  ">';

  $info = ($info) ? $info : '$' . $variable_name;
  echo '<b style="color:red;">' . $info . ':</b><br>';
  do_dump($variable, '$' . $variable_name);
  echo '<b style="color:red;">End ' . $info . '</b></pre>';
}
function do_dump(&$dump, $vname = NULL, $tab = NULL, $ref = NULL) {
  $md5 = md5(rand() . rand() . rand() . rand() . rand() . rand());
  $ntab = 8;
  $bat = '<span style="color:#ccc;">|</span><a style="text-decoration:none;" href="#' . $md5 . '" alt="' . $vname . '" title="' . $vname . '">' . str_repeat('&nbsp;',$ntab) . '</a>';
  $ref = $ref . $vname;
  $kvar = 'the_do_dump_recursion_protection_scheme';
  $kname = 'referenced_object_name';

  if (is_array($dump) && isset($dump[$kvar])) {
    $rvar = &$dump[$kvar];
    $rname = &$dump[$kname];
    $type = ucfirst(gettype($rvar));
    echo $tab . '<a name="' . $md5 . '">' . $vname . '</a><span style="color:#a2a2a2">' . $type . '</span> = <span style="color:#e87800;">&amp;' . $rname . '</span><br>';
  }
  else {
    $dump = array($kvar => $dump, $kname => $ref);
    $avar = &$dump[$kvar];

    $type = ucfirst(gettype($avar));
    $span = '&nbsp;<span style="color:#a2a2a2;">';
    $color = array(
      'Array' => 'dummy',
      'Object' => 'dummy',
      'String' => 'green',
      'Integer' => 'red',
      'Double' => '#0099c5',
      'Boolean' => '#92008d',
      'NULL' => 'blue',
    );
    $tc = '<span style="color:' . $color[$type] . ';">';
    $cs = '&nbsp;</span>';
    $bs = '</span><br />';
    switch (gettype($avar)) {
      case 'object':
        echo $tab . '<a name="' . $md5 . '">' . $vname . $span . $type . '(' . count($avar) . ')</span><br>' . $tab . '(<br>';
        foreach($avar as $name=>$value) { do_dump($value, '[' . $name . '] ', $tab . $bat, $ref); }
        echo $tab . ')<br>';
        break;

      case 'array':
        echo $tab . '<a name="' . $md5 . '">' . ($vname ? $vname . '=>':'') . $span . $type . '(' . count($avar) . ')</span><br>' . $tab . '(<br>';
        $keys = array_keys($avar);
        foreach($keys as $name) {
          $value = &$avar[$name];
          do_dump($value, '[\'' . $name . '\'] ', $tab . $bat, $ref);
        }
        echo $tab . ')<br>';
        break;
      case 'string': echo $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $tc . '"' . dtags($avar) . '"' . $bs; break;
      case 'integer': echo $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $tc . $avar . $bs; break;
      case 'double': $type = 'Float'; echo $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $tc . $avar . $bs; break;
      case 'boolean': echo $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $tc . ($avar == 1 ? 'true':'false') . $bs; break;
      case 'NULL': echo $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $tc . 'null' . $bs; break;
      default: echo $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $avar . '<br>'; break;
    }
    $dump = $dump[$kvar];
  }
}
function dtags($tags) {
  $search = array('<','>',"\n","\r","\n\r","\r\n","\t");
  $string = array('&#60;','&#62;','\n','\r','\n\r','\r\n','\t');
  return str_replace($search,$string,$tags);
}
