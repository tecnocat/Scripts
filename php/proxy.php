<?php

/**
 * tecnocat
 *
 * @section LICENSE
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @details    Proxy to avoid cross domain AJAX restriction and with file cache
 * @category   Proxy
 * @version    $Id: proxy.php 0 2012-03-22 17:28:34 $
 * @author     tecnocat
 * @file       /proxy.php
 * @date       2012-03-22 17:28:34
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 *
 */

extract(parse_url($_GET['url']));
$valid = 'example.com';                               // Valid from this domain
$test  = substr($host, strlen($valid) * -1);          // Get domain
$url   = $scheme . '://' . $host . $path;             // Build the URL
$type  = ($_GET['type'] != 'html') ? 'xml' : 'html';  // Allowed return formats
$temp  = sys_get_temp_dir();                          // Obviously...
$file  = $temp . '/proxycache-' . md5($url);          // Store request in cache
$time  = (60 * 60 * 24);                              // Cache expiration, 1 day

if (isset($url) AND isValidURL($url) AND $test == $valid) {

  if (is_file($file) AND ((time() - filemtime($file)) < $time)) {
    $data = file_get_contents($file);
  }
  else {
    $fp   = fopen($file, 'w');
    $data = file_get_contents($url);
    fwrite($fp, $data);
    fclose($fp);
  }

  header("Content-type: text/$type");
  die($data);
}

function isValidURL($url = null) {
  return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}