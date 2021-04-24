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
 * This is the script that the ninja poops, look and fun, this is their style :)
 *
 * Fucked Bitelchus Córdoba! Then they asked me how it worked this wasted script!?
 */

define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH . '/config/Config.php');
include(ROOT_PATH . '/config/appi.inc');
 include(ROOT_PATH . '/config/log.inc');

 require_once ROOT_PATH . '/classes/Connection.php';



	$year = "";
	$month = "";
	$nif = "";
	$xml = "";
	$token = "";

	if (isset($_POST['year']))
		$year = $_POST['year'];
	if (isset($_POST['month']))
		$month = $_POST['month'];
	if (isset($_POST['nif']))
		$nif = $_POST['nif'];
	if (isset($_POST['xml']))
		$xml = $_POST['xml'];
	if (isset($_POST['api_token']))
		$token = $_POST['api_token'];
         if (isset($_POST['html']))
                $html = $_POST['html'];

        /*Temporary log to know what data is being sent*/
        error_log($xml,3,log_file2);
        /*End of temporary log*/

	//ComprobaciÃ³n del formato de datos

	$valido = check_data($year, $month, $nif, $xml, $token);

	if ($valido <> "")
	{
		//Tenemos que construir el xml de error
		$xml = webservice_output_error($year, $month, $nif, $token);
	} else {

		 $xml = webservice_output($year, $month, $nif, $token,$html);
	}


/**
 * Function to output data - tecnocat
 */
function webservice_output($year, $month, $nif, $token,$html) {
	$input_dir = input_dir; // Without the trailing slash!

	if (strlen($month) <> 2)
		$month = "0".$month;


  if (!recursive_mkdir($input_dir.'/'.$year.'/'.$month, 0777)) {
	  $return  = 'The system can not create the necessary structure folder ';
	  $return .= $path . ' due to permission restrictions, please change the ';
	  $return .= 'permissions to allow a web server to use mkdir.';

	  error_log($return, 3, log_file);
	  webservice_output_error($year, $month, $nif, $token);

    }
	else {
	  $file = $input_dir . '/' . $year . '/' . $_POST['month'] . '/' . $_POST['nif'] . '.html';

	  if ($fp = fopen($file, 'w')) {
		fwrite($fp, $html);
		fclose($fp);
		$return = 'OK';
	  }
	  else {
		error_log("Error al escribir en disco", 3, log_file);
		$return = 'Error al escribir en disco';
	  }
	    return _xml(array('respuesta' => $return), 'xml');

	  }
}

/**
 * Function to output error data - tecnocat
 */
function webservice_output_error($year, $month, $nif, $token) {
    $return  = array(
      'respuesta' => "Error",
    );

    return _xml($return, 'xml');
}





function recursive_mkdir($path, $mode = 0775) {

$fullpath = "";

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
 * Function to check format data - tecnocat
 */
function check_data($year, $month, $nif, $xml, $token) {

	$date = date('d.m.Y h:i:s');

	$error = "";
	if ($year == "")
	{
		$msg = "Debe indicar el parÃ¡metro year";
		$log = $date. "|" .$msg."\n";
		error_log($log, 3, log_file);
		return $msg;
	}
	if (strlen($year) <> 4)
	{
		$msg = "El aÃ±o debe tener cuatro dÃ­gitos";
		$log = $date. "|" .$msg."\n";
		error_log($log, 3, log_file);
		return $msg;
	}
	if ($month == "")
	{
		$msg = "Debe indicar el parÃ¡metro month";
		$log = $date. "|" .$msg."\n";
		error_log($log, 3, log_file);
		return $msg;

	}
	if (strlen($month) > 2)
	{
		$msg = "El mes no puede tener mÃ¡s de dos dÃ­gitos";
		$log = $date. "|" .$msg."\n";
		error_log($log, 3, log_file);
		return $msg;

	}
	if ($token == "")
	{
		$msg = "Debe indicar el parÃ¡metro api_token";
		$log = $date. "|" .$msg."\n";
		error_log($log, 3, log_file);
		return $msg;

	}
	if ($token <> api_token)
	{
		$msg ="El api token no es correcto";
		$log = $date. "|" .$msg."\n";
		error_log($log, 3, log_file);
		return $msg;

	}

	if ($nif == "")
	{
		$msg = "Debe indicar el parÃ¡metro nif";
		$log = $date. "|" .$msg."\n";
		error_log($log, 3, log_file);
		return $msg;

	}


	if ($nif <> "")
	{
		//Tenemos que ver si el NIF existe en base de datos
		$link   = db_open(server, dbuser, dbpass, dbname);
		$query  = "
			SELECT emp_number from hs_hr_employee where emp_other_id = '".$nif."'";

		$matches = db_fetch(db_query($link, $query));
		db_close($link);

		if(count($matches) == 0)
		{
			$msg = "No existe ningÃºn empleado con dicho NIF";
			$log = $date. "|" .$msg."\n";
			error_log($log, 3, log_file);
			return $msg;
		}

	}

	if ($xml == "")
	{
		$msg = "Debe indicar el parÃ¡metro xml";
		$log = $date. "|" .$msg."\n";
		error_log($log, 3, log_file);
		return $msg;

	}

	return $error;
}



function _xml($data, $enclosure = 'XMLStructure', $dat = false, $tab = 0) {

  header('Content-type: text/xml');
  $n   = "\n";
  $t   = str_repeat('  ', $tab++);
  $xml = "<xml>";
  $xml .= "$t<$enclosure>$n";

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
  $xml .= "</xml>";
  die($xml);
}

?>
