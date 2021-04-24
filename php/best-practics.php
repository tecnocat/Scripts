Mejoras e ideas para el equipo drupaleros

Usar echo en vez de un print y respetar dentro de lo posible los coding standars
Al crear un modulo usar el archivo .module solo para hooks y .inc para funciones
Cuando se crea un pane o panel asignar el id y class iguales parecidos al titulo
Los nombres de imagecache no deberian contener numeros de medidas solo un titulo
No poner en las view style nodo sino fields salvo cuando sea realmente necesario
Cuando metemos ids o class para maquetacion es mejor no usar underscores si no -
Cuando se manejan valores es muy recomendable castear el tipo de datos variables
La longitud de las lineas no deberían superar el ancho de 75-85 caracteres aprox


PHP Coding Standards:                http://pear.php.net/manual/en/standards.php
Drupal coding Standards:                      http://drupal.org/coding-standards
Line length:                                http://paul-m-jones.com/archives/276
Doxygen information:                                 http://blog.gon.cl/post/296

/**
 * Doxygen comments
 */
Documentar el código es más que dejarlo bonito. Es hacerlo entendible.

“Comentar el código es como limpiar el cuarto de baño; nadie quiere hacerlo,
pero el resultado es siempre una experiencia más agradable para uno mismo y sus
invitados” – Ryan Campbell

Uno de los propósitos iniciales de este blog, era combatir mi inquietante
amnesia. No se ustedes, pero me es muy frecuente, que después de muchos días,
semanas o meses sin ver el código que estaba trabajando, termino olvidando que
hacía o como funcionaba.

“Ley de Alzheimer de la programación: si lees un código que escribiste hace más
de dos semanas es como si lo vieras por primera vez” — Via Dan Hurvitz

Hay solo una forma de combatirlo: documentando.

Será una lata, pero creo (seriamente) que los lenguajes de programación debieran
mandar advertencias cuando no documentamos el código fuente.
Cuando estuve en inserto en el proyecto GDT, me topé de cerca con Doxygen, una
excelente herramienta para documentar el código en función de etiquetas
especiales insertas en los comentarios. Muchos años antes, me tocó conocer otra
grandiosa herramienta, que ayer re-descubrí: phpdocumentor.

¿Que tiene de fantástico?

Tiene varios detalles que lo hacen un amor:
Esta escrito en PHP.
En su documentación incluye los tags especiales, con ejemplos, para escribir
correctamente los comentarios.
Funciona por interfaz web y por consola. Posibilitando automatizar el proceso
de documentación
Se puede configurar, guardando un archivo personalizado .ini con las opciones.
Esto permite automatizar más aún el proceso.
Exporta a varios formatos, incluyendo HTML, PDF y CHM. Incluso tiene diversas
plantillas para producir distintos estilos de HTML. También se pueden
personalizar estas plantillas.
Sin duda debe tener más gracias, pero con eso es suficiente por ahora.
Ejemplos
Para probar, documenté TODAS las clases de Gonium y dejé copia acá. Sin ir más
lejos, la documentación del API de Zend Framework está producida con
phpDocumentor.
Sugerencia
Ser buen programador, no solo significa adoptar las mejores convenciones para
escribir el código, usar el mejor IDE (esto es una estupidez, pero lo he
escuchado), presumir el uso de patrones de diseño novedosos o presumir de código
limpio y óptimo. Documentar es una tarea básica que DEBIERA SER OBLIGATORIA. No
solo si sufres algún problema de memoria (con la de tu cabeza, no con la del PC)
como yo.
Además es mucho más tedioso documentar un montón de clases ya escritas, que no
sabes exactamente que hace, que comenzar documentando desde le principio. Si
estas comenzando un proyecto, HAZLO YA, sino TAMBI�N.
Caso personal
En Gonium hay algunas cuantas clases, que en su momento me pareció bueno crear
mientras se me ocurría como implementarlas (por ahí por febrero de este año).
Ahora no recuerdo que diablos quería hacer con ellas
Fuente de las citas: Variable not found: Otras 101 citas célebres del mundo de
la informática.
/*****/


