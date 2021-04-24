<?php

$len = 100000;
$min = 0;
$max = 99;

echo "\nTrying with $len iterations...";

$t = (int) (microtime(true) * 0xFFFF);

$a = array();
srand($t);

for ($i = 0; $i < $len; $i++) {
  $a[$i] = rand($min, $max);
}

$b = array();
srand($t);

for ($i = 0; $i < $len; $i++) {
  $b[$i] = rand($min, $max);
}

for ($i = 0; $i < $len; $i++) {
  if ($a[$i] !== $b[$i] ) {
    die('Pseudo-random sequence borked at #' . $i . 'th iteration!');
  }
}

echo "\n\n\tYour pseudo-random sequencer is working correctly.\n\n";
exit(0);