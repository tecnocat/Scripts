<?php

$size = 0;
$max  = 5120; // How much MB create?
$max  = $max * 1024;
while ($size < $max) {

  echo "\nCurrent size: " . round($size / 1024) . 'MB ';

  for ($i = 0; $i < 128; $i++) {
    echo '.';
    $directory  = '/home/tecnocat/workspace/dropbox/';
    $directory .= rand(1982, date('Y'));
    $extension  = (rand(0,1)) ? 'jpg' : 'png';
    generate_image($directory, $extension, '720x480', '7680x4320');
    $size += array_shift(explode("\t", exec("du -s $directory")));
  }


}

echo "\nReached size: " . round($size / 1024) . "MB\n\n";

function generate_image($directory, $extension = 'png', $min_resolution, $max_resolution) {

  if (!is_dir($directory)) {
    mkdir($directory);
  }

  $destination = $directory . '/' . uniqid('IMG_', true) . '.' . $extension;

  $min = explode('x', $min_resolution);
  $max = explode('x', $max_resolution);

  $width = rand((int)$min[0], (int)$max[0]);
  $height = rand((int)$min[1], (int)$max[1]);

  // Make an image split into 4 sections with random colors.
  $im = imagecreate($width, $height);
  for ($n = 0; $n < 4; $n++) {
    $color = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
    $x = $width/2 * ($n % 2);
    $y = $height/2 * (int) ($n >= 2);
    imagefilledrectangle($im, $x, $y, $x + $width/2, $y + $height/2, $color);
  }

  // Make a perfect circle in the image middle.
  $color = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
  $smaller_dimension = min($width, $height);
  $smaller_dimension = ($smaller_dimension % 2) ? $smaller_dimension : $smaller_dimension;
  imageellipse($im, $width/2, $height/2, $smaller_dimension, $smaller_dimension, $color);

  $save_function = 'image'. ($extension == 'jpg' ? 'jpeg' : $extension);
  $save_function($im, $destination);

  $images[$extension][$min_resolution][$max_resolution][$destination] = $destination;
}
