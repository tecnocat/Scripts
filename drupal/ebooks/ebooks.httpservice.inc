<?php /* UTF-8 Verified (áéíóú) */

function _ebooks_user_access($uid, $nid) {
  $user = user_load($uid);
  if (user_access('editar ebooks', $user)) {
    return true;
  }
  else {
    $result_nodes = db_query("
      SELECT n.nid
      FROM {node} n
        LEFT JOIN {nodeaccess} a ON n.nid = a.nid
      WHERE n.type = 'ebook'
        AND n.status != 0
        AND n.nid = %d
        AND a.gid = %d
        OR  a.gid = 1
        AND a.grant_view = 1
    ", $nid, $uid);
    $result_taxonomys = db_query("
      SELECT n.nid
      FROM {node} n
        LEFT JOIN {term_node} t ON n.nid = t.nid
        LEFT JOIN {term_access_user} a ON t.tid = a.tid
      WHERE n.type = 'ebook'
        AND n.status != 0
        AND n.nid = %d
        AND a.uid = %d
        AND a.grant_view = 1
    ", $nid, $uid);
    if (db_result($result_nodes)) { return true; }
    if (db_result($result_taxonomys)) { return true; }
  }
  return false;
}

function _ebooks_pdf2swf() {
  $result = ebooks_cron(true);
  foreach ($result as $stats) {
    $microtime = (float)$stats['end'] - (float)$stats['start'];
    $time      = round($microtime, 3);
    $title     = $stats['title'];
    $pages     = $stats['pages'];
    $speed     = round((float)$pages / (float)$microtime, 3);
    $replaces  = array(
      '!time'  => $time,
      '!title' => $title,
      '!pages' => $pages,
      '!speed' => $speed,
    );
    drupal_set_message(t('eBook "!title" was processed in !time secs. (!pages pages @ !speed pages/sec)', $replaces));
  }
  drupal_goto('administrar-libros');
}

function _ebooks_return_xml($data, $enclosure = 'XMLStructure', $tab = 0) {
  $n = "\n";
  $t = str_repeat(" ", $tab++);
  $xml = "$t<$enclosure>$n";
  if (is_object($data)) { $data = (array) $data; }
  if (is_array($data)) {
    foreach ($data as $tag => $val) {
      if (is_numeric($tag)) {
        $tag = 'item';
      }
      $xml .= _ebooks_return_xml($val, $tag, $tab);
    }
  }
  else {
    $xml .= "$t <![CDATA[$data]]>$n";
  }
  $t = str_repeat(" ", --$tab);
  $xml .= "$t</$enclosure>$n";
  return $xml;
}

function _ebooks_http_service() {
  $mode = $_POST['mode'];
  unset($request);
  foreach ($_POST as $var => $val) {
    $request .= $var . '=' . $val . ' ';
  }
  watchdog('ebook', t('Request: %request.'), array('%request' => $request));
  switch ($mode) {
    case 'init': $data = _ebooks_http_service_init(); break; // catalog
    case 'view': $data = _ebooks_http_service_view(); break; // ebook
    case 'save': $data = _ebooks_http_service_save(); break; // ebook objects
    case 'rest': $data = _ebooks_http_service_rest(); break; // ebook page
    case 'undo': $data = _ebooks_http_service_undo(); break; // ebook element
    default:
      $data = array('status' => 'error', 'result' => t('Invalid option.'));
      break;
  }
  $xml = _ebooks_return_xml($data);
  header("Content-type: text/xml");
  die($xml);
}

function _ebooks_http_service_init() {
  global $base_url;
  global $user;
  $uid = $user->uid;
  if (user_access('editar ebooks', $user)) {
    $fullaccess = true;
  }
  else {
    $LEFT_JOIN = 'LEFT JOIN {nodeaccess} a ON n.nid = a.nid';
    $AND = 'AND a.gid = %d OR a.gid = 1 AND a.grant_view = 1';
    $fullaccess = false;
  }
  $result_nodes = db_query("
    SELECT n.nid
    FROM {node} n
      LEFT JOIN {content_type_ebook} c ON n.nid = c.nid
      $LEFT_JOIN
    WHERE n.type = 'ebook'
      AND n.status != 0
      AND c.field_ebook_status_value = 1
      $AND
    GROUP BY n.nid
    ORDER BY n.nid ASC
  ", $uid);
  if (!$fullaccess) {
    $result_taxonomys = db_query("
      SELECT n.nid
      FROM {node} n
        LEFT JOIN {term_node} t ON n.nid = t.nid
        LEFT JOIN {term_access_user} a ON t.tid = a.tid
        LEFT JOIN {content_type_ebook} c ON n.nid = c.nid
      WHERE n.type = 'ebook'
        AND n.status != 0
        AND c.field_ebook_status_value = 1
        AND a.grant_view = 1
        AND a.uid = %d
      GROUP BY n.nid
      ORDER BY n.nid ASC
    ", $uid);
    while ($row = db_fetch_object($result_taxonomys)) {
      $nodes_allowed[$row->nid] = $row->nid;
    }
  }
  while ($row = db_fetch_object($result_nodes)) {
    $nodes_allowed[$row->nid] = $row->nid;
  }
  $categorias[] = array(
    'categoria' => array(
      'categoriaid' => 0,
      'descripcion' => 'Todas',
    ),
  );
  if ($nodes_allowed) {
    foreach ($nodes_allowed as $nid => $dummy) {
      $node = node_load($nid);
      $imagen = $node->field_ebook_imagen[0]['filepath']
      ? $base_url . '/' . $node->field_ebook_imagen[0]['filepath']
      : $base_url . '/sites/default/files/private/processed-ebooks-swf/' . $node->nid . '/eBook-page-1.swf';
      $fichero = $base_url . '/' . $node->field_ebook_pdf[0]['filepath'];
      $localfile = dirname($_SERVER['SCRIPT_FILENAME']) . str_replace($base_url, '', $imagen);
      $imagen = str_replace('sites/default/files', 'system/files', $imagen);
      $fichero = str_replace('sites/default/files', 'system/files', $fichero);
      if (file_exists($localfile)) {
        $imgdata = getimagesize($localfile);
      }
      else {
        $imgdata = array(100, 100);
      }
      $ancho = $imgdata[0];
      $alto = $imgdata[1];
      $libros[] = array(
        'libro' => array(
          'libroid' => $node->nid,
          'titulo' => $node->title,
          'imagen' => $imagen,
          'descripcion' => $node->body,
          'codlibro' => $node->field_ebook_codigo[0]['value'],
          'fichero' => $fichero,
          'estado' => $node->status,
          'ancho' => $ancho,
          'alto' => $alto,
        ),
      );
      foreach ($node->taxonomy as $taxonomy) {
        $taxonomias[$taxonomy->tid]['tid'] = $taxonomy->tid;
        $taxonomias[$taxonomy->tid]['name'] = $taxonomy->name;
        $taxonomias[$taxonomy->tid]['nodos'][] = $node->nid;
      }
    }
    foreach ($taxonomias as $taxonomia) {
      $categorias[] = array(
        'categoria' => array(
          'categoriaid' => $taxonomia['tid'],
          'descripcion' => $taxonomia['name'],
          'nodos' => $taxonomia['nodos'],
        ),
      );
    }
    asort($categorias);
    $status = 'success';
    $result = array(
      'libros' => $libros,
      'categorias' => $categorias,
    );
  }
  else {
    $status = 'error';
    $result = t('Sorry, your user does not have access to any eBook or the eBook\'s database it\'s empty.');
  }
  return array('option' => 'init', 'status' => $status, 'result' => $result);
}

function _ebooks_http_service_view() {
  global $user;
  global $base_url;
  if (_ebooks_user_access($user->uid, $_POST['libro'])) {
    if (!$_POST['libro']) {
      return _ebooks_http_service_init();
    }
    else {
      $node = node_load($_POST['libro']);
      $pageobjects = unserialize($node->field_ebook_objetos[0]['value']);
      $imagen = $node->field_ebook_imagen[0]['filepath']
      ? $base_url . '/' . $node->field_ebook_imagen[0]['filepath']
      : $base_url . '/sites/default/files/private/processed-ebooks-swf/' . $node->nid . '/eBook-page-1.swf';
      $fichero = $base_url . '/' . $node->field_ebook_pdf[0]['filepath'];
      $localfile = dirname($_SERVER['SCRIPT_FILENAME']) . str_replace($base_url, '', $imagen);
      $imagen = str_replace('sites/default/files', 'system/files', $imagen);
      $fichero = str_replace('sites/default/files', 'system/files', $fichero);
      if (!file_exists($localfile)) {
        $imgdata = array(100, 100);
      }
      else {
        $imgdata = getimagesize($localfile);
      }
      if (!$imgdata[0]) {
        $imgdata = getswfsize($localfile);
      }
      $ancho = $imgdata[0];
      $alto = $imgdata[1];
      $libro = array(
        'libro' => array(
          'libroid' => $node->nid,
          'titulo' => $node->title,
          'imagen' => $imagen,
          'descripcion' => $node->body,
          'codlibro' => $node->field_ebook_codigo[0]['value'],
          'fichero' => $fichero,
          'Estado' => $node->status,
          'ancho' => $ancho,
          'alto' => $alto,
        ),
      );
      $swfpath = $base_url . '/system/files/private/processed-ebooks-swf/' . $node->nid . '/';
      $pagina = 1;
      $total = $node->field_ebook_paginas[0]['value'];
      while ($pagina <= $total) {
        $swf = $swfpath . 'eBook-page-' . $pagina . '.swf';
        unset($objetos);
        if ($pageobjects[$pagina]) {
          foreach ($pageobjects[$pagina] as $object) {
            foreach ($object as $id => $val) {
              $objeto[$id] = $val;
            }
            $objeto['objetoid'] = rand(1,999);
            $objetos[] = array('objeto' => $objeto);
          }
        }
        $libroswf[] = array(
          'pagina' => array(
            'paginaid' => $pagina,
            'ruta' => $swf,
            'objetos' => $objetos,
          ),
        );
        $pagina++;
      }
      $libro['libro']['libroSWF'] = $libroswf;
      $data = array(
        'libros' => $libro,
      );
      $status = 'success';
      $result = $data;
    }
  }
  else {
    $status = 'error';
    $result = t('Permission denied.');
  }
  return array('option' => 'view', 'status' => $status, 'result' => $result);
}

function _ebooks_http_service_save() {
  $xml = xml_parser_string_to_array($_POST['xml']);
  $libro = $_POST['libro'];
  $pagina = $_POST['pagina'];
  array_shift($xml);
  if ($xml['object']) {
    $xml[0] = $xml['object'];
    unset($xml['object']);
  }
  $node = node_load($libro);
  $pageobjects = unserialize($node->field_ebook_objetos[0]['value']);
  if ($xml) {
    $pageobjects[$pagina] = $xml;
  }
  else {
    unset($pageobjects[$pagina]);
  }
  $node->field_ebook_objetos[0]['value'] = serialize($pageobjects);
  node_save($node);
  return array('option' => 'save', 'status' => 'success', 'result' => 'null');
}

function _ebooks_http_service_rest() {
  $dirname = dirname($_SERVER['SCRIPT_FILENAME']);
  $path = $dirname . '/sites/default/files/private/processed-ebooks-swf/' . $_POST['libro'] . '/' . $_POST['pagina'];
  $command = 'rm -rf ' . $path;
  exec($command);
  $node = node_load($_POST['libro']);
  $pageobjects = unserialize($node->field_ebook_objetos[0]['value']);
  unset($pageobjects[$_POST['pagina']]);
  $node->field_ebook_objetos[0]['value'] = serialize($pageobjects);
  node_save($node);
  return array('option' => 'rest', 'status' => 'success', 'result' => 'null');
}

function _ebooks_http_service_undo() {
  $dirname = dirname($_SERVER['SCRIPT_FILENAME']);
  $objeto = basename($_POST['objeto']);
  $path = $dirname . '/sites/default/files/private/processed-ebooks-swf/' . $_POST['libro'] . '/' . $_POST['pagina'] . '/' . $objeto;
  $command = 'rm -f ' . $path;
  if (is_file($path)) {
    exec($command);
  }
  return array('option' => 'undo', 'status' => 'success', 'result' => 'null');
}

function _ebooks_http_upload_object() {
  unset($request);
  foreach ($_POST as $var => $val) {
    $request .= $var . '=' . $val . ' ';
  }
  watchdog('ebook', t('Upload: %request.'), array('%request' => $request));
  global $base_url;
  $ext = $_POST['ext'];
  $libro = $_POST['libro'];
  $pagina = $_POST['pagina'];
  if ($_FILES['Flex']['error'] == 0) {
    $dirname = dirname($_SERVER['SCRIPT_FILENAME']);
    $path = $dirname . '/sites/default/files/private/processed-ebooks-swf/' . $libro . '/' . $pagina;
    $file = str_replace('.', '-', uniqid('', true)) . $ext;
    $filepath = $path . '/' . $file;
    $search = array($dirname, 'sites/default/files');
    $replace = array($base_url, 'system/files');
    $urlpath = str_replace($search, $replace, $filepath);
    $dir_created = true;
    if (!is_dir($path)) {
      $dir_created = false;
      if (!mkdir($path)) {
        watchdog('ebook', t('eBook upload system can not create the necessary structure in the folder %swfpath due to permission restrictions, please change the permissions on this folder to 777.'), array('%swfpath' => $filepath), WATCHDOG_ERROR, l('See how to change permisions on Unix systems','http://es.wikipedia.org/wiki/Chmod'));
      }
      else {
        $dir_created = true;
      }
    }
    if ($dir_created) {
      if (move_uploaded_file($_FILES['Flex']['tmp_name'], $filepath)) {
        $status = 'success';
        $result = $urlpath;
      }
      else {
        $status = 'error';
        $result = 'No se pudo subir el archivo al servidor.';
      }
    }
    else {
      $status = 'error';
      $result = 'Error de permisos en el servidor.';
    }
  }
  else {
    $status = 'error';
    $result = 'Error inesperado, reinténtelo de nuevo.';
  }
  header("Content-type: text/xml");
  $data = array('option' => 'file', 'status' => $status, 'result' => $result);
  die(_ebooks_return_xml($data, 'testupload'));
}

function _ebooks_show_flex($data) {
  global $base_url;
  $data['libro'] = $_REQUEST['libro'];
  $data['pagina'] = $_REQUEST['pagina'];
  $data['baseurl'] = $base_url;
  $data['service'] = $base_url . '/HTTPService';
  $data['flexurl'] = $base_url . '/sites/all/modules/ebooks/flex/';
  if (!$data['swf']) { $data['swf'] = 'VisorLibros'; }
  die(theme('flexviewer', $data));
}

function bin2dec($binstring) {
  $decvalue = 0;
  for ($i = 0; $i < strlen($binstring); $i++) {
    $decvalue += ((int) substr($binstring, strlen($binstring) - $i - 1, 1)) * pow(2, $i);
  }
  return $decvalue;
}

function getswfsize($file) {
  $handle = fopen($file, 'rb');
  $data = fread($handle, filesize($file));
  fclose($handle);
  $signature = substr($data, 0, 3);
  switch ($signature) {

    case 'FWS':
      $compressed = false;
      break;

    case 'CWS':
      $compressed = true;
      break;

    default:
      return array('error' => t('Expecting "FWS" or "CWS", found "%signature"', array('%signature' => $signature)));
  }

  if ($compressed) {
    $head = substr($data, 0, 8);
    $data = substr($data, 8);
    if ($decompressed = gzuncompress($data)) {
      $data = $head . $decompressed;
    }
    else {
      return array('error' => t('Error decompressing compressed SWF data.'));
    }
  }

  $bits = (ord(substr($data, 8, 1)) & 0xF8) >> 3;
  $length = ceil((5 + (4 * $bits)) / 8);
  $string = str_pad(decbin(ord(substr($data, 8, 1)) & 0x07), 3, '0', STR_PAD_LEFT);
  for ($i = 1; $i < $length; $i++) {
    $string .= str_pad(decbin(ord(substr($data, 8 + $i, 1))), 8, '0', STR_PAD_LEFT);
  }
  list($w1, $w2, $h1, $h2) = explode("\n", wordwrap($string, $bits, "\n", 1));
  $width = intval(round(bin2dec($w2) / 20));
  $height = intval(round(bin2dec($h2) / 20));
  return array($width, $height);
}