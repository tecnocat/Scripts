<?php

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
 * @details    Company file for migration databases
 *
 * This file sync Company databases production to develop given priority on the
 * production database in certain tables, we customize a basic and general script
 * that may be (posible) can use it on other projects to make your life easy ;-)
 *
 * This is the original file, all others are dummy copies, please delete it.
 *
 * @category   Tools
 * @version    $Id: company_sync.php 0 2011-12-01 13:37:34 $
 * @author     tecnocat
 * @file       /company_sync.php
 * @date       2011-12-01 13:37:34
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
$server     = 'localhost';
$user       = 'drupal';
$password   = 'drupal';
$produccion = 'company_migra_prod';
$desarrollo = 'company_migra_devel';

/**
 * Logs
 */
$basedir = 'SQL_OUTPUT';
if (!is_dir($basedir)) {
  mkdir($basedir);
}
system("rm -rf" . ($print ? 'v' : '') . " $basedir/*");
$log_common = "$basedir/COMMON.sql";
$log_insert = "$basedir/INSERT.sql";
$log_update = "$basedir/UPDATE.sql";

/**
 * SQL Statements
 */
$INSERT  = array();
$UPDATE  = array();
$inserts = 0;
$updates = 0;

/**
 * Common variables
 */
$tables_processed = 0;
$tables_skipped   = 0;

/*******************************************************************************
 * Main logic
 ******************************************************************************/

// Conexiones a las bases de datos de schema, produccion y desarrollo
$info  = db_open('information_schema');
$prod  = db_open($produccion);
$devel = db_open($desarrollo);

// Sacamos todas las tablas y columnas para poder filtrar
$query  = "SELECT * FROM `COLUMNS` WHERE `TABLE_SCHEMA` = '$produccion'";
$schema = db_fetch(db_query($info, $query));
$query  = "SELECT * FROM `STATISTICS` WHERE `TABLE_SCHEMA` = '$produccion' AND `NON_UNIQUE` = '0'";
$unique = db_fetch(db_query($info, $query));

// Si no hay datos Excepción
if (!count($schema)) {
  throw new Exception("ERROR: no existen columnas para la base de datos: $produccion");
}

// Recojemos los datos de las tablas para despues procesarlos
$tablas = array();

foreach ($schema as $rid => $row) {

  unset($TABLE_NAME, $COLUMN_NAME, $COLUMN_KEY);
  extract($row);

  $tablas[$TABLE_NAME][$COLUMN_NAME] = $COLUMN_KEY;
}

// Cambiamos los campos que tengan MUL a PRI si son únicos
foreach ($unique as $rid => $row) {

  unset($TABLE_NAME, $COLUMN_NAME);
  extract($row);

  if (isset($tablas[$TABLE_NAME][$COLUMN_NAME])
  AND $tablas[$TABLE_NAME][$COLUMN_NAME] != 'PRI') {
    $tablas[$TABLE_NAME][$COLUMN_NAME] = 'UNI';
  }
}

// Colocamos en cada lugar lo que corresponde para su posterior acceso
foreach ($tablas as $tabla => $datos) {

  foreach ($datos as $dato => $clave) {

    switch ($clave) {

      case 'PRI': $tablas[$tabla]['PRIMARY_KEYS'][$dato] = $dato; break;
      case 'UNI': $tablas[$tabla]['UNIQUES_KEYS'][$dato] = $dato; break;
      case 'MUL': $tablas[$tabla]['INDEXES_KEYS'][$dato] = $dato; break;
      default:    $tablas[$tabla]['COLUMNS_KEYS'][$dato] = $dato; break;
    }

    unset($tablas[$tabla][$dato]);
  }
}

// Excluimos algunas tablas que no nos interesa tocar
$tablas_vid = array();

foreach ($tablas as $tabla => $data) {

  foreach ($data as $name => $fields) {

    if ($name == 'PRIMARY_KEYS') {
      if (in_array('vid', $fields)) {
        $tablas_vid[$tabla] = $tabla;
      }
    }
  }
}

