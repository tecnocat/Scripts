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
 * @details    Custom webservice
 * @category   Tools
 * @version    $Id: webservice.php 0 2012-04-12 12:47:34 $
 * @author     tecnocat
 * @file       /webservice.php
 * @date       2012-04-12 12:47:34
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * This is the original script before the ninja eaten and then it pooped it
 *
 * Fucked Bitelchus Córdoba! Then they asked me how it worked the script!
 */

// EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE
$_POST['xml'] = '<?xml version="1.0" encoding="UTF-8"?>
<xml>
  <year>' . rand(2010, 2012) . '</year>
  <mes>' . rand(1, 12) . '</mes>
  <nif>123456789W</nif>
  <api_token>34e34a9280a7c3ee8380d9d0fe0954b5</api_token>
  <informevim><![CDATA[' . utf8_encode(file_get_contents('http://localhost')) . ']]></informevim>
</xml>';
// EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE

/**
 * MySQL server configuration, change this to proper values
 *
 * Don't try this, only work inside a VPN, sorry
 */
$server   = '213.229.162.171';
$user     = 'root';
$password = 'fnac.es';
$database = 'fnac_rrhh';

// API Tokens, generate with md5(uniqid(null, true))
$api_tokens = array(
  '34e34a9280a7c3ee8380d9d0fe0954b5',
  '72bde163b1b9b8da51b44013f9c2221a',
  '72a0425c2dbc23fb451e0b89666483a3',
  'd725e3300a8158b18c26fe0a95af56c3',
  '4e45e0510d1dd3bb7b444ddc12023ce5',
  '489c2825cdd8b49c4796d61d66f0660e',
  'd91972432fe0552b642253815a650c54',
  'f9d3cefcbc50d626b3415d37caa2de43',
  '675bb9cf458a7d8fabcfd6b4fb81652d',
  'ebe82b76c23dc2fbcd0601473fc3012a',
);

// FileSystem paths
$input_dir = '/home/user/workspace/webservice'; // Without the trailing slash!

/**
 * Web service main process
 */
webservice();

/**
 * Function to parse received data
 */
function webservice() {

  if (sizeof($_POST)) {

    $data = simplexml_load_string($_POST['xml'], 'SimpleXMLElement', LIBXML_NOCDATA);

    // Input data
    if (isset($data->nif)) {
      $xml = webservice_input($data);
    }
    // Output data
    else {
      $xml = webservice_output($data);
    }

    if ($xml) {
      header('Content-type: text/xml');
      die($xml);
    }
  }
}

/**
 * Function to input data
 */
function webservice_input($xml) {

  global $api_tokens, $input_dir;

  $year  = $xml->year;
  $month = $xml->mes;
  $nif   = $xml->nif;
  $token = $xml->api_token;
  $html  = $xml->informevim;

  if (in_array($token, $api_tokens)) {

    if (!recursive_mkdir($input_dir, 0775)) {
      $return  = 'The system can not create the necessary structure folder ';
      $return .= $path . ' due to permission restrictions, please change the ';
      $return .= 'permissions to allow a web server to use mkdir.';
    }
    else {
      $file = $input_dir . '/' . $year . '-' . $month . '-' . $nif . '.html';
      if ($fp = fopen($file, 'w')) {
        fwrite($fp, $html);
        fclose($fp);
        $return = 'OK';
      }
      else {
        $return = 'Error';
      }
    }

    return _xml(array('respuesta' => $return), 'xml');
  }
}

/**
 * Function to output data
 */
function webservice_output($xml) {

  global $api_tokens;

  $year  = $xml->year;
  $month = $xml->mes;
  $token = $xml->api_token;

  if (in_array($token, $api_tokens)) {

    global $server, $user, $password, $database;

    $link   = db_open($server, $user, $password, $database);
    $query  = "
      SELECT
        `fnac_objetivos_vim`.`estado` AS resultado,
        `hs_hr_employee`.`emp_other_id` AS nif
      FROM `fnac_objetivos_vim`
      INNER JOIN `hs_hr_employee` ON `fnac_objetivos_vim`.`emp_number` = `hs_hr_employee`.`employee_id`
      WHERE `fnac_objetivos_vim`.`mes` = '$month'
        AND `fnac_objetivos_vim`.`ano` = '$year'
    ";
    $matches = db_fetch(db_query($link, $query));
    db_close($link);

    $return  = array(
      'mes' => (int)$month,
      'year' => (int)$year,
      'registros' => sizeof($matches),
      'datos' => array(),
    );

    foreach ($matches as $match) {

      $return['datos']['registro' . (sizeof($return['datos']) + 1)] = array(
        'nif' => $match['nif'],
        'resultado' => $match['resultado'],
      );
    }

    return _xml($return, 'xml');
  }
}

/**
 * Function to implement recursive mkdir
 */
function recursive_mkdir($path, $mode = 0775) {

  foreach (explode('/', $path) as $dir) {

    if (!empty($dir)) {

      $fullpath = $fullpath . '/' . $dir;

      if (!is_dir($fullpath)) {
        if (!@mkdir($fullpath, $mode)) {
          return false;
        }
      }
    }
  }

  return true;
}

/**
 * Parse data object or array to XML Struct
 */
function _xml($data, $enclosure = 'XMLStructure', $dat = false, $tab = 0) {

  $n   = "\n";
  $t   = str_repeat('  ', $tab++);
  $xml = "$t<$enclosure>$n";

  if (is_object($data)) {
    $data = (array) $data;
  }
  if (is_array($data)) {

    foreach ($data as $tag => $val) {

      if (is_numeric($tag)) {
        $tag = 'item';
      }
      $xml .= _xml($val, $tag, $dat, $tab);
    }
  }
  else {
    if ($dat) {
      $xml .= "$t  <![CDATA[$data]]>$n";
    }
    else {
      $xml .= $t . '  ' . $data . $n;
    }
  }

  $t    = str_repeat('  ', --$tab);
  $xml .= "$t</$enclosure>$n";

  return $xml;
}

/*******************************************************************************
 * MySQLi functions
 ******************************************************************************/

/**
 * Connect to database
 */
function db_open($server, $user, $password, $database) {

  $link = mysqli_connect($server, $user, $password, $database);

  if (mysqli_connect_errno()) {
    throw new Exception('Connection failed: ' . mysqli_connect_error());
  }
  else {
    mysqli_query($link, 'SET NAMES utf8');
  }

  return $link;
}

/**
 * Execute query in database
 */
function db_query($link, $query) {

  if (!isset($link)) {
    throw new Exception('Connection with MySQL server needed before execute a SQL query.');
  }
  else {
    return mysqli_query($link, $query);
  }
}

/**
 * Fetch a db query result in array associative
 */
function db_fetch($data) {

  $rows = array();

  if ($data) {
    while ($fetched = mysqli_fetch_assoc($data)) {
      $rows[] = $fetched;
    }
    mysqli_free_result($data);
  }

  return $rows;
}

/**
 * Disconnect from database
 */
function db_close($link) {
  mysqli_close($link);
}
