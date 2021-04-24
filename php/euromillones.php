<?php
/*
39.508.293,00 €
20.696.087,00 €
65.686.353,00 €
40.169.694,00 €
43.037.523,00 €
40.505.576,00 €
44.060.359,00 €
44.738.297,00 €
43.371.515,00 €
46.419.812,00 €
96.061.152,00 €
77.191.686,00 €
65.213.994,00 €
55.728.138,00 €
48.434.281,00 €
42.403.666,00 €
41.364.359,00 €

Numeros   Estrellas   Probabilidad      Porcentaje de premio

2         1           1 de 39           24.0%
1         2           1 de 103          10,1%
3         0           1 de 367           4,7%
2         2           1 de 538           4,4%
3         1           1 de 551           5,1%
3         2           1 de 7.705         1,0%
4         0           1 de 16.143        0,7%
4         1           1 de 24.215        1,0%
4         2           1 de 339.002       1,5%
5         0           1 de 3.632.160     2,1%
5         1           1 de 5.448.240     7,4%
5         2           1 de 76.275.360   32,0%

*/

$porcentaje = array(
  52 => 32,
  51 => 7.4,
  50 => 2.1,
  42 => 1.5,
  41 => 1,
  40 => 0.7,
  32 => 1,
  31 => 5.1,
  30 => 4.7,
  22 => 4.4,
  21 => 24,
  20 => 0,
  12 => 10.1,
  11 => 0,
  10 => 0,
  '02' => 0,
  '01' => 0,
  '00' => 0,
);

$premio_maximo = 65686353;
$premio_minimo = 39508293;
$juegos_maximo = 100;

$numeros = array();
for ($i = 1; $i <= 50; $i++) {
  $numeros[$i] = $i;
}
$estrellas = array();
for ($i = 1; $i <= 11; $i++) {
  $estrellas[$i] = $i;
}

echo '<pre>
  <table width="640px" border="1" cellspacing="1" cellpading="1">
    <tr>
      <th>Juego</th>
      <th>Bote</th>
      <th>Invertido</th>
      <th>Numeros</th>
      <th>Estrellas</th>
      <th>Premio</th>
    </tr>';

for ($juego = 1; $juego <= $juegos_maximo; $juego++) {

  $premio = rand($premio_minimo, $premio_maximo);

  $numero_premio = $numeros;
  $numero_sorteo = $numeros;
  $estrella_premio = $estrellas;
  $estrella_sorteo = $estrellas;

  while (count($numero_premio) != 5
  AND count($numero_sorteo) != 5) {
    shuffle($numero_premio);
    shuffle($numero_sorteo);
    array_shift($numero_sorteo);
    array_shift($numero_premio);
  }
  while (count($estrella_premio) != 2
  AND count($estrella_sorteo) != 2) {
    shuffle($estrella_sorteo);
    shuffle($estrella_premio);
    array_shift($estrella_sorteo);
    array_shift($estrella_premio);
  }
  asort($numero_premio);
  asort($numero_sorteo);
  asort($estrella_premio);
  asort($estrella_sorteo);

  $premio_ganado = premio($numero_premio, $numero_sorteo, $estrella_premio, $estrella_sorteo, $premio);

  echo "
    <tr>
      <td>$juego</td>
      <td>$premio&euro;</td>
      <td>" . ($juego * 4) . "&euro;</td>
      <td>";
  foreach ($numero_premio as $numero) {
    echo " $numero ";
  }
  echo "<br />";
  foreach ($numero_sorteo as $numero) {
    echo " $numero ";
  }
  echo '</td>
        <td>';
  foreach ($estrella_premio as $estrella) {
    echo " $estrella ";
  }
  echo '<br />';
  foreach ($estrella_sorteo as $estrella) {
    echo " $estrella ";
  }
  echo '
      </td>';
  echo '
      <td>' . $premio_ganado . '&euro;</td>
    </tr>';
}
echo '
  </table></pre>';

function premio(&$numero_premio, &$numero_sorteo, &$estrella_premio, &$estrella_sorteo, $premio) {
  global $porcentaje;
  $n = 0;
  $e = 0;
  foreach ($numero_sorteo as $numero) {
    if (in_array($numero, $numero_premio)) {
      $numero_premio[array_search($numero, $numero_premio)] = '<strong style="color: green;">' . $numero_premio[array_search($numero, $numero_premio)] .'</strong>';
      $numero_sorteo[array_search($numero, $numero_sorteo)] = '<strong style="color: green;">' . $numero_sorteo[array_search($numero, $numero_sorteo)] .'</strong>';
      $n++;
    }
  }
  foreach ($estrella_sorteo as $estrella) {
    if (in_array($estrella, $estrella_premio)) {
      $estrella_premio[array_search($estrella, $estrella_premio)] = '<strong style="color: green;">' . $estrella_premio[array_search($estrella, $estrella_premio)] . '</strong>';
      $estrella_sorteo[array_search($estrella, $estrella_sorteo)] = '<strong style="color: green;">' . $estrella_sorteo[array_search($estrella, $estrella_sorteo)] . '</strong>';
      $e++;
    }
  }
  return 'N: ' . $n . ' E: ' . $e . ' = ' . (($premio * $porcentaje[$n.$e]) / 100);
}