if (count($tablas_vid)) {

  $tablas_vid += array(
    'files'                => 'files',
    'history'              => 'history',
    'locales_target'       => 'locales_target',   // This table can cause errors
    'localizernode'        => 'localizernode',    // This table can cause errors
    'menu_productos'       => 'menu_productos',
    'node'                 => 'node',
    'node_access'          => 'node_access',
    'node_revisions'       => 'node_revisions',
    'node_type'            => 'node_type',
    'orden_sectores'       => 'orden_sectores',
    'search_dataset'       => 'search_dataset',   // This table must be TRUNCATE
    'search_index'         => 'search_index',     // This table must be TRUNCATE
    'search_total'         => 'search_total',
    'swish_fulltext'       => 'swish_fulltext',
    'term_access'          => 'term_access',
    'term_access_defaults' => 'term_access_defaults',
    'term_data'            => 'term_data',
    'term_hierarchy'       => 'term_hierarchy',
    'term_node'            => 'term_node',        // This table can cause errors
    'term_node_site'       => 'term_node_site',   // This table can cause errors
    'url_alias'            => 'url_alias',
  );
}
else {
  throw new Exception('ERROR: No se han recibido datos de las tablas con PRIMARY_KEYS');
}

asort($tablas_vid);
$tables = $tablas_vid;

foreach ($tables as $table) {

  $query  = "SELECT * FROM `$table`";
  $fields = db_fetch(db_query($prod, $query));

  if (empty($fields)) {

    if ($print) {
      echo "\n\n\tSkiping empty table: $table";
    }

    $tables_skipped++;
    continue;
  }

  if ($table == 'search_dataset' OR $table == 'search_index') {
    db_query($devel, "TRUNCATE $table");
  }

  if ($print) {
    echo "\n\n\tProcessing table: $table -> ";
  }

  $tables_processed++;
  $U = $I = 0;

  foreach ($fields as $field) {

    if (row_exist($table, $field)) {
      company_update($table, $field);
    }
    else {
      company_insert($table, $field);
    }
  }

  if ($print) {
    echo "\n\t(UPDATES: " . $U . ', INSERTS: ' . $I . ', TOTAL: ' . ($U + $I) . ")\n";
  }
}

// Guardamos primero los UPDATES ...
$count    = 0;
$progress = '';

if ($print AND count($UPDATE)) {
  echo "\n\n\tProcesing UPDATE statements ... 0%";
}

foreach ($UPDATE as $datos) {

  $count++;
  $percent = number_format(($count / count($UPDATE)) * 100, 0);

  if ($progress != $percent) {

    $progress = $percent;

    if ($print) {
      echo " ... $progress%";
    }
  }

  extract($datos);
  error_log("$query;\n", 3, $log_common);
  error_log("$query;\n", 3, str_replace('.', ".$table.", $log_common));
}

// Guardamos después los INSERTS ...
$count = 0;

if ($print AND count($INSERT)) {
  echo "\n\n\tProcesing INSERT statements ... 0%";
}

foreach ($INSERT as $datos) {

  $count++;
  $percent = number_format(($count / count($INSERT)) * 100, 0);

  if ($progress != $percent) {
    $progress = $percent;

    if ($print) {
      echo " ... $progress%";
    }
  }

  extract($datos);
  error_log("$query;\n", 3, $log_common);
  error_log("$query;\n", 3, str_replace('.', ".$table.", $log_common));
}

$commons     = $inserts + $updates;
$inserts     = str_repeat(' ', (8 - strlen($inserts))) . $inserts;
$updates     = str_repeat(' ', (8 - strlen($updates))) . $updates;
$commons     = str_repeat(' ', (8 - strlen($commons))) . $commons;
$SQL_INSERTS = (is_file($log_insert)) ? bytes(filesize($log_insert)) : 'None';
$SQL_UPDATES = (is_file($log_update)) ? bytes(filesize($log_update)) : 'None';
$SQL_COMMONS = (is_file($log_common)) ? bytes(filesize($log_common)) : 'None';

echo "
\n\tScript completed.
\n
\tSQL INSERTS:   $inserts ($SQL_INSERTS)
\tSQL UPDATES: + $updates ($SQL_UPDATES)
\t             ----------
\tSQL SUMMARY: = $commons ($SQL_COMMONS)
\t
\tTables processed: $tables_processed
\tTables skipped:   $tables_skipped
\n
";

db_close($info);
db_close($prod);
db_close($devel);


/*******************************************************************************
 * Functions
 ******************************************************************************/

