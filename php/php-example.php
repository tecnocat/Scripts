<?php

/**
 * @file
 * Dummy PHP code example for syntax hightlighting
 *
 * List of types and HTML HEX colors:
 *
 * Normal Text        #FF8000
 * PHP Text           #C3C3C3
 * Function           #8E79A5
 * Keyword            #E8E800
 * Control Structures #78B753
 * Loops              #FF8000
 * Return             #FF0000
 * Special methods    #1E90FF
 * Magic Constants    #1E90FF
 * Super Variables    #FF8000
 * Variables          #00C0C0
 * Decimal            #C000C0
 * Octal              #FF8000
 * Hex                #00C000
 * Float              #C000C0
 * String             #E85848
 * Booleans           #FF8000
 * Comment            #7F7F7F
 * Backslash Code     #E8E800
 * Other              #78B753
 */

echo <<<DUMMY
  this is a normal text string
DUMMY;
include "pachin pachi";
function my_function($args = array()) {

  $file  = __FILE__;
  $query = $_GET['q'];
  $mierda = array(1, 2, 'coños');
  foreach (explode('/', $query) as $arg) {

    echo "\nmierdas putas";
    echo 'mierda coño $variable';
    if ($arg === TRUE) {
      $args = 0;
      break;
    }
    else {
      $args[] = "\n" . $arg;
    }
  }
}
echo my_function();
$m = new Mongo();

class MyClass() {

  function __construct() {
        $fock::parent();
    $this->loaded = FALSE;
    $this->trunk  = 0x08fb8a;
  }

  function turn_on() {

    parent::Execute();

    return TRUE;
  }
}
