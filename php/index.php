<html>
  <head>
    <title>locagost</title>
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
      a {
        text-decoration: none;
        color: rgb(150,2,2);
      }
      a:hover {
        text-decoration: underline;
        font-weight: bold;
      }
      .dir {
        list-style-image: url('folder.png');
      }
      .file {
        list-style-image: url('file.png');
      }
    </style>
  </head>
  <body>
    <?php
      if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1'
      AND  $_SERVER['REMOTE_ADDR'] != '::1') {
        echo 'hola ' . $_SERVER['REMOTE_ADDR'] . ' :-)' . "\n";
      }
      else {
        $dirs = scandir('.');
        array_shift($dirs);
        array_shift($dirs);
        echo '<ul>' . "\n";
        foreach ($dirs as $item) {
          if (is_dir($item)) {
            $directorys[] = $item;
          }
          else {
            $files[] = $item;
          }
        }
        asort($directorys);
        asort($files);
        foreach ($directorys as $dir) {
          echo '      <li class="dir"><a href="' . $dir . '">' . $dir . '</a></li>' . "\n";
        }
        foreach ($files as $file) {
          echo '      <li class="file"><a href="' . $file . '">' . $file . '</a></li>' . "\n";
        }
        echo '    </ul>' . "\n";
      }
    ?>
  </body>
</html>