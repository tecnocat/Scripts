<?php

/*******************************************************************************
 * MySQLi functions
 ******************************************************************************/

/**
 * Connect to database
 */
function db_open($server, $user, $password, $database) {

  if (isset($server) AND isset($user) AND isset($password) AND isset($database)) {

    $link = mysqli_connect($server, $user, $password, $database);

    if (mysqli_connect_errno()) {
      throw new Exception('Connection failed: ' . mysqli_connect_error());
    }
    else {
      mysqli_query($link, 'SET NAMES utf8');
    }
  }
  else {
    throw new Exception('Connection failed: Missing MySQLi configuration.');
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
