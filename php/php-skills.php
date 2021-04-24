<?php

/*
Creo que llegados a este punto es hora de ponernos algo serios y empezar a inculcar
las buenas prácticas a los programadores, para que poco a poco se vaya consiguiendo
obtener desarrollos con estructura de código similar y homogénea, en vez de lo que
hay ahora, que cada uno pone lo que cree mas oportuno de la manera que cree que es
mas correcta, es muy importante que los proyectos sean fáciles de mantener y que
el código que ha hecho A lo pueda mantener B o incluso C sin tener que recurrir
ninguno a preguntarle a A por que se hizo eso asi o cosas similares.

Es por eso que he pensado en publicar finalmente el manual de coding standards que
ya hice hace mas de un año en el gestor de conocimiento, o en caso de no existir o
estar operativo enviartelo a ti para que tu a su vez lo envies por e-mail e indiques
que es buen momento para ponerlo en práctica, ya que al parecer a ti te hacen caso,
por que cuando yo envio consejos o similares obtengo respuesta y resultados 0.

Por eso he pensado que este examen/test o como queramos llamarlo podría contar de
2 conceptos, a ver que te parece mi planteamiento:

1 - Las preguntas creo que deberían ser escritas, me refiero a escritas para evitar
ser tipo test, de escoja la respuesta a la pregunta, para no permitir responder al
azar, te pongo un ejemplo para verlo mejor:
*/

// Pregunta: ¿Que devolvera el siguiente código?

$colors = array(
  'red'   => 'hot',
  'green' => 'warm',
  'blue'  => 'cold',
);
echo array_pop(array_keys($colors));

// A) red
// B) hot
// C) green
// D) warm
// E) blue
// F) cold


// Pienso que sería mejor de este otro modo para evitar 'la suerte del azar':

// Pregunta: ¿Que devolvera el siguiente código?

$colors = array(
  'red'   => 'hot',
  'green' => 'warm',
  'blue'  => 'cold',
);
echo array_pop(array_keys($colors));

/*
2 - Las respuestas acertadas podrían tener un plus de buena sintaxis de tal modo
que por pregunta acertada 1 punto y por pregunta acertada y buena sintaxis 1,5 o
2 puntos, de ese modo se evalua no solo la agudeza del programador si no su buena
fé del uso de buenas prácticas, no es lo mismo escribir código que cagar código,
te pongo un ejemplo de ambos casos igualmente funcionales:
*/

// Spaguetti

$items=12;
for($i=0;$i<=$items;$i++){
    $list[] = "<li>$i - item</li>";
}

// Buena sintaxis:

$items = 12;
$list  = array();
for ($i = 0; $i <= $items; $i++) {
  $list[] = '<li>' . $i . ' - item</li>';
}

// Algo de teoría básica sobre PHP

// ¿Cuales son los 4 tipos de datos escalares que maneja PHP?
// Respuesta: boolean, integer, float/double y string

// ¿Cuales son los 2 tipos de datos compuestos que maneja PHP?
// Respuesta: array y object

// ¿Cuales son los 2 tipos de datos especiales que maneja PHP?
// Respuesta: resource y NULL

// Bueno y ahora ideas para evaluar, todas las preguntas son tipo ¿Que devuelve?

// Strings

// 1
echo strpos('En el campo hay árboles', 'camp');

// 2
echo strstr('En el campo hay árboles', 'camp');

// 3
echo (int) substr('Hay 12 manzanas', -11);

// 4
echo (bool) gettype((int) 'Tengo 4 sandías');

// 5
echo (bool) !0;

// 6
echo (bool) !'false';

// 7
echo (bool) '0';

// 8
$color  = 'rojo';
$string = <<<HEREDOC
Mi color favorito es $color
HEREDOC;
echo $string;

// 9
$string = <<<HEREDOC
Hoy estamos a " . date('d/m/Y H:i:s') . "
HEREDOC;
echo $string;

// 10
$string = 'El coche azul';
echo $string[10];

// Arrays

