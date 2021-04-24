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
 * @details   Custom environment creator for developers
 *
 * This file it's use to create new and custom environments under apache2 and
 * Ubuntu distro, with this script you can create a standalone config site and
 * custom file logs to make life more easy in your developments ;-)
 *
 * This is the original file, all others are dummy copies, please delete it.
 *
 * @category   Tools
 * @version    $Id: environment.php 0 2012-26-01 11:58:34 $
 * @author     tecnocat
 * @file       /environment.php
 * @date       2012-26-01 11:58:34
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 *
 * @TODO: Hacer que se descargue un .sh y que lo ejecute todo automágicamente
 */

/**
 * Variables
 */
$title        = 'Environment Generator';
$default_name = 'Project';
$default_path = '/home/user/workspace/project';

/**
 * Main logic
 */
if ($_POST['environment_name'] AND $_POST['environment_path']) {

  $filepath = '/tmp/envgen.tmp';
  $recurse  = fopen($filepath, 'w');
  $project  = strtolower($_POST['environment_name']);
  $fullpath = strtolower($_POST['environment_path']);
  $filename = "www.$project.dev";
  $config   = "<VirtualHost *:80>
  ServerName www.$project.dev
  ServerAdmin root@localhost
  DocumentRoot $fullpath
  <Directory $fullpath/>
    Options Indexes +FollowSymLinks MultiViews
    AllowOverride All
    Order allow,deny
    Allow from all
  </Directory>
  ErrorLog /var/log/apache2/www.$project.dev.error.log
  # Possible values include: debug, info, notice, warn, error, crit,
  # alert, emerg.
  LogLevel debug
  CustomLog /var/log/apache2/www.$project.dev.access.log combined
</VirtualHost>";

  $infodata = "
        <header>
          <h3>
            <ol>
              <li>Add this line in your /etc/hosts: 127.0.0.1 $filename</li>
              <li>Put the download file in your /etc/apache2/sites-available</li>
              <li>Create a symbolic link to enable the environment using:<br />
              sudo ln -s /etc/apache2/sites-available/$filename /etc/apache2/sites-enabled/$filename</li>
              <li>Restart the Web server apache2 with: sudo service apache2 restart</li>
              <li>Put your \$base_url in your project settings.php to http://$filename</li>
              <li>Create a symbolic link to files-compartidos using:<br />
              sudo ln -s /home/YOUR_USER/workspace/files-compartidos/files-$project /home/YOUR_USER/workspace/$project/sites/default/files</li>
              </li>
              <li>Have fun! ;-)</li>
            </ol>
          </h3>
        </header>
  ";

  fwrite($recurse, $config);
  fclose($recurse);

  $javascript = "
  <script type='text/javascript'>
    window.open('?filename=$filename', '_blank', 'width=400,height=400');
  </script>
  ";
}

if ($_GET['filename']) {

  $filepath = '/tmp/envgen.tmp';
  $filename = $_GET['filename'];

  // check for file to process
  if (file_exists($filepath)) {

    // clean buffer
    ob_clean();

    // cache controls
    header('Expires: Sat, 1 Jan 2000 00:00:00 GMT');
    header('Cache-Control: store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Content-Description: File Transfer');

    // tweak for fuking IE
    if (preg_match('/MSIE ([\d\.]*)/', $_SERVER['HTTP_USER_AGENT'])) {
      header('Content-Type: application/force-download');
    }
    else {
      header('Content-Type: application/octet-stream');
    }

    // attach the file
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Content-Transfer-Encoding: binary');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

    // output to buffer
    readfile($filepath);

    // output buffer to browser
    ob_flush();

    // R.I.P. ;-)
    die();
  }
}

/**
 * HTML
 */
?>
<!DOCTYPE HTML>
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
          <label>Project name:</label>
          <input type="text" name="environment_name" value="<?php echo $default_name ?>" size="32" />
          <label>Project path:</label>
          <input type="text" name="environment_path" value="<?php echo $default_path ?>"  size="32" />
          <input type="submit" name="submit" value="Download!" />
        </form>

      </article>

    </section>

    <footer>
      <p class="footer">Copyright &copy; 2012 A.N.F.</p>
    </footer>

  </body>

</html>