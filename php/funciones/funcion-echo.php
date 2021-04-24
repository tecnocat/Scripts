void echo ( string $arg1 [, string $... ] )
<?php
/*
Muestra todos los parámetros.

echo() no es realmente una función (es un constructor del lenguaje), por lo que no se requiere el uso de paréntesis con el. echo() (a diferencia de otros constructores del lenguaje) no se comporta como una función, es decir no siempre se puede usar en el contexto de una función. Además, si se quieren pasar más de un parámetro a echo(), los parámetros no deben estar entre paréntesis.

echo() también tiene sintaxis corta, donde se puede poner el símbolo igual justo después del inicio de la etiqueta de PHP. Este tipo de sintaxis corta solo funciona con la opción de configuración short_open_tag activada.
*/
echo "Hola mundo";

echo "Esto espacia
multiple líneas. los saltos de línea también
se mostrarán";

echo "Esto espacia\nmúltiples líneas. Los saltos de línea también\nse mostrarán.";

echo "Para escapar caracteres se hace \"así\".";

// Se puede usar variables dentro de una declaración echo
$foo = "foobar";
$bar = "barbaz";

echo "foo es $foo"; // foo es foobar

// También se pueden usar arrays
$baz = array("value" => "foo");

echo "Esto es {$baz['value']} !"; // Esto es foo !

// Si se utilizan comillas simples se mostrará el nombre de la variable, no su valor
echo 'foo is $foo'; // foo is $foo

// Si no se están usando otros caracteres, se puede simplemente echo variables.
echo $foo;          // foobar
echo $foo,$bar;     // foobarbarbaz

// Some people prefer passing multiple parameters to echo over concatenation.
echo 'Esta ', 'cadena ', 'está ', 'hecha ', 'con múltiple parámetros.', chr(10);
echo 'Esta ' . 'cadena ' . 'está ' . 'hecha ' . 'con concatenación.' . "\n";

echo <<<END
Aquí se utiliza la sintaxis de "here document" para mostrar
múltiples líneas con interpolación de $variable. Nótese
que el finalizador de here document debe aparecer en una
línea con solamente punto y coma. ¡Nada de espacio extra!
END;

// Ya que echo no se comporta como una función el siguiente código no es válido.
// ($some_var) ? echo 'true' : echo 'false';

// De todas formas el siguiente código funcionará:
($some_var) ? print 'true' : print 'false'; // print también es un constructor, pero
                                            // se comporta como una función, entonces
                                            // puede usarse en este contexto.
echo $some_var ? 'true': 'false'; // dando la vuelta a la declaración
?>