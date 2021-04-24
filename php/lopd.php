<?php

/**
 *
 * @file lopd.php
 * @author tecnocat
 *
 * Manage and prepare a database to LOPD
 *
 */



/*******************************************************************************
 * Variables
 ******************************************************************************/

/**
 * Config for MySQL
 */
$server   = 'localhost';
$user     = 'root';
$password = 'drupal';



/**
 * Initialize
 */
$link = '';
$data = '';
$rows = '';
$loop = 0;



/**
 * Defaults values for forms and unset labels display
 */
$select_databases = '<option value="0">-- Select a database --</option>';
$label_databases  = '';
$select_tables    = '<option value="0">-- Select a table --</option>';
$label_tables     = '';
$select_fields    = '<br />';
$label_fields     = '';


/**
 * Empty database an table with selected table and confirm with empty post data
 */
$database = '';
$table    = '';
$istable  = false;

if (!isset($_REQUEST['database']) OR $_REQUEST['database'] == '0') {
  $_REQUEST['database'] = '';
}

if (!isset($_REQUEST['table']) OR $_REQUEST['table'] == '0') {
  $_REQUEST['table'] = '';
}

$_REQUEST['checklist'] = array();



/**
 * Output of results, by default nothing to show
 */
$results = 'No results.';



/*******************************************************************************
 * Main logic
 ******************************************************************************/

// First select all of databases in the configurated server to show to the user
$link = db_open('information_schema');
$data = db_query($link, 'SELECT `SCHEMA_NAME` FROM `SCHEMATA`');
$rows = db_fetch($data);

// Fetch the databases first
foreach ($rows as $row) {

  $database = $row['SCHEMA_NAME'];
  $selected = ($database == $_REQUEST['database']) ? 'selected' : '';

  // skip information_schema and mysql for security reasons
  if (in_array($database, array('information_schema', 'mysql', 'phpmyadmin'))) {
    continue;
  }

  // prepare databases to select form
  $select_databases .= "<option value='$database' $selected>$database</option>";
}

if (!empty($_REQUEST)) {

  if (!empty($_REQUEST['database'])) {

    // Fetch the tables of the selected database
    $query = "
    SELECT `TABLE_NAME`
    FROM `TABLES`
    WHERE `TABLE_SCHEMA` = '" . $_REQUEST['database'] . "'";
    $data  = db_query($link, $query);
    $rows  = db_fetch($data);

    foreach ($rows as $row) {

      $table    = $row['TABLE_NAME'];
      if (!empty($_REQUEST['table'])) {

        $selected = ($table == $_REQUEST['table']) ? 'selected' : '';
        $istable  = (empty($selected) AND !$istable) ? false : true;
      }
      else {
        $istable  = false;
      }

      $select_tables .= "<option value='$table' $selected>$table</option>";
    }

    if ($istable) {
      // Fetch the tables of the selected database
      /* if not needed all fields replace * with this:
      `COLUMN_NAME`, `IS_NULLABLE`, `DATA_TYPE`,
      `CHARACTER_MAXIMUM_LENGTH`, `NUMERIC_PRECISION`,
      `COLUMN_COMMENT`
      */
      $query = "
      SELECT * FROM `COLUMNS`
      WHERE `TABLE_SCHEMA` = '" . $_REQUEST['database'] . "'
      AND `TABLE_NAME` = '" . $_REQUEST['table'] . "'";
      $data  = db_query($link, $query);
      $rows  = db_fetch($data);

      foreach ($rows as $row) {

        $field       = $row['COLUMN_NAME'];
        $description = $row['COLUMN_COMMENT'];
        $description = ((bool) $description) ? " ($description) " : '';
        $checked     = (isset($_REQUEST[$field])) ? 'checked' : '';

        if ((bool) $checked) {
          $_REQUEST['checklist'][$field] = $row;
        }

        $select_fields .= "<input type='checkbox' name='$field' $checked/>";
        $select_fields .= '<strong>' . $field . '</strong>';
        $select_fields .= $description . '<br />';

      }
      $label_fields  = 'Please select the fields than may be process.';
    }

    if (empty($_REQUEST['table']) OR !$istable) {
      $label_tables  = 'No active table, please select one:';
    }
    else {
      $table         = '<strong>' . $_REQUEST['table'] . '</strong>';
      $label_tables  = 'Active table is ' . $table . ', you can change to:';
    }

    $database        = '<strong>' . $_REQUEST['database'] . '</strong>';
    $label_databases = 'Active database is ' . $database . ', you can change to:';
  }
  else {
    $label_databases = 'Please, select the name of a database:';
    $label_tables    = 'You need first select a database.';
  }

}

db_close($link);

// If need to process some field, run now
if (count($_REQUEST['checklist'])) {

  foreach ($_REQUEST['checklist'] as $name => $data) {

    //dump('checking ' . $name . '...');
    db_dummy($data);
  }
}



/*******************************************************************************
 * PHP + HTML5
 ******************************************************************************/
$select_databases = '
          <label>' . $label_databases . '</label>
          <select name="database">
            ' . $select_databases . '
          </select>';
