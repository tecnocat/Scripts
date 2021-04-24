<?php

/**
 * Set default code for Zend Debugger
 */
@ini_set('zend_monitor.enable', 0);

if (@function_exists('output_cache_disable')) {
  @output_cache_disable();
}

if (isset($_GET['debugger_connect']) AND $_GET['debugger_connect'] == 1) {

  if (function_exists('debugger_connect')) {
    debugger_connect();
    exit();
  }
  else {
    echo "No connector is installed.";
  }
}