/**
 * Normas comunes para estructuración de código
 */
Describir la estructura de carpetas para themes y módulos (.info, .admin.inc, js)
El módulo patrón para las customizaciones su nombre y estructura básica
Los nombres de las funciones internas con prefijo _anf
Los nombres de las variables y los form setttings con prefijo anf_
Los textos administrativos pueden ir en español
Los textos del portal deben ir todos en ingles con t();
La template page.tpl.php debe de tener comentario del template Override, indicar
que es una variable $template que agregamos en el hook_preprocess_page()
Hacer una función para poner los ids a los paneles a partir de su nombre maquina
En el EA especificar cuales van a ser los nombres de clases y estructuras para
facilitar la vida a maquetacion y tener un orden consciente de los elementos
Las páginas y variantes deben seguir unos patrones lógicos para evitar que se
llamen como sea y tengan un nombre máquina, url y nombre administrativo aleatorio
Los formatos de las fechas no tienen que usar date() si no format_date();
Hacer un ejemplo de uso de theme_item_list con clases dentro y fuera de los <li>
Hacer un ejemplo de uso de theme_table con clases dentro y fuera de <tr><td>
IMPORTANTISIMO que los módulos jamas se llamen igual que los themes (hook_block)



/******************************************************************************
 * CÓDIGO PREPARADO PARA SQL INJECTION                                        *
 ******************************************************************************/
<?php
if (!empty($_REQUEST['profile_sector'])) {
  $all = implode(',',$_REQUEST['profile_sector']);
  return $all;
} else {
  return 'all';
}
?>
/******************************************************************************
 * SOLUCIÓN DE SQL INJECTION                                                  *
 ******************************************************************************/
<?php
if (!empty($_REQUEST['profile_sector'])) {

  $profile_sector = $_REQUEST['profile_sector'];

  foreach ($profile_sector as $item => $content) {
    $profile_sector[$item] = filter_xss($content);
  }

  $all = implode(',', $profile_sector);

  return $all;
}
else {
  return 'all';
}
?>



/******************************************************************************
 * FALLO DE ESTRUCTURA REPETITIVA E INFINITA                                  *
 ******************************************************************************/
<?php
  if ($_GET['q'] == "node/".variable_get('redirect_nodo_buscador_profesional') )
    $titulo = t('Contactar con profesionales');
  else if ($_GET['q'] == "node/".variable_get('redirect_nodo_buscador_empresas') )
    $titulo = t('Buscar empresas');

  $cadena ='';
  if (!empty($_GET['free']))
    $cadena = $_GET['free'];
  else if (!empty($_GET['name']))
    $cadena = $_GET['name'];
  else if (!empty($_GET['profile_apellido']))
    $cadena = $_GET['profile_apellido'];
  else if (!empty($_GET['ciudad']))
    $cadena = $_GET['ciudad'];
  else if (!empty($_GET['profile_empresa']))
    $cadena = $_GET['profile_empresa'];
  else if (!empty($_GET['profile_cargo']))
    $cadena = $_GET['profile_cargo'];
  else if (!empty($_GET['ciudad']))
    $cadena = $_GET['ciudad'];
  else if (!empty($_GET['sector']))
    $cadena = $_GET['sector'][0];
  else if (!empty($_GET['profile_sector']))
    $cadena = $_GET['profile_sector'][0];
  else if (!empty($_GET['profile_state']))
    $cadena = $_GET['profile_state'];
  else if (!empty($_GET['provincia']))
    $cadena = $_GET['provincia'];

  if( !empty($cadena) )
    $cadena = "\"$cadena\"";
  //print "<h1 id ='page_title'>$titulo \"$cadena\"</h1>";
  $block["content"] = "<h1 id ='page_title'>$titulo $cadena</h1>";