/**
 * Test if row exists in target
 */
function row_exist($table, $data) {

  global $devel, $tablas;

  $ids = array(
    'PRIMARY_KEYS' => false,
    'UNIQUES_KEYS' => false,
    'INDEXES_KEYS' => false,
    'COLUMNS_KEYS' => false,
  );
  extract($ids);

  // Buscamos la row por todas sus claves una a una hasta encontrarla
  foreach ($ids as $id => $dummy) {

    $keys = keys($table, $id);

    if ($keys) {

      $$id = true;
      // Si esta vuelta es INDEXES_KEYS o COLUMNS_KEYS y no hemos encontrado nada
      // con PRIMARY_KEYS o UNIQUES_KEYS o estos ultimos no existen en la tabla
      // podemos dar perfectamente por hecho que no existe row.
      if ((($INDEXES_KEYS OR $COLUMNS_KEYS) AND ($PRIMARY_KEYS OR $UNIQUES_KEYS))
      // Si es la vuelta en la que buscamos por COLUMNS_KEYS y ya hemos buscado
      // por INDEXES_KEYS sin encontrar nada, y ademas, en las vueltas de antes
      // con PRIMARY_KEYS o UNIQUES_KEYS tampoco encontramos nada no existe row.
      OR ($INDEXES_KEYS AND $COLUMNS_KEYS AND !$PRIMARY_KEYS AND !$UNIQUES_KEYS)
      // El mismo caso que el anterior pero sin que existan solo PRIMARY_KEYS
      OR ($INDEXES_KEYS AND $COLUMNS_KEYS AND !$PRIMARY_KEYS AND $UNIQUES_KEYS)
      // El mismo caso que el anterior pero sin que existan solo UNIQUES_KEYS
      OR ($INDEXES_KEYS AND $COLUMNS_KEYS AND  $PRIMARY_KEYS AND !$UNIQUES_KEYS)
      // O también si hemos llegado a la vuelta COLUMNS_KEYS y no encontramos
      // nada en PRIMARY_KEYS ni UNIQUES_KEYS ni INDEXES_KEYS no existe row
      OR (!$INDEXES_KEYS AND $COLUMNS_KEYS AND !$PRIMARY_KEYS AND !$UNIQUES_KEYS)
      // El mismo caso que el anterior pero sin que existan solo PRIMARY_KEYS
      OR (!$INDEXES_KEYS AND $COLUMNS_KEYS AND !$PRIMARY_KEYS AND $UNIQUES_KEYS)
      // El mismo caso que el anterior pero sin que existan solo UNIQUES_KEYS
      OR (!$INDEXES_KEYS AND $COLUMNS_KEYS AND  $PRIMARY_KEYS AND !$UNIQUES_KEYS)) {
        return false;
      }

      $WHERE  = where($keys, $data);
      $query  = "SELECT * FROM `$table` WHERE $WHERE";
      $result = db_fetch(db_query($devel, $query));

      if ($result) {
        return true;
      }
    }
  }

  // Si no encontramos row es que no existe
  return false;
}

/**
 * Execute a SQL query to INSERT
 */
function company_insert($table, $data) {

  global $devel, $tablas, $I;

  if (!$data) {
    print_r(get_defined_vars());
    throw new Exception("ERROR: No existen parámetros para INSERT en $table");
  }

  $VALUES = values($data);
  $FIELDS = fields($data);

  $query  = "INSERT INTO `$table` ($FIELDS) VALUES ($VALUES)";
  sql($query, $table, 'INSERT');
  $I++;
}

/**
 * Execute a SQL query to UPDATE
 */
