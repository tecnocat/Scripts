<?php

global $base_url;

$data = array();
$html = $node->body;
preg_match_all('/<img[^>]+>/i', $html, $matches);

foreach ($matches[0] as $img) {
  preg_match_all('/(alt|title|src)=("[^"]*")/i', $img, $data[$img]);
}

foreach ($data as $img => $img_data) {

  $src    = str_replace('"', '', $img_data[2][0]);
  $alt    = str_replace('"', '', $img_data[2][1]);
  $title  = str_replace('"', '', $img_data[2][2]);
  $size   = getimagesize(str_replace($base_url, '', $src));
  $width  = $size[0];
  $height = $size[1];
  $span   = "<span style='width: $width;'>$alt</span>";
  $html   = str_replace($img, $img . $span, $html);
}

$node->body = $html;