// 11
$colors = array(3 => 'red', green, 'blue');
echo $colors[4];

// 12
$colors = array('red', 'green', 'blue');
array_push($colors, 'yellow');
array_shift($colors);
echo $colors[1];

// 13
$string = explode(' ', 'I love PHP soo much!');
echo array_pop($string);
echo $string[4];

// 14
$colors = array('red', 'green', 'blue');
$days   = array('Friday', 'Saturday', 'Sunday');
array_combine($colors, $days);
echo $colors[1];

// 15
$string = explode(', ', 'Cuento un, dos, tres como 1,2,3 splash!');
$string  = array_flip($string);
echo $string[1];

/*
No voy a poner mas ejemplos por que creo que la idea de ellos se ve muy bien, es
para que sirva de ejemplo de lo que se podría poner, mas que saber resolverlos
la idea es que se preste atención y se detecte lo que sucederá realmente, hay un
par de ellos que tienen truco y no son tan fáciles como parecen ser.

Sobre PHP se podría preguntar sobre el uso de las funciones mas comunes, con algunos
ejemplos parecidos a los que puesto, saber que funciones hay que usar en cada
momento y cosas así, también sería interesante hablar sobre las variables especiales
$_GET, $_POST, $_REQUEST, $_SERVER, $_COOKIE etc... por ejemplo en formularios
cuando se acceden a los datos y como, como poder setear una cookie desde PHP que
es algo que con los CMS ya prácticamente ni se sabe/usa, recoger valores para el
manejo de archivos de $_SERVER por ejemplo cuando hay que guardar o leer datos de
archivos, o que y como se interpretan los datos de $_FILES por ejemplo.

Podrías evaluar el conocimiento de estas $GLOBALS y preguntar algo sobre ellas,
que sabemos que están ahí pero quizá no se usen correctamente, y si las variables
son visibles o no, cuando y cómo son globales, estaticas, privadas o el ámbito
de las mismas, por ejemplo fuera de una función y dentro el uso de la misma $var

Tambien existen las funciones magicas que te dan datos interesantes, no se como
se podría preguntar algo relaccionado con ellas, hablo de __FILE__, __FUNCTION__,
__CLASS__, __LINE__ etc...

Otro punto también interesante sería preguntar como depurar un código, y no digo
eso de var_dump/print_r si no saber que archivo/función ha llamado a la linea
actual, cuando estás en la función B llamada por A saber como se llama A y en que
archivo y línea ha sido llamada B.

Saber que diferencia hay de pasar una variable por referencia o normal, en que
casos es necesario o no devolver un valor con return/echo, poner un par de tests
en donde pasar variables por referencia o alo así.

Hablar sobre classes, preguntar como se declara una clase, que son los metodos,
cuando se llama a __construct y __destruct, pedir que se escriba un código de
ejemplo con una clase lampara e implementar el uso de los metodos encender() y
apagar() por ejemplo, meter opciones avanzadas como class CLASE extends PADRE
y luego saber las diferencias y que pasara con las llamadas self::metodo(),
parent::metodo() y cosas así, que es la variable $this en distintos momentos.

Hablar de los try {} catch () y poner preguntas de algo para evitar errores o
saber procesarlos dependiendo del tipo, pedir al programador que en un código de
ejemplo implemente aserciones para evitar 'Fatal error' o que corte flujo lógico,
que implemente por ejemplo throw new Exception() para cazar errores concretos...

Hacer unas preguntillas sobre die(), exit(), return, break [1, 2, n], continue y
poner un par de bucles anidados con un break 2 a ver si saben si saldrá del bucle
1 o todos etc...

Preguntar la sintaxis básica de las estructuras de control, if then else, switch
while/do while, for/foreach, etc... los metodos existentes para cuando estos son
usados dentro de código html (if (cond): else: endif;), preguntar que es y como
se usa el método ternario...

A partir de PHP 5.3, es posible dejar de lado la parte media del operador ternario.
La expresión expr1 ?: expr2 retorna expr1 si expr1 se evalúa TRUE y expr2 si no.