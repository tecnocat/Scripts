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
 * @details    Company migration script
 * @category   Company
 * @version    $Id: company.php 0 2012-02-22 09:14:34 $
 * @author     tecnocat
 * @file       /company.php
 * @date       2012-02-22 09:14:34
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 */

/**
 * Config for Script
 */
$server   = 'localhost';     // MySQL server
$user     = 'root';          // MySQL user
$password = 'intranet';      // MySQL password
$drupal5  = 'company_vieja'; // MySQL old database
$drupal6  = 'company_nueva'; // MySQL new database

// Prevent call to undefined function
if (!function_exists('mysqli_connect')) {
  die("\nERROR: Este servidor no tiene MySQLi, abortando...\n");
}
// Run
script();

/*******************************************************************************
 * Script functions
 ******************************************************************************/

/**
 * Script to execute migration for Company
 */
function script() {

  global $server, $user, $password, $drupal5, $drupal6;

  $string = array($drupal5, $drupal6, $user);
  $search = array(
    'DATABASE_PLACEHOLDER_DRUPAL5',
    'DATABASE_PLACEHOLDER_DRUPAL6',
    'DATABASE_PLACEHOLDER_USER',
  );

  e('Preparando base de datos NUEVA ' . $drupal6 . '...');
  $dump = 'DUMP.sql';
  $temp = 'COMPANY.sql';
  if (file_exists($temp)) {
    $data = "/**
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
 * @details    Company migration proccess
 * @category   Migration
 * @version    \$Id: Script.sql 0 2012-02-22 09:14:34 $
 * @author     tecnocat
 * @file       /Script.sql
 * @date       2012-02-22 09:14:34
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 */

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`DATABASE_PLACEHOLDER_DRUPAL6` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `DATABASE_PLACEHOLDER_DRUPAL6`;\n\n";
    $data .= file_get_contents($temp);
    $data  = str_replace($search, $string, $data);
    $fp    = fopen($dump, 'w');
    fwrite($fp, $data);
    fclose($fp);
    if (file_exists($dump)) {
      exec("mysql -u$user -p$password -h$server < $dump");
      unlink($dump);
    }
    else {
      die(e('ERROR: No se puede crear archivo necesario ' . $dump . ', abortando...'));
    }
  }
  else {
    die(e('ERROR: No se encuentra archivo necesario ' . $temp . ', abortando...'));
  }

  e('Abriendo base de datos VIEJA ' . $drupal5 . '...');
  $link5 = db_open($drupal5);
  e('Abriendo base de datos NUEVA ' . $drupal6 . '...');
  $link6 = db_open($drupal6);

  $file  = 'Script.sql';
  e('Preparando archivo de salida ' . $file . '...');

  if (file_exists($file)) {
    e('Archivo existente, BORRANDO...');
    unlink($file);
  }
  e('Abriendo archivo ' . $file . ' para escribir...');
  $fp   = fopen($file, 'w');
  $temp = 'SQL1.sql';
  if (file_exists($temp)) {
    $data = file_get_contents($temp);
    $data = str_replace($search, $string, $data);
    fwrite($fp, $data);
  }
  else {
    die(e('ERROR: No se encuentra archivo necesario ' . $temp . ', abortando...'));
  }

  e('Procesando palabras clave...');
  $data = "
-- Reset some terms
DELETE FROM `$drupal6`.`term_data` WHERE `term_data`.`vid` IN (6, 9, 15);
DELETE FROM `$drupal6`.`term_node`;

-- Copy terms";
  fwrite($fp, $data);

  $query = "SELECT * FROM `content_field_palabras_clave`";
  $rows  = db_fetch(db_query($link5, $query));

  foreach ($rows as $row) {

    extract($row);
    $tags = explode(',', $field_palabras_clave_value);

    foreach ($tags as $tag) {

      $tag    = trim($tag);
      $query  = "SELECT `tid` FROM `$drupal6`.`term_data` WHERE `vid` = '9' AND `name` = '$tag'";
      $result = db_fetch(db_query($link6, $query));

      if (!$result) {
        $query  = "INSERT INTO `$drupal6`.`term_data` (`vid`, `name`, `description`) VALUES ('9', '$tag', '');";
        fwrite($fp, "\n$query");
        db_query($link6, $query);
      }

      $query = "INSERT INTO `$drupal6`.`term_node` (`nid`, `vid`, `tid`) VALUES ('$nid', '$vid', (SELECT `tid` FROM `$drupal6`.`term_data` WHERE `vid` = '9' AND `name` = '$tag'));";
      fwrite($fp, "\n$query");
      db_query($link6, $query);
    }
  }

  $temp = 'SQL2.sql';
  if (file_exists($temp)) {
    $data = file_get_contents($temp);
    $data = str_replace($search, $string, $data);
    fwrite($fp, "\n\n" . $data);
  }
  else {
    die(e('ERROR: No se encuentra archivo necesario ' . $temp . ', abortando...'));
  }

  e('Procesando permisos antiguos...');
  $data = "
/*******************************************************************************
 * PERMISSIONS PROCCESS
 ******************************************************************************/
-- Reset all perms
TRUNCATE `$drupal6`.`acl`;
TRUNCATE `$drupal6`.`acl_node`;
TRUNCATE `$drupal6`.`acl_user`;
TRUNCATE `$drupal6`.`content_access`;
TRUNCATE `$drupal6`.`node_access`;
";
  fwrite($fp, "\n" . $data);
  $query = "
    SELECT `node`.`nid`, `term_node`.`tid`
    FROM `node`
    INNER JOIN `term_node` ON `term_node`.`nid` = `node`.`nid`
    WHERE `term_node`.`tid` BETWEEN 100 AND 102
    ORDER BY `node`.`nid` ASC
  ";
  $rows  = db_fetch(db_query($link5, $query));
  $aclid = 0;
  $data  = '-- Copy perms';

  foreach ($rows as $row) {

    extract($row);

    foreach (array('view', 'update', 'delete') as $state) {

      $aclid++;
      $grant_view   = ($state == 'view') ? 1 : 0;
      $grant_update = ($state == 'update') ? 1 : 0;
      $grant_delete = ($state == 'delete') ? 1 : 0;
      $data        .= "
INSERT INTO `$drupal6`.`acl` (`acl_id`, `module`, `name`) VALUES ('$aclid', 'content_access', '{$state}_{$nid}');
INSERT INTO `$drupal6`.`acl_node` (`acl_id`, `nid`, `grant_view`, `grant_update`, `grant_delete`) VALUES ('$aclid', '$nid', '$grant_view', '$grant_update', '$grant_delete');";
    }

    $roles = array(
      '4' => 'Servicios Centrales',
      '5' => 'Servicios Provinciales',
      '6' => 'Editor',
      '7' => 'Administrator',
    );

    // Default settings
    $permissions = array(
      'view'       => array(6, 7),
      'view_own'   => array(6, 7),
      'update'     => array(7),
      'update_own' => array(7),
      'delete'     => array(7),
      'delete_own' => array(7),
    );

    foreach ($roles as $rol => $dummy) {

      // 100 = Servicios Provinciales
      // 101 = Servicios Centrales
      // 102 = Ambos

      if ($tid == 100) {
        if ($rol == 4) {
          continue;
        }
        if (!in_array($rol, $permissions['view'])) {
          $permissions['view'][] = $rol;
        }
      }
      elseif ($tid == 101) {
        if ($rol == 5) {
          continue;
        }
        if (!in_array($rol, $permissions['view'])) {
          $permissions['view'][] = $rol;
        }
      }
      elseif ($tid == 102) {
        if (!in_array(4, $permissions['view'])) {
          $permissions['view'][] = 4;
        }
        if (!in_array(5, $permissions['view'])) {
          $permissions['view'][] = 5;
        }
      }

      $settings     = serialize($permissions);
      $state        = 'view'; // Maybe change this?
      $grant_view   = ($state == 'view') ? 1 : 0;
      $grant_update = ($state == 'update') ? 1 : 0;
      $grant_delete = ($state == 'delete') ? 1 : 0;
      $data        .= "
" . (($rol == 7) ? "INSERT INTO `$drupal6`.`content_access` (`nid`, `settings`) VALUES ('$nid', '$settings');\n" : '') . "INSERT INTO `$drupal6`.`node_access` (`nid`, `gid`, `realm`, `grant_view`, `grant_update`, `grant_delete`) VALUES ('$nid', '$rol', 'content_access_rid', '$grant_view', '$grant_update', '$grant_delete');";
    }
  }
  fwrite($fp, "\n" . $data);

  e('Procesando sistema de validación final...');
  $data = "
/*******************************************************************************
 * VALIDATION PROCCESS
 ******************************************************************************/
-- Check results
SELECT DISTINCT
  (SELECT COUNT(`users`.`name`) FROM `$drupal5`.`users`) AS 'Usuarios Vieja',
  (SELECT COUNT(`users`.`name`) FROM `$drupal6`.`users`) AS 'Usuarios Nueva',
  COUNT(v.`nid`) AS 'Contenidos Vieja',
  v.`type` AS 'Type',
  COUNT(n.`nid`) AS 'contenidos Nueva'
FROM `$drupal5`.`node` v
LEFT JOIN `$drupal6`.`node` n ON v.`type` = n.`type`
GROUP BY v.`nid`, v.`type`, n.`type`
ORDER BY COUNT(v.`type`) DESC;";
  fwrite($fp, "\n" . $data);

  e('Cerrando archivo ' . $file . '...');
  fclose($fp);
  e('Cerrando base de datos NUEVA ' . $drupal6 . '...');
  db_close($link6);
  e('Cerrando base de datos VIEJA ' . $drupal5 . '...');
  db_close($link5);
  e('Finalizado, ejecute el archivo ' . realpath($file) . ' con un gestor de MySQL.');
}

/**
 * Output function
 */
function e($message = '') {
  echo "\n$message\n";
}
/*******************************************************************************
 * MySQLi functions
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