function company_update($table, $data) {

  //SELECT * FROM node WHERE nid >= 4490 AND nid <= 4541 ORDER BY nid DESC LIMIT 0, 2500;

  global $devel, $tablas, $U;

  if (!count($data)) {
    print_r(get_defined_vars());
    throw new Exception("ERROR: No existen parámetros para UPDATE en $table");
  }

  $ids = array(
    'PRIMARY_KEYS' => false,
    'UNIQUES_KEYS' => false,
    'INDEXES_KEYS' => false,
    'COLUMNS_KEYS' => false,
  );

  // Buscamos la row por todas sus claves una a una hasta encontrarla
  foreach ($ids as $id => $dummy) {

    $keys = keys($table, $id);

    // Si la tabla tiene claves por las cuales buscar
    if ($keys) {

      // Hacemos otra consulta por dichas claves para buscar algun resultado
      $WHERE  = where($keys, $data);
      $query  = "SELECT * FROM `$table` WHERE $WHERE";
      $result = db_fetch(db_query($devel, $query));

      // Si hay resultado buscamos realmente que datos hay que cambiar y paramos
      if ($result) {

        // Por cada row obtenida (si no es PRIMARY_KEYS o UNIQUES_KEYS)
        foreach ($result as $row) {

          // Seteamos a cero los cambios a realizar
          $UPDATE = array();

          // Por cada campo de la row verificamos que los datos no sean iguales
          foreach ($row as $key => $value) {

            // Si existe realmente la clave dada en los datos
            if (isset($data[$key])) {

              // Si los datos no son iguales que los que hay en la base de datos
              if ($data[$key] != $value) {
                // Agregamos a la lista de actualizar para construir la query
                $UPDATE[$key] = $key;
              }
            }
          }

          // Si hemos encontrado campos para los que hay que actualizar
          if ($UPDATE) {

            $WHERE = where($keys, $data);
            $SET   = set($UPDATE, $data);
            $query = "UPDATE `$table` SET $SET WHERE $WHERE";
            sql($query, $table, 'UPDATE');
            $U++;
          }
        }

        // Paramos el bucle de los KEYS ya que hemos encontrado dicha row/s
        break;
      }
    }
  }
}

/**
 * Function to construct the VALUES of a INSERT statement
 */
function values($data) {

  $VALUES = array();

  foreach ($data as $key => $value) {
    $value    = $data[$key];
    $VALUES[] = "'" . str_replace("'", "\'", $value) . "'";
  }

  return implode(', ', $VALUES);
}

/**
 * Function to construct the FIELDS of a INSERT statement
 */
function fields($data) {
  return '`' . implode('`, `', array_keys($data)) . '`';
}

/**
 * Function to construct the keys to WHERE SELECT / UPDATE
 */
function keys($table = null, $key = 'PRIMARY_KEYS') {

  global $tablas;

  if ($table) {
    return (isset($tablas[$table][$key])) ? $tablas[$table][$key] : false;
  }
  else {
    throw new Exception('ERROR: No se ha especificado la tabla para obtener sus claves');
  }
}

/**
 * Function to construct WHERE sentences
 */
function where($keys, $data) {

  $WHERE = array();

  foreach ($keys as $key) {
    $index   = $key;
    $value   = $data[$key];
    $WHERE[] = "`$index` = '" . str_replace("'", "\'", $value) . "'";
  }

  return implode(' AND ', $WHERE);
}

/**
 * Function to construct SET sentences
 */
function set($keys, $data) {

  $SET = array();

  foreach ($keys as $key) {
    $index   = $key;
    $value   = $data[$key];
    $SET[] = "`$index` = '" . str_replace("'", "\'", $value) . "'";
  }

  return implode(', ', $SET);
}

/**
 * Log a pure SQL TRANSACT to archive .sql
 */
function sql($query, $table, $mode) {

  global $UPDATE, $updates, $INSERT, $inserts;
  global $log_insert, $log_update, $log_common, $print;

  switch ($mode) {

    case 'UPDATE':

      if ($print) {
        echo '·';
      }

      $updates++;
      $UPDATE[] = array('table' => $table, 'query' => $query);
      error_log("$query;\n", 3, $log_update);
      error_log("$query;\n", 3, str_replace('.', ".$table.", $log_update));
      break;

    case 'INSERT':

      if ($print) {
        echo '+';
      }

      $inserts++;
      $INSERT[] = array('table' => $table, 'query' => $query);
      error_log("$query;\n", 3, $log_insert);
      error_log("$query;\n", 3, str_replace('.', ".$table.", $log_insert));
      break;
  }
}

/**
 * Function to parse filesize bytes y human readable with prefix
 */
function bytes($bytes) {

  $symbol = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
  $exp    = 0;
  $value  = 0;

  if ($bytes) {
    $exp   = floor(log($bytes) / log(1024));
    $value = ($bytes / pow(1024, floor($exp)));
  }

  return sprintf('%.2f ' . $symbol[$exp], $value);
}

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
