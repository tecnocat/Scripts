<?php /* UTF-8 Verified (áéíóú) */

// $Id$

/*
 * Implements hook_block($op = 'list', $delta = 0, $edit = array())
 */
function empresa_block($op = 'list', $delta = 0, $edit = array()) {
  switch ($op) {
    case 'list':
      $block[0] = array('info' => t('Companies search form.'),);
      $block[1] = array('info' => t('List of company\'s employees.'),);
      $block[2] = array('info' => t('List of recent company\'s changues.'),);

      break;

    case 'view':
      switch ($delta) {
        case 0: $block['content'] = drupal_get_form('empresa_busqueda_empresas_form'); break;
        case 1: $block['content'] = empresa_empleados(); break;
        case 2: $block['content'] = empresa_ultimos_cambios($op); break;
      }
      break;
  }
  return $block;
}

/**
 * return img list of employees
 */
function empresa_empleados() {
  $node = node_load(arg(1));
  $query = "
    SELECT
      u.uid AS uid,
      p.value AS empresa,
      u.picture AS foto,
      u.name AS nombre,
      RAND() AS _random
    FROM users u
      LEFT JOIN profile_values p ON u.uid = p.uid
      AND p.fid = '19'
    WHERE
      p.value = '" . $node->title . "'
      AND u.picture <> ''
    ORDER BY _random ASC
    LIMIT 0, 60
  ";
  $data = db_query($query);
  $empty = true;
  while ($row = db_fetch_array($data)) {
    if ($empty) { $return = '<h2 class="pane-title">' . t('Work in !company',array('!company' => $node->title)) . '</h2>'; }
    $empty = false;
    $row['foto'] = str_replace('sites/default/', '', $row['foto']);
    $src = base_path() . 'sites/default/' . str_replace('files', 'files/imagecache/empresa_empleado', $row['foto']);
    $name = trim($row['nombre']);
    $attributes = array('html' => true);
    $link = 'user/' . $row['uid'];
    $return .= l('<img class="employee" src="' . $src . '" alt="' . $name . '" title="' . $name . '" />',$link,$attributes);
  }
  return $return;
}

/**
 * return or update data of last changues of company
 */
function empresa_ultimos_cambios($op = 'view', $node = null) {
  global $language;
  $nid = arg(1);
  $cid = 'empresa_updates:' . $language->language . ':' . $nid;
  $cache_table = 'cache_block';
  switch ($op) {

    case 'view':
      if (is_numeric($nid)) {
        $cache = cache_get($cid, $cache_table);
        if (empty($cache->data)) {
          $query = "
            SELECT nid,uid,field,date
            FROM {empresa_updates}
            WHERE nid = $nid
            GROUP BY field
            ORDER BY date DESC
          ";
          $result = db_query($query);
          while ($row = db_fetch_array($result)) {
            $data[] = $row;
          }
          cache_set($cid, $data, $cache_table);
        }
        else {
          $data = $cache->data;
        }
        return theme('lastupdates', array('data' => $data));
      }
      break;

    case 'compare':
      $old = node_load($node->nid);
      $new = $node;
      $fields = array(
        'field_empresa_direccion' => 'value',
        'field_empresa_ciudad' => 'value',
        'field_empresa_cp' => 'value',
        'field_empresa_pais' => 'value',
        'field_empresa_provincia' => 'value',
        'field_empresa_sector' => 'value',
        'field_empresa_telefono' => 'value',
        'field_empresa_web' => 'value',
        'field_empresa_email' => 'email',
        'field_empresa_descripcion' => 'value',
        'field_empresa_logo' => 'filepath',
        'field_empresa_video' => 'embed',
        'field_empresa_activitystream' => 'value',
      );
      if ($old->title != $new->title) { $compare['field_empresa_nombre'] = 1; }
      foreach ($fields as $field => $name) {
        if ($field == 'field_empresa_web') {
          for ($i = 0; $i <= 3; $i++) {
            if ($old->{$field}[$i][$name] != $new->{$field}[$i][$name]) {
              $compare[$field] = 1;
            }
          }
        }
        else {
          if ($old->{$field}[0][$name] != $new->{$field}[0][$name]) {
            $compare[$field] = 1;
          }
        }
      }
      foreach ($old as $field => $dummy) {
        if (ereg('^field_empresa_c[0-9]',$field)) {
          if ($old->{$field}[0]['value'] != $new->{$field}[0]['value']) {
            $compare['field_empresa_competencias'] = 1;
          }
        }
      }
      $translations = array(
        'field_empresa_nombre' => 'the name',
        'field_empresa_direccion' => 'the address',
        'field_empresa_ciudad' => 'the city',
        'field_empresa_cp' => 'the postal code',
        'field_empresa_pais' => 'the state',
        'field_empresa_provincia' => 'the country',
        'field_empresa_sector' => 'the sector',
        'field_empresa_telefono' => 'the phone',
        'field_empresa_web' => 'the website',
        'field_empresa_email' => 'the e-mail',
        'field_empresa_descripcion' => 'the description',
        'field_empresa_logo' => 'the logo',
        'field_empresa_video' => 'the video',
        'field_empresa_activitystream' => 'the lifestream',
        'field_empresa_competencias' => 'the competences',
      );
      if (!empty($compare)) {
        $update = false;
        foreach ($compare as $field => $dummy) {
          if (!$update) {
            $query = "INSERT INTO {empresa_updates} (nid,uid,field,date) VALUES ";
            $update = true;
          }
          $query .= "('" . $node->nid . "','" . $node->uid . "','" . $translations[$field] . "','" . time() . "'),";
        }
        $query = substr($query,0,strlen($query) - 1);
        db_query($query);
        db_query("DELETE FROM {" . $cache_table . "} WHERE cid = '" . $cid . "'");
      }
      break;
  }
}

/**
 * update data of employees that worked with them
 */
function _empresa_get_workers() {
  $die = '<h2 class="rosa">' . t('ERROR: This block it\'s only for node type empresa') . '</h2>';
  $type = $_GET['get'];
  if ($type == 'theworkers' || $type == 'thelikeds') {
    $nid = $_GET['nid'];
    $uid = $_GET['uid'];
    $node = node_load($nid);
    if ($node->type == 'empresa') {
      $data = ($type == 'thelikeds')
      ? unserialize($node->field_empresa_admiradores[0]['value'])
      : unserialize($node->field_empresa_empleados[0]['value']);
      if (empty($data[$uid])) {
        $data[$uid] = $uid;
        if ($type == 'theworkers') {
          $node->field_empresa_empleados[0]['value'] = serialize($data);
        }
        if ($type == 'thelikeds') {
          $node->field_empresa_admiradores[0]['value'] = serialize($data);
        }
        node_save($node);
        $die = ($type == 'thelikeds')
        ? t('You have been marked successfully as you like %company',array('%company' => $node->title))
        : t('You have been added successfully to worker\'s list of %company',array('%company' => $node->title));
      }
      else {
        $die = ($type == 'thelikeds')
        ? t('Sorry, you can not select again that you like %company',array('%company' => $node->title))
        : t('Sorry, you can not select again that you are work on %company',array('%company' => $node->title));
      }
    }
  }
  die($die);
}