$select_tables = '
          <label>' . $label_tables . '</label>
          <select name="table">
            ' . $select_tables . '
          </select>';
$select_fields = '
          <label>' . $label_fields . '</label>
          ' . $select_fields . '
          ';



/*******************************************************************************
 * Functions
 ******************************************************************************/

/**
 * Connect to database
 */
function db_open($database) {

  global $server, $user, $password;
  $link = mysqli_connect($server, $user, $password, $database);

  if (mysqli_connect_errno()) {
    die('Connection failed: ' . mysqli_connect_error());
  }
  else {
    mysqli_query($link, 'SET NAMES utf8');
    return $link;
  }
}



/**
 * Execute query in database
 */
function db_query($link, $query) {

  if (!isset($link)) {
    die('Connection with MySQL server needed before execute a SQL query.');
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
  while ($fetched = mysqli_fetch_assoc($data)) {
    $rows[] = $fetched;
  }
  mysqli_free_result($data);

  return $rows;
}



/**
 * Disconnect from database
 */
function db_close($link) {
  mysqli_close($link);
}



/**
 * Truncate field with dummy data
 */
function db_dummy($data) {
  //dump($data);

  // More easy to call vars with their real MySQL name
  extract($data);

  //dump($_REQUEST);
  $link  = db_open($TABLE_SCHEMA);
  $query = "SELECT `$COLUMN_NAME` FROM `$TABLE_NAME` LIMIT 1, 1";
  $data  = db_query($link, $query);
  $rows  = db_fetch($data);

  foreach ($rows as $row) {
    $value = $row[$COLUMN_NAME];
    $parms = $_REQUEST['checklist'][$COLUMN_NAME];
    $query = "
    UPDATE `$TABLE_NAME`
    SET `$COLUMN_NAME` = '" . dummy($COLUMN_NAME, $value, $parms) . "'
    WHERE `$COLUMN_NAME` = '$value'";

    if (empty($value)) {

      // Skip empty fields
      continue;
    }
    else {
      dump($query);
    }
  }

  db_close($link);
}



/**
 * Dummy function, generate dummy content based on received parameters
 */
function dummy($name, $value, $parms) {

  $debug = array(
    'name' => $name,
    'value' => $value,
    'parms' => $parms,
  );
  dump($debug);

  // More easy to call vars with their real MySQL name
  extract($parms);

  switch ($DATA_TYPE) {

    // Numeric types
    case 'tinyint':
      break;

    case 'smallint':
      break;

    case 'mediumint':
      break;

    case 'int':
      break;

    case 'bigint':
      break;

    case 'float':
      break;

    case 'double':
      break;

    case 'decimal':
      break;

    case 'bit':
      break;

    // String types
    case 'char':
      break;

    case 'varchar':
      $dummy   = md5(uniqid(mt_rand(), true));
      $charset = $CHARACTER_SET_NAME;
      $length  = mb_strlen($value, $charset);
      while (mb_strlen($dummy, $charset) < $length) {
        dump($dummy);
        dump($value);
        $dummy .= md5(uniqid(mt_rand(), true));
      }
      $return  = mb_substr($dummy, 0, $length, $charset);
      break;

    case 'tinytext':
      break;

    case 'text':
      break;

    case 'mediumtext':
      break;

    case 'longtext':
      break;

    // Binary types
    case 'binary':
      break;

    case 'varbinary':
      break;

    case 'tinyblob':
      break;

    case 'blob':
      break;

    case 'mediumblob':
      break;

    case 'longblob':
      break;

    case 'enum':
      break;

    case 'set':
      break;

    // Date types
    case 'date':
      break;

    case 'datetime':
      break;

    case 'time':
      break;

    case 'timestamp':
      break;

    case 'year':
      break;

    default:
      dump('Unknow data type ' . $DATA_TYPE);
      break;
  }

  return $return;
}



/**
 * Dump a var_dump with a few css style
 */
function dump($variable) {
  $style = array(
    'background-color: #ddd',
    'margin: 0px',
    'padding: 15px',
  );
  echo '<hr /><div style="' . implode(';', $style) . '">';
  var_dump($variable);
  echo '</div><hr />';
}



/*******************************************************************************
 * HTML5
 ******************************************************************************/
?>
<!DOCTYPE HTML>
<html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Your Website</title>
  </head>

  <body>

    <header>
      <nav>
        <ul>
          <li><a href="<?php echo basename(__FILE__) ?>">Main</a></li>
        </ul>
      </nav>
    </header>

    <section>

      <article>

        <header>
          <h2>LOPD Operations</h2>
        </header>

        <form>
          <?php echo $select_databases ?>
          <br />
          <?php echo $select_tables ?>
          <br />
          <?php echo $select_fields ?>
          <input type="submit" name="submit" value="Switch!" />
        </form>

      </article>

    </section>

    <aside>
      <h2>Results:</h2>
      <?php echo $results ?>
    </aside>

    <footer>
      <p>Copyright 2009 Your name</p>
    </footer>

  </body>

</html>