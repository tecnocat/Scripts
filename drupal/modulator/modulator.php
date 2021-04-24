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
 * @details    Custom module creator for developers
 * @category   Tools
 * @version    $Id: modulator.php 0 2012-26-01 11:58:34 $
 * @author     tecnocat
 * @file       /modulator.php
 * @date       2012-26-01 11:58:34
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * @FIXED: Reemplazar los hooks $module por TOKEN_PLACEHOLDER
 * @TODO: Enviar un .zip con todo el modulo empaquetado
 * @TODO: Implementar mas hooks usados anteriormente
 * @TODO: Incluir configuración para .admin.inc
 * @TODO: Generar el .css y el .admin.inc en tiempo real
 */

$temp         = '';
$title        = 'Module Generator';
$default_name = 'Project';
$default_path = '/home/user/workspace/project';
$hooks        = scandir('./hooks');
asort($hooks);

foreach ($hooks as $hook) {

  if (in_array($hook, array('.', '..'))) {
    continue;
  }

  $hook   = array_shift(explode('.', $hook));
  $status = (in_array($hook, array('hook_init', 'hook_menu'))) ? 'disabled checked' : '';
  $temp  .= "<!-- Implementation of $hook(); -->\n";
  $temp  .= "<input type='checkbox' name='$hook' $status />\n";
  $temp  .= "<label for='$hook'>$hook()</label>\n";
  $temp  .= "<br />\n";
}

$hooks_checkboxes = $temp;

header('Content-type: text/html; charset=utf-8');

if (isset($_POST['build'])) {

  if (!$_POST['proyecto'] OR !$_POST['usuario'] OR !$_POST['nombre']) {
    $infodata = "<h2 style='color: red;'>Los campos Proyecto, Usuario y Nombre son obligatorios.</h2>";
  }
  else {

    $proyecto = strtoupper($_POST['proyecto']);
    $usuario  = strtolower($_POST['usuario']);
    $modulo   = strtolower($_POST['proyecto']);
    $nombre   = $_POST['nombre'];
    $fecha    = date('Y-m-d H:i:s');
    $hook     = $modulo;
    $module   = "<?php\n";
    $tokens   = array(
      'TOKEN_PROYECTO' => $proyecto,
      'TOKEN_USUARIO'  => $usuario,
      'TOKEN_MODULO'   => $modulo,
      'TOKEN_NOMBRE'   => $nombre,
      'TOKEN_FECHA'    => $fecha,
      'TOKEN_HOOK'     => $hook,
    );

    foreach (array('header.php', 'hooks/hook_init.php', 'hooks/hook_menu.php') as $file) {

      $data = file_get_contents($file);

      foreach ($tokens as $token => $value) {
        $data = str_replace($token, $value, $data);
      }
      $module .= "\n$data\n";
    }

    foreach ($_POST as $id => $status) {

      if (is_file("hooks/$id.php")) {

        $data = file_get_contents("hooks/$id.php");

        foreach ($tokens as $token => $value) {
          $data = str_replace($token, $value, $data);
        }
        $module .= "\n$data\n";
      }
    }

    // output the code and finish
    die(highlight_string($module, 1));
  }
}
?><!DOCTYPE HTML>
<html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo $title ?></title>
    <style type="text/css">
      * {
        font: normal normal normal 16px/160% Arial, Tahoma, Helvetica, sans-serif
      }
      body {
        background-image: -webkit-gradient(
            linear,
            left top,
            right bottom,
            color-stop(0.29, rgb(203,201,209)),
            color-stop(0.65, rgb(86,163,168)),
            color-stop(0.83, rgb(90,189,130))
        );
        background-image: -moz-linear-gradient(
            left top,
            rgb(203,201,209) 29%,
            rgb(86,163,168) 65%,
            rgb(90,189,130) 83%
        );
      }
      h1 {
        font-size: 2em;
      }
      h2 {
        font-size: 1.8em;
      }
      h3 {
        font-size: 1.6em;
      }
      p.footer {
        font-size: 0.8em;
      }
    </style>

    <?php echo $javascript ?>

  </head>

  <body>

    <section>

      <article>

        <header>
          <h2><?php echo $title ?></h2>
        </header>

        <?php echo $infodata ?>

        <form name="environment" method="POST">
          <label>Proyecto:</label>
          <input type="text" name="proyecto" value="<?php echo $_REQUEST['proyecto'] ?>" size="32" />
          <br />
          <label>Tu usuario:</label>
          <input type="text" name="usuario" value="<?php echo $_REQUEST['usuario'] ?>"  size="32" />
          <br />
          <label>Tu nombre:</label>
          <input type="text" name="nombre" value="<?php echo $_REQUEST['nombre'] ?>" size="32" />
          <br />
          <legend>Hooks a incluir:</legend>
          <?php echo $hooks_checkboxes; ?>
          <p>* Some hooks are bloqued for dependency requirements.</p>
          <input type="submit" name="build" value="Download!" />
        </form>

      </article>

    </section>

    <footer>
      <p class="footer">Copyright &copy; 2012 A.N.F.</p>
    </footer>

  </body>

</html>