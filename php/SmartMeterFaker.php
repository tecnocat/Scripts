<?php

/**
 * Generate SQL to populate a SmartMeter with fake data to test AlertBundle
 */
$time = new DateTimeZone('UTC');
$from = new DateTime('2010-01-01', $time);
$to   = new DateTime('first day of this month midnight -1 sec', $time);
$file = fopen ('runme.sql', 'w');
$sql  = <<<SQL
-- Use database
USE c1gnfetc;

-- Truncate the affected rows
DELETE FROM `etc_pm_home_energy` WHERE `house_bridge_id` = 3; -- Prueba02

-- Insert the new ones

SQL;

fwrite($file, $sql);

while ($from <= $to) {

  $commit = 0;
  $watts  = 1000 / 24; // We want 1 kWh per day
  $kwh    = $watts / 4000; // (kWh = W * (1000 / 0.25))
  $INSERT = "INSERT INTO `etc_pm_home_energy` (house_bridge_id, consumption, power, hour, created_at)";
  $VALUES = array();

  while ($commit < 500 AND $from <= $to) {

    if ($from->format('d') > 20) {
      $from->modify('+ 15 mins');
      continue;
    }

    $date     = $from->format('Y-m-d H:i:s');
    $VALUES[] = sprintf("(3, %s, %s, '%s', '%s')", $kwh, $watts, $date, $date);
    echo $from->format("Y.m.d H:i:s\n");
    flush();
    $from->modify('+ 15 mins');
    $commit++;
  }

  $SQL = $INSERT . ' VALUES ' . implode(',', $VALUES) . ';';

  // Write to file
  fwrite($file, $SQL . "\n");

}
echo "Completed.\n";
fclose($file);