?>
/******************************************************************************
 * APLICANDO LÓGICA Y CODING STANDARDS                                        *
 ******************************************************************************/
<?php

  $url_query = $_GET['q'];
  $url_prof  = 'node/' . variable_get('redirect_nodo_buscador_profesional');
  $url_comp  = 'node/' . variable_get('redirect_nodo_buscador_empresas');

  switch ($url_query) {

    case $url_prof:

      // WRONG, MUST BE ALWAYS IN ENGLISH, IF t() != ENGLISH IS WRONG!
      $titulo = 'Contactar con profesionales';
      break;

    case $url_comp:

      // WRONG, MUST BE ALWAYS IN ENGLISH, IF t() != ENGLISH IS WRONG!
      $titulo = 'Buscar empresas';
      break;
  }

  $cadena = '';
  $items = array(
    'free',
    'name',
    'profile_apellido',
    'ciudad',
    'profile_empresa',
    'profile_cargo',
    'sector',
    'profile_sector',
    'profile_state',
    'provincia',
  );

  // Write less, do more.
  foreach ($_GET as $item) {
    if (!empty($item) AND in_array($item, $items)) {
      $cadena = $_GET[$item];
    }
  }

  /*
  What happend if there are 2000 fields? write 4000 lines? ...

  This search engine structure is bad, only last field will be searched,
  what happend if you search a name in some city? only take the city field.

  if (!empty($_GET['free']))
    $cadena = $_GET['free'];
  else if (!empty($_GET['name']))
    $cadena = $_GET['name'];
  else if (!empty($_GET['profile_apellido']))
    $cadena = $_GET['profile_apellido'];
  else if (!empty($_GET['ciudad'])) <-- COPY/PASTE = DUPLICATE ENTRYS ¬¬...
    $cadena = $_GET['ciudad'];
  else if (!empty($_GET['profile_empresa']))
    $cadena = $_GET['profile_empresa'];
  else if (!empty($_GET['profile_cargo']))
    $cadena = $_GET['profile_cargo'];
  else if (!empty($_GET['ciudad'])) <-- COPY/PASTE = DUPLICATE ENTRYS ¬¬...
    $cadena = $_GET['ciudad'];
  else if (!empty($_GET['sector']))
    $cadena = $_GET['sector'][0];
  else if (!empty($_GET['profile_sector']))
    $cadena = $_GET['profile_sector'][0];
  else if (!empty($_GET['profile_state']))
    $cadena = $_GET['profile_state'];
  else if (!empty($_GET['provincia']))
    $cadena = $_GET['provincia'];
  */

  if (!empty($cadena)) {
    $cadena = filter_xss($cadena); // Avoid XSS Cross-Site Scripting
  }
  $block["content"] = "<h1 id='page_title'>$titulo '$cadena'</h1>";
?>



/******************************************************************************
 * CODIGO SIMPLIFICADO PARA FORMULARIOS DE CONFIGURACION EN ADMINISTRACION    *
 ******************************************************************************/
<?php
$id            = 'id_page_detalle_tax' . $tid;
$twitter       = 'twitter_hashtag_' . $tid;
$url           = 'url_page_detalle_tax' . $tid;
$show          = 'visible_page_detalle_tax' . $tid;
$default_value = variable_get($id, null);
$collapsed     = ($default_value) ? true : false;
$style         = ($collapsed) ? '' : 'background-color:#ffff99';

$form['actuaciones_page'][$cat] = array(
  '#type' => 'fieldset',
  '#collapsible' => true,
  '#collapsed' => $collapsed,
  '#title' => $title,
);

