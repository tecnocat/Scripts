<html>
  <head>
    <title>locagost</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <script>
      $(document).ready(function() {
        $("#accordion").accordion();
      });
    </script>
    <link rel="stylesheet" type="text/css" href="stylesheet.css" />
  </head>
  <body>
    <div id="accordion">
      <?php $path = './funciones'; ?>
      <?php $funciones = scandir($path); ?>
      <?php foreach ($funciones as $funcion): ?>
      <?php if (substr($funcion,0 , 8) != 'funcion-') { continue; } ?>
      <?php $file = $funcion; ?>
      <?php $funcion = str_replace(array('funcion-', '.php'), '', $funcion); ?>
      <?php $codigo = explode("\n", file_get_contents($path . '/' . $file)); ?>
      <?php $sintaxis = $codigo[0]; ?>
      <?php array_shift($codigo); ?>
      <?php $codigo = implode("\n", $codigo); ?>

      <!-- inicio bloque -->
      <h3><a href="#"><?php echo $funcion ?></a></h3>
      <div>
        <p class="funcion">Función: <strong><?php echo $funcion ?></strong></p>
        <!-- que es lo que realiza -->
        <p class="sintaxis">Sintáxis: <?php echo $sintaxis ?></p>
        <!-- codigo sin interpretar -->
        <p class="codigo">
          <?php echo str_replace('&nbsp;', ' ', highlight_string($codigo, true)) . "\n"; ?>
        </p>
      </div>
      <!-- fin bloque -->
      <?php endforeach; ?>

    </div>
  </body>
</html>