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
 * @details    Custom function for retrieve moodle data
 * @category   Moodle
 * @version    $Id: moodle.php 0 2012-03-16 12:50:34 $
 * @author     tecnocat
 * @file       /moodle.php
 * @date       2012-03-16 12:50:34
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 *
 */

/*******************************************************************************
 * Script config
 ******************************************************************************/
$server    = 'localhost';               // Database server
$user      = 'drupal';                  // Database user
$password  = 'drupal';                  // Database password
$database1 = 'moodledatabase';          // Database 1
$database2 = 'moodle2database';         // Database 2
$moodleurl = 'http://www.example.com/'; // URL to courses

/*******************************************************************************
 * Main logic
 ******************************************************************************/

/**
 * Return data from database
 */
if ($server AND $user AND $password AND $database1 AND $database2) {

  $link1 = db_open($server, $user, $password, $database1);
  $link2 = db_open($server, $user, $password, $database2);
  $query = "
    SELECT
      `mdl_grade_items`.`itemname` AS 'course',
      `mdl_course`.`enrolstartdate` AS 'start',
      `mdl_course`.`enrolenddate` AS 'end',
      '$moodleurl' AS moodleurl
    FROM `mdl_grade_items`
    INNER JOIN `mdl_course` ON `mdl_course`.`id` = `mdl_grade_items`.`courseid`
    WHERE `mdl_grade_items`.`itemname` IS NOT NULL
      AND `mdl_course`.`enrollable` = 1
      AND `mdl_course`.`enrolstartdate` != 0
      AND `mdl_course`.`enrolenddate` != 0
  ";
  $status = 'success';
  $result = array();
  foreach (db_fetch(db_query($link1, $query)) as $row) {
    $result[] = $row;
  }
  foreach (db_fetch(db_query($link2, $query)) as $row) {
    $result[] = $row;
  }
  db_close($link1);
  db_close($link2);
}
else {
  $status = 'error';
  $result = 'Missing configuration';
}

// Output and exit
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
die(_xml(array('status' => $status, 'result' => $result)));

/*******************************************************************************
 * Custom functions
 ******************************************************************************/

/**
 * Parse data object or array to XML Struct
 */
function _xml($data, $enclosure = 'XMLStructure', $tab = 0) {

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
      $xml .= _xml($val, $tag, $tab);
    }
  }
  else {
    $xml .= "$t  <![CDATA[$data]]>$n";
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
    $message = 'Connection with MySQL server needed before execute a SQL query.';
    throw new Exception($message);
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