$form['actuaciones_page'][$cat][$id] = array(
  '#type' => 'textfield',
  '#title' => 'ID de página detalle para ' . $name,
  '#default_value' => $default_value,
  '#size' => 40,
  '#description' => "Escriba el ID de la página detalle para '$name'",
  '#field_prefix' => $base_url . '/node/',
  '#required' => false,
  '#element_validate' => array('redes_helper_in_settings_validate'),
  '#attributes' => array('style' => $style),
);

$form['actuaciones_page'][$cat][$twitter] = array(
  '#type' => 'textfield',
  '#title' => 'ID de cuenta de usuario en Twitter para ' . $name,
  '#default_value' => variable_get($twitter, '@redpuntoes'),
  '#size' => 12,
  '#description' => "Escriba el ID de usuario de Twitter para '$name', si
  no se especifíca ningún usuario se tomará @redpuntoes por defecto",
  '#field_prefix' => '@usuario = ',
  '#required' => false,
);
?>






/******************************** CODIGOS *************************************
 * CODIGO MAL CONSTRUIDO / LIOSO / SIN CODING STANDARDS                       *
 ******************************************************************************/
<?php
$date_url = arg(2);
if($date_url){
  $anio_fechaprograma_url = preg_split('/-/',$date_url, -1, PREG_SPLIT_NO_EMPTY);
}else{
  global $fechaprograma;
  $anio_fechaprograma_url = preg_split('/-/',$fechaprograma, -1, PREG_SPLIT_NO_EMPTY);
}
$anio_edicion_select = variable_get('program_select_year', 2010);
$anio_default_select = variable_get('program_default_year', 2010);
$anio_actual_year = variable_get('program_actual_year', 2010);
if(((int)$anio_actual_year ==(int)$anio_fechaprograma_url[0]) && ((int)$anio_fechaprograma_url[0]>(int)$anio_default_select)){
  return TRUE;
}else{
  return FALSE;
}
?>
/******************************************************************************
 * APLICANDO ORGANIZACIÓN Y CODING STANDRADS                                  *
 ******************************************************************************/
<?php

global $fechaprograma;

$arg2   = arg(2);
$conf   = $fechaprograma;
$getted = preg_split('/-/', $arg2, -1, PREG_SPLIT_NO_EMPTY);
$setted = preg_split('/-/', $conf, -1, PREG_SPLIT_NO_EMPTY);

$program_select  = variable_get('program_select_year',  2010);
$program_default = variable_get('program_default_year', 2010);
$program_actual  = variable_get('program_actual_year',  2010);

$year = ($arg2) ? $getted : $setted;

$check1 = ((int) $program_actual == (int) $year[0]);
$check2 = ((int) $year[0] > (int) $program_default);

return ($check1 AND $check2);

?>



/******************************** TEMPLATES ***********************************
 * TEMPLATE MAL CONSTRUIDA / LIOSA / SIN CODING STANDARDS                     *
 ******************************************************************************/
<?php
// $Id: views-view-fields.tpl.php,v 1.6 2008/09/24 22:48:21 merlinofchaos Exp $
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->separator: an optional separator that may appear before a field.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */

?>

<?php if((!empty($fields['field_video_embed']->content)) || (!empty($fields['field_foto_fid']->content))): ?>
  <div class = "noticia-imagen-video"><?php print $fields['field_video_embed']->content ? $fields['field_video_embed']->content : $fields['field_foto_fid']->content; ?></div>
<?php endif; ?>
<div class = "noticia-contenido">
  <div class = "noticia-fecha"><?php print $fields['field_fecha_value']->content; ?></div>
  <div class = "noticia-categoria-antetitulo">
    <span class = "noticia-categoria">
      <?php
        if(!empty($fields['tid']->handler->items[$fields['nid']->content])){
          foreach($fields['tid']->handler->items[$fields['nid']->content] as $tid => $value){
            print l($value['name'], url('search',array('query' => 'cat='.$value['tid'], 'absolute' => TRUE)), array('attributes' => array('title' => $value['name'])));
          }
        }
      ?>
    </span>
    <?php if (!empty($fields['field_antetitulo_value']->content)): ?>
      - <span class = "noticia-antetitulo"><?php print $fields['field_antetitulo_value']->content; ?></span>
    <?php endif; ?>
  </div>
  <h2 class = "noticia-titulo"><?php print l($fields['title']->content, "node/" . $fields['nid']->content); ?></h2>
  <div class = "noticia-cuerpo"><?php print $fields['field_entradilla_value']->content; ?></div>
  <div class = "noticia-ver-mas"><?php print l(t('Read more'), "node/" . $fields['nid']->content); ?></div>
</div>
/******************************************************************************
 * APLICANDO CODING STANDARS Y USANDO VARIABLES (NO MUERDEN!)                 *
 ******************************************************************************/
<?php
// $Id: views-view-fields.tpl.php,v 1.6 2008/09/24 22:48:21 merlinofchaos Exp $
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->separator: an optional separator that may appear before a field.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */

  $nid            = $fields['nid']->content;
  $titulo         = $fields['title']->content;
  $entradilla     = $fields['field_entradilla_value']->content;
  $antetitulo     = $fields['field_antetitulo_value']->content;
  $video          = $fields['field_video_embed']->content;
  $imagen         = $fields['field_foto_fid']->content;
  $fecha          = $fields['field_fecha_value']->content;
  $url            = 'node/' . $nid;
  $options        = array('html' => true);
  $titulo         = l($titulo, $url, $options);
  $leer_mas       = l(t('Read more'), 'node/' . $nid);
  $fields_content = $fields['tid']->handler->items[$nid];
  $categorias     = '';

  if (!empty($fields_content)) {

    foreach ($fields_content as $tid => $value) {

      $attributes = array(
        'attributes' => array(
          'title'    => $value['name'],
          'html'     => true,
        ),
      );

      $query       = 'cat=' . $value['tid'];
      $options = array(
        'query'    => $query,
        'absolute' => true,
      );

      $url         = url('search', $options);
      $categorias .= l($value['name'], $url, $attributes);
    }
  }
?>

<?php if ((!empty($video)) || (!empty($imagen))): ?>

<div class="noticia-imagen-video">
  <?php echo ($video) ? $video : $imagen; ?>
</div>

<?php endif; ?>

<div class="noticia-contenido">

  <div class="noticia-fecha">
    <?php echo $fecha ?>
  </div>

  <div class="noticia-categoria-antetitulo">
    <span class="noticia-categoria">
      <?php echo $categorias ?>
    </span>
    <?php if (!empty($antetitulo)): ?> -
    <span class="noticia-antetitulo">
      <?php echo $antetitulo ?>
    </span>
    <?php endif; ?>
  </div>

  <h2 class="noticia-titulo">
    <?php echo $titulo ?>
  </h2>

  <div class="noticia-cuerpo">
    <?php echo $entradilla ?>
  </div>

  <div class="noticia-ver-mas">
    <?php echo $leer_mas ?>
  </div>

</div>
/******************************************************************************
 * Imprescindible indicar en las templates el archivo que es con:             *
 ******************************************************************************/
<?php echo '<!--' . basename(__FILE__) . "-->\n"; ?>

O más fácil:

<!--<?php echo basename(__FILE__) ?>-->

Por que si no no hay dios que se entere de que archivo es el que está procesando

/******************************************************************************
 * COMPLICACIONES DE CÓDIGO INNECESARIAS                                      *
 ******************************************************************************/
<?php

// BEFORE
function localizer_language_in_path($path) {

  $exploded_path  = explode('/', $path);
  $languageinpath = $exploded_path[0];

  if(localizer_isvalid_language($languageinpath)){
    return $languageinpath;
  }
  else
  {
    return '';
  }
}

// AFTER
function localizer_language_in_path($path) {

  $lang_code = array_shift(explode('/', $path));

  return (localizer_isvalid_language($lang_code)) ? $lang_code : '';
}



// Mejoras o tricks:

unset($array[count($array) - 1]); = array_pop($array);
echo $array[count($array) - 1];   = echo end($array);

if ($var == 'one' || $var == 'two' || $var == 'three' || $var == 'four') { }
=
if (in_array($var, array('one', 'two', 'three', 'four'))) { }

$id_arbol_de_taxonomias = taxonomy_get_tree(69);
$form = array($varname_very_long);
$varname_very_long['id_text_very_long']['new_id_text_very_long']['#suffix'] = '';

$mfvl = my_function_very_long();
$var  = array($mfvl)


// En los arrays es mucho mejor usar:

$array = array(
  'casa' => 'roja',
  'coche' => 'verde',
);

extract($array);
echo 'Mi casa es ' . $casa . ' y mi coche ' . $coche;

// que:

echo 'Mi casa es ' . $array['casa'] . ' y mi coche ' . $array['coche'];


/*
Nombres en los presets de imagecache, nada de medidas!

110x66px              Normal  Editar    Eliminar    Flush    Exportar
138x90px              Normal  Editar    Eliminar    Flush    Exportar
180x120               Normal  Editar    Eliminar    Flush    Exportar
207x140px             Normal  Editar    Eliminar    Flush    Exportar
451x232px             Normal  Editar    Eliminar    Flush    Exportar
451x98px              Normal  Editar    Eliminar    Flush    Exportar
591x331px             Normal  Editar    Eliminar    Flush    Exportar
banner_pie_carousel   Normal  Editar    Eliminar    Flush    Exportar
banner_pie_destacado  Normal  Editar    Eliminar    Flush    Exportar
width_640px           Normal  Editar    Eliminar    Flush    Exportar
*/



// Pero por qué escribimos spanglish!?
define('VOCABULARY_CATEGORIA', 2);
define('VOCABULARY_PROCEDIMIENTO', 4);



// Códigos extraños:
$variable1 = "<span class=\"class\">".$variable2."</span>";
$variable2 = "<span class='class'>$variable2</span>";



// Nada de ids de nodo / taxonomía en el código, y que pasa si cambian?
switch ($branch['link']['mlid']) {

  case 884:
  case 2194:
    $type = 'television';
    break;

  case 1090:
  case 1091:
    $type = 'cine';
    break;

  case 1543:
  case 2193:
    $type = 'music';
    break;

  case 1547:
  case 1551:
    $type = 'theatre';
    break;
}

$nolinks = array(2194, 884);
$nothing = array(1090, 1536, 1547, 1091, 1543, 1551, 2193);



// Hook para sobreescribir una entrada de menu, por ejemplo node/add
function tcontact_menu_alter(&$callbacks) {
  $callbacks['admin/build/contact']['page callback'] = 'tcontact_admin_categories';
  unset($callbacks['admin/build/contact']['file']);
}

/******************************************************************************
 * Código de ejemplo súcio                                                    *
 ******************************************************************************/
/**
 * Preprocess Theme the AddThis button.
 */
function phptemplate_addthis_button($node, $teaser) {
  global $_addthis_counter;

  // Fix IE's bug.
  if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) {

    $protocol = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';

    drupal_add_link(array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' => $protocol."s7.addthis.com/static/r07/widget02.css",
    ));
  }
  if (variable_get('addthis_dropdown_disabled', '0')) {
    $button = sprintf('
      <a class="addthis-button" href="http://www.addthis.com/bookmark.php"
        onclick="addthis_url   = location.href; addthis_title = document.title; return addthis_click(this);" target="_blank" >Share</a>'
    );
  }
  else {
    if ($_addthis_counter == 1) {
      /*$external_js = $_SERVER['HTTPS'] == 'on' ? 'https://secure.addthis.com' : 'http://s7.addthis.com'.'/js/'.variable_get('addthis_widget_version', '152').'/addthis_widget.js';
      drupal_add_js('document.write(unescape("%3Cscript src=\''.
      $external_js .'\' type=\'text/javascript\'%3E%3C/script%3E"));', 'inline');*/
      $external_js = $_SERVER['HTTPS'] == 'on'
          ? 'https://secure.addthis.com'
          : 'http://s7.addthis.com';
        $external_js .= '/js/' . variable_get('addthis_widget_version', '152');
        $external_js .= '/addthis_widget.js';
        $js = "
          Drupal.behaviors.LoadExternalJS = function(context) {
            $.getScript('$external_js');
          };
        ";
        drupal_add_js($js, 'inline');
    }
      $button.=sprintf('<a class="addthis-button" href="http://www.addthis.com/bookmark.php"
        onfocus="return addthis_open(this, \'\', \'%s\', \'%s\')"
        onmouseover="return addthis_open(this, \'\', \'%s\', \'%s\')"
        onblur="addthis_close()"
        onmouseout="addthis_close()"
        onclick="return addthis_sendto()">Share</a>',
      $teaser ? url('node/'. $node->nid, array('absolute' => 1) ) : '[URL]',
      $teaser ? addslashes($node->title) : '[TITLE]',
      $teaser ? url('node/'. $node->nid, array('absolute' => 1) ) : '[URL]',
      $teaser ? addslashes($node->title) : '[TITLE]'
      );
  }
  return $button;
}


/******************************************************************************
 * Código de ejemplo límpio (line length <= 80)                               *
 ******************************************************************************/
/**
 * Preprocess Theme the AddThis button.
 */
function phptemplate_addthis_button($node, $teaser) {

  global $base_url, $_addthis_counter;

  // Fix IE's bug.
  if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) {

    $protocol = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';

    $link = array(
      'rel'  => 'stylesheet',
      'type' => 'text/css',
      'href' => $protocol . 's7.addthis.com/static/r07/widget02.css',
    );
    drupal_add_link($link);
  }

  if (variable_get('addthis_dropdown_disabled', '0')) {

    $onclick  = 'addthis_url = location.href; addthis_title = document.title;';
    $onclick .= 'return addthis_click(this);';

    $attributes = array(
      'attributes' => array(
        'class'   => 'addthis-button',
        'onclick' => $onclick,
        'target'  => '_blank',
      ),
    );
    $button = l(t('Share'), 'http://www.addthis.com/bookmark.php', $attributes);
  }
  else {

    if ($_addthis_counter == 1) {

      $http  = 'http://s7.addthis.com';
      $https = 'https://secure.addthis.com';

      $external_js  = ($_SERVER['HTTPS'] == 'on') ? $https : $http;
      $external_js .= '/js/' . variable_get('addthis_widget_version', '152');
      $external_js .= '/addthis_widget.js';

      $js = "
        Drupal.behaviors.LoadExternalAddThisJS = function(context) {
          $.getScript('$external_js');
        };
      ";
      drupal_add_js($js, 'inline');
    }

    $url   = ($teaser) ? $base_url . '/node/' . $node->nid : '[URL]';
    $title = ($teaser) ? addslashes($node->title) : '[TITLE]';

    $onfocus     = "return addthis_open(this, '', '$url', '$title')";
    $onmouseover = "return addthis_open(this, '', '$url', '$title')";
    $onblur      = 'addthis_close()';
    $onmouseout  = 'addthis_close()';
    $onclick     = 'return addthis_sendto()';
    $attributes  = array(
      'attributes'    => array(
        'class'       => 'addthis-button',
        'onfocus'     => $onfocus,
        'onmouseover' => $onmouseover,
        'onblur'      => $onblur,
        'onmouseout'  => $onmouseout,
        'onclick'     => $onclick,
      ),
    );
    $anchor  = l(t('Share'), 'http://www.addthis.com/bookmark.php', $attributes);
    $button .= $anchor;
  }

  return $button;
}
