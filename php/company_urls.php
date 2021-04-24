<pre><?php

/**
 *
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
 * @details   Company file for update urls alias in database
 *
 * This file search all url alias that contain language location prefix and
 * remove for get the correct link without language prefix.
 *
 * This is the original file, all others are dummy copies, please delete it.
 *
 * @category   Tools
 * @version    $Id: company_urls.php 0 2011-12-14 15:35:34 $
 * @author     tecnocat
 * @file       /company_urls.php
 * @date       2011-12-14 15:35:34
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 *
 */

/*******************************************************************************
 * Variables
 ******************************************************************************/

/**
 * Debug
 */
$print = (isset($argv[1])) ? (($argv[1] == 'debug') ? true : false) : false;

/**
 * Config for MySQL
 */
$server   = 'mysqlserver';
$user     = 'drupal';
$password = 'drupal';
$db_info  = 'company_migra_devel';
$db_devel = 'company_migra_prod';

/**
 * Languages & Locations
 */
$languages = array('en', 'es', 'pt');
$locations = array('ar', 'br', 'pt');

/**
 * Main logic
 */
$info   = db_open($db_info);
$devel  = db_open($db_devel);
$query  = "SELECT * FROM `url_alias`";
$result = db_fetch(db_query($info, $query));
$count  = 0;
/*
echo "-- Rows for $db_info " . count($result) . "\n";

foreach ($result as $row) {

  extract($row);
  $query = "INSERT INTO `url_alias` (`pid`, `src`, `dst`) VALUES ($pid, '$src', '$dst')";

  db_query($devel, $query);

  if (mysqli_error($devel)) {
    $count++;
    echo "\n$count: $query <strong>ERROR: " . mysqli_error($devel) . '</strong>';
  }
}
*/
$query  = "SELECT * FROM `url_alias`";
$result = db_fetch(db_query($devel, $query));
$count  = 0;

foreach ($languages as $language) {

  foreach ($locations as $location) {

    $prefix = $language . '-' . $location . ' - ';

    foreach ($result as $row) {

      extract($row);
      $dst = str_replace($prefix, '', $dst);
      $query = "UPDATE `url_alias` SET `dst` = '$dst' WHERE `pid` = $pid";

      db_query($devel, $query);

      if (mysqli_error($devel)) {
        $count++;
        echo "\n$count: $query <strong>ERROR: " . mysqli_error($devel) . '</strong>';
      }
    }
  }
}

db_close($info);
db_close($devel);

/*******************************************************************************
 * MySQLi Functions
 ******************************************************************************/

/**
 * Connect to database
 */
function db_open($database) {

  global $server, $user, $password;

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
