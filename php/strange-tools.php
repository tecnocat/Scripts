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
 * @details    Drupal Devel Tricks.
 * @category   Debug
 * @version    $Id: strange-tools.php 0 2011-10-22 09:14:34 $
 * @author     tecnocat
 * @file       /sites/default/strange-tools.php
 * @date       2011-10-22 09:14:34
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 */

/**
 * Function to find matches files that was deleted on server and keep in {files}
 */
function check_if_files_exists() {

  echo '<pre>';
  echo '<strong><a href="?debug=true">Refresh</a></strong>';
  if (!$_REQUEST['copy']) {
    echo ' - <strong><a href="?debug=true&copy=true">Copy this files</a></strong>';
  }
  echo '<hr />';
  $files   = 0;
  $copys   = 0;
  $query   = "SELECT * FROM {files} ORDER BY fid DESC";
  $result  = db_query($query);
  $orphans = '';
  while ($row = db_fetch_object($result)) {
    if (!file_exists($row->filepath)) {
      $files     = $files + 1;
      $source    = $target = $row->filepath;
      $source    = str_replace(array_pop(explode('_' , $source)) , '', $source);
      $extension = array_pop(explode('.', $target));
      $pattern   = $source . '*.' . $extension;
      if ($copys <= 500) {
        foreach (glob($pattern) as $match) {
          $copys++;
          $orphans .= '<br />';
          $orphans .= 'Archivo huerfano detectado: ';
          $orphans .= '<strong style="color: #f00;">' . $target . '</strong> ';
          $orphans .= 'posible hermano -> ';
          $orphans .= '<strong style="color: #00f;">' . $match . '</strong>';
          if ($_REQUEST['copy']) {
            copy($match, $target);
          }
          break;
        }
      }
    }
  }
  echo '<strong>Quedan ' . $files . ' files huerfanos en la base de datos</strong>';
  echo $orphans;
}

/**
 * Function to delete all nodes based on custom sql query
 *
 * @param $limit (int) max number of nodes to delete
 */
function delete_nodes($limit = 250) {

  $langs = array('en', 'es', 'pt');
  $sites = array('ar', 'br', 'mx', 'us');
  $types = array();

  foreach ($langs as $lang) {
    foreach ($sites as $site) {
      $types[] = $lang . '-' . $site;
    }
  }

  $types = "'" . implode("', '", $types) . "'";
  $query = "
    SELECT DISTINCT n.nid
    FROM node n
    INNER JOIN localizernode l ON l.nid = n.nid
    WHERE l.language IN (%s)
    ORDER BY n.nid ASC
    LIMIT 0, %d;
  ";
  $result = db_query($query, $types, $limit);
  echo '<h1>Deleting nodes</h1>';
  while ($node = db_fetch_object($result)) {
    echo $node->nid . ', ';
    node_delete($node->nid);
  }
}

/**
 * Function to delete nodes inside custom table if node not exists
 */
function delete_nodes_inside() {

  for ($i = 1; $i <= 6; $i++) {

    $query  = "
      SELECT nid, sector_%s
      FROM {menu_productos}
      WHERE SUBSTR(sector_%s, 1, 1) = 'a'
    ";
    $result = db_query($query, $i, $i);

    while ($row = db_fetch_object($result)) {

      $sector = 'sector_' . $i;
      $data   = unserialize($row->$sector);

      foreach ($data as $nodo) {

        $data[$nodo] = (int) $nodo;
        $node = node_load($nodo);

        if (!$node) {
          unset($data[$nodo]);
        }
      }
      $data  = serialize($data);
      $query = "UPDATE {menu_productos} SET sector_%s = '%s' WHERE nid = %d";
      db_query($query, $i, $data, $row->nid);
    }
  }
}

/**
 * Best debugging that var_dump with Drupal integration
 * @param $dump: Variable / Object / Array to debug
 * @param $info: Title of block to identify (default varname)
 * @param $return: false print debug, true return output
 *
 */
function dumpal($dump, $info = false, $return = true) {
  drupal_set_message(dump($dump, $info, $return));
}
function dump($dump, $info = false, $return = false) {

    $back = $dump;
    $dump = $uniqid = uniqid(rand());
    $name = false;
    foreach ($GLOBALS as $variable => $content) {
      if ($content === $uniqid) {
        $name = $variable;
      }
    }
    $dump = $back;

    $output = '<pre style="
      text-align:left;
      margin:0px 0px 10px 0px;
      display:block;
      background:white;
      color:black;
      border:1px solid #ccc;
      padding:5px;
      font-size:11px;
      line-height:14px;
    ">';

    $info = ($info) ? $info : '$' . $name;
    $output .= '<b style="color:red;">' . $info . ':</b><br>';
    $output .= do_dump($dump, '$' . $name);
    $output .= '<b style="color:red;">End ' . $info . '</b></pre>';
  if ($return) { return $output; }
  else { echo $output; }
}
function do_dump(&$dump, $vname = NULL, $tab = NULL, $ref = NULL) {
  if (empty($output)) { $output = ''; }
  $md5 = md5(rand() . rand() . rand() . rand() . rand() . rand());
  $ntab = 4;
  $bat = '<span style="color:#ccc;">|</span><a style="text-decoration:none;" href="#' . $md5 . '" alt="' . $vname . '" title="' . $vname . '">' . str_repeat('&nbsp;',$ntab) . '</a>';
  $ref = $ref . $vname;
  $kvar = 'the_do_dump_recursion_protection_scheme';
  $kname = 'referenced_object_name';

  if (is_array($dump) && isset($dump[$kvar])) {
    $rvar = &$dump[$kvar];
    $rname = &$dump[$kname];
    $type = ucfirst(gettype($rvar));
    $output .= $tab . '<a name="' . $md5 . '">' . $vname . '</a><span style="color:#a2a2a2">' . $type . '</span> = <span style="color:#e87800;">&amp;' . $rname . '</span><br>';
  }
  else {
    $dump = array($kvar => $dump, $kname => $ref);
    $avar = &$dump[$kvar];

    $type = ucfirst(gettype($avar));
    $span = '&nbsp;<span style="color:#a2a2a2;">';
    $color = array(
      'Array' => 'dummy',
      'Object' => 'dummy',
      'String' => 'green',
      'Integer' => 'red',
      'Double' => '#0099c5',
      'Boolean' => '#92008d',
      'NULL' => 'blue',
    );
    $tc = '<span style="color:' . $color[$type] . ';">';
    $cs = '&nbsp;</span>';
    $bs = '</span><br />';
    switch (gettype($avar)) {
      case 'object':
        $output .= $tab . '<a name="' . $md5 . '">' . $vname . $span . $type . '(' . count($avar) . ')</span><br>' . $tab . '(<br>';
        foreach($avar as $name=>$value) { do_dump($value, '[' . $name . ']', $tab . $bat, $ref); }
        $output .= $tab . ')<br>';
        break;

      case 'array':
        $output .= $tab . '<a name="' . $md5 . '">' . ($vname ? $vname . ' =>':'') . $span . $type . '(' . count($avar) . ')</span><br>' . $tab . '(<br>';
        $keys = array_keys($avar);
        foreach($keys as $name) {
          $value = &$avar[$name];
          do_dump($value, '[\'' . $name . '\']', $tab . $bat, $ref);
        }
        $output .= $tab . ')<br>';
        break;
      case 'string': $output .= $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $tc . '"' . dtags($avar) . '"' . $bs; break;
      case 'integer': $output .= $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $tc . $avar . $bs; break;
      case 'double': $type = 'Float'; $output .= $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $tc . $avar . $bs; break;
      case 'boolean': $output .= $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $tc . ($avar == 1 ? 'true':'false') . $bs; break;
      case 'NULL': $output .= $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $tc . 'null' . $bs; break;
      default: $output .= $tab . '<a name="' . $md5 . '">' . $vname . ' =' . $span . $type . '(' . strlen($avar) . ')' . $cs . $avar . '<br>'; break;
    }
    $dump = $dump[$kvar];
  }
  return $output;
}
function dtags($tags) {
  $search = array('<','>',"\n","\r","\n\r","\r\n","\t");
  $string = array('&#60;','&#62;','\n','\r','\n\r','\r\n','\t');
  return str_replace($search,$string,$tags);
}

/**
 * Pentaho migrations toolkit
 */
$server   = 'localhost';
$user     = 'drupal';
$password = 'drupal';
function pentaho_search_relations($database = false) {

  if ($database) {

    $link = db_open($database);

    $query = "
      SELECT COUNT(*) AS NODE_ROWS, n.type AS NODE_TYPE
      FROM $database.node n
      GROUP BY n.type
      ORDER BY COUNT(*) DESC
    ";
    $types = db_fetch(db_query($link, $query));

    $query = "
      SELECT table_name AS TABLE_NAME
      FROM information_schema.columns
      WHERE table_schema = '$database' AND column_name ='nid'
    ";

    $tables = db_fetch(db_query($link, $query));

    foreach ($types as $type) {

      extract($type);

      if ($NODE_ROWS > 30) {

        echo "<hr /><h1>$NODE_TYPE</h1><pre>
SELECT
  `nid` AS nid_tbl_node,
  `type` AS type_tbl_node,
  'es' AS language_tbl_node,
  `title` AS title_tbl_node,
  `uid` AS uid_tbl_node,
  `status` AS status_tbl_node,
  `created` AS created_tbl_node,
  `changed` AS changed_tbl_node,
  `comment` AS comment_tbl_node,
  0 AS promote_tbl_node,
  `moderate` AS moderate_tbl_node,
  `sticky` AS sticky_tbl_node,
  0 AS tnid_tbl_node,
  0 AS translate_tbl_node
FROM `node`
WHERE `type` = '$NODE_TYPE'
ORDER BY `nid` ASC;
        </pre><hr />";

        foreach ($tables as $table) {

          extract($table);

          $query = "
            SELECT *
            FROM $database.node n
            INNER JOIN $database.$TABLE_NAME a ON a.nid = n.nid
            WHERE n.type = '$NODE_TYPE'
            LIMIT 1, 1
          ";

          $result = db_fetch(db_query($link, $query));

          if (count($result)) {

            //echo "\n<!-- $query\n -->\n";
            //echo "<h2>$NODE_TYPE -> $TABLE_NAME</h2>";
            $query  = "SELECT * FROM $TABLE_NAME LIMIT 1, 1";
            $result = db_fetch(db_query($link, $query));
            $fields = array();

            foreach ($result[0] as $field_name => $field_value) {
              $fields[] = "  `$field_name` AS {$field_name}_tbl_$TABLE_NAME";
            }
            /*echo "<pre>
SELECT DISTINCT n.`nid` AS drupal5_nid
FROM `node` n
INNER JOIN `$TABLE_NAME` a ON a.`nid` = n.`nid`
WHERE n.`type` = '$NODE_TYPE'
            </pre>";*/
            $pentaho  = "SELECT\n";
            $pentaho .= implode(",\n", $fields) . "\n";
            $pentaho .= "FROM `$TABLE_NAME`\n";
            $pentaho .= "WHERE `nid` = ?";
            //echo "<pre>$pentaho</pre>";
          }
        }
      }
    }
    db_close($link);
  }
}
//pentaho_search_relations('company_vieja');

/**
 * Ultra-mega-hyper-migration toolkit based on Pentaho and JMeter
 */
//jmeter_migration_nodes('page');
//jmeter_migration_nodes('taxonomy');
function jmeter_migration_nodes($type = 'dummy') {

  static $basepath = 'company-files/';

  // Private function
  function e($error) {
    echo "\n$error\n";
  }

  function jmeter($type = null, $rows = array()) {

    switch ($type) {

      case 'header':

        return '<?xml version="1.0" encoding="UTF-8"?>
<jmeterTestPlan version="1.2" properties="2.1">
  <hashTree>
    <TestPlan guiclass="TestPlanGui" testclass="TestPlan" testname="Importación de contenidos" enabled="true">
      <stringProp name="TestPlan.comments"></stringProp>
      <boolProp name="TestPlan.functional_mode">false</boolProp>
      <boolProp name="TestPlan.serialize_threadgroups">false</boolProp>
      <elementProp name="TestPlan.user_defined_variables" elementType="Arguments" guiclass="ArgumentsPanel" testclass="Arguments" testname="Variables definidas por el Usuario" enabled="true">
        <collectionProp name="Arguments.arguments"/>
      </elementProp>
      <stringProp name="TestPlan.user_define_classpath"></stringProp>
    </TestPlan>
    <hashTree>
      <ThreadGroup guiclass="ThreadGroupGui" testclass="ThreadGroup" testname="Grupo de hilos de testeo" enabled="true">
        <elementProp name="ThreadGroup.main_controller" elementType="LoopController" guiclass="LoopControlPanel" testclass="LoopController" testname="Loop Controller" enabled="true">
          <boolProp name="LoopController.continue_forever">false</boolProp>
          <stringProp name="LoopController.loops">1</stringProp>
        </elementProp>
        <stringProp name="ThreadGroup.num_threads">1</stringProp>
        <stringProp name="ThreadGroup.ramp_time">25</stringProp>
        <longProp name="ThreadGroup.start_time">1234530300000</longProp>
        <longProp name="ThreadGroup.end_time">1234532100000</longProp>
        <boolProp name="ThreadGroup.scheduler">false</boolProp>
        <stringProp name="ThreadGroup.on_sample_error">continue</stringProp>
        <stringProp name="ThreadGroup.duration"></stringProp>
        <stringProp name="ThreadGroup.delay"></stringProp>
      </ThreadGroup>
      <hashTree>
        <HeaderManager guiclass="HeaderPanel" testclass="HeaderManager" testname="Gestor de Cabecera HTTP" enabled="true">
          <collectionProp name="HeaderManager.headers">
            <elementProp name="" elementType="Header">
              <stringProp name="Header.name">Content-Type</stringProp>
              <stringProp name="Header.value">application/x-www-form-urlencoded</stringProp>
            </elementProp>
          </collectionProp>
        </HeaderManager>
        <hashTree/>';
        break;

      case 'elementProp':

        return '
              <elementProp name="' . $rows[0] . '" elementType="HTTPArgument">
                <boolProp name="HTTPArgument.always_encode">false</boolProp>
                <stringProp name="Argument.value">' . $rows[1] . '</stringProp>
                <stringProp name="Argument.metadata">=</stringProp>
                <boolProp name="HTTPArgument.use_equals">true</boolProp>
                <stringProp name="Argument.name">' . $rows[0] . '</stringProp>
              </elementProp>';

      case 'footer':

        return '
      </hashTree>
      <ResultCollector guiclass="GraphVisualizer" testclass="ResultCollector" testname="Grafica de tiempos" enabled="true">
        <boolProp name="ResultCollector.error_logging">false</boolProp>
        <objProp>
          <name>saveConfig</name>
          <value class="SampleSaveConfiguration">
            <time>true</time>
            <latency>true</latency>
            <timestamp>true</timestamp>
            <success>true</success>
            <label>true</label>
            <code>true</code>
            <message>true</message>
            <threadName>true</threadName>
            <dataType>true</dataType>
            <encoding>false</encoding>
            <assertions>true</assertions>
            <subresults>true</subresults>
            <responseData>false</responseData>
            <samplerData>false</samplerData>
            <xml>true</xml>
            <fieldNames>false</fieldNames>
            <responseHeaders>false</responseHeaders>
            <requestHeaders>false</requestHeaders>
            <responseDataOnError>false</responseDataOnError>
            <saveAssertionResultsFailureMessage>false</saveAssertionResultsFailureMessage>
            <assertionsResultsToSave>0</assertionsResultsToSave>
            <bytes>true</bytes>
          </value>
        </objProp>
        <stringProp name="filename"></stringProp>
      </ResultCollector>
      <hashTree/>
      <ResultCollector guiclass="StatGraphVisualizer" testclass="ResultCollector" testname="Tiempos (tabla)" enabled="true">
        <boolProp name="ResultCollector.error_logging">false</boolProp>
        <objProp>
          <name>saveConfig</name>
          <value class="SampleSaveConfiguration">
            <time>true</time>
            <latency>true</latency>
            <timestamp>true</timestamp>
            <success>true</success>
            <label>true</label>
            <code>true</code>
            <message>true</message>
            <threadName>true</threadName>
            <dataType>true</dataType>
            <encoding>false</encoding>
            <assertions>true</assertions>
            <subresults>true</subresults>
            <responseData>false</responseData>
            <samplerData>false</samplerData>
            <xml>true</xml>
            <fieldNames>false</fieldNames>
            <responseHeaders>false</responseHeaders>
            <requestHeaders>false</requestHeaders>
            <responseDataOnError>false</responseDataOnError>
            <saveAssertionResultsFailureMessage>false</saveAssertionResultsFailureMessage>
            <assertionsResultsToSave>0</assertionsResultsToSave>
            <bytes>true</bytes>
          </value>
        </objProp>
        <stringProp name="filename"></stringProp>
      </ResultCollector>
      <hashTree/>
      <ResultCollector guiclass="ViewResultsFullVisualizer" testclass="ResultCollector" testname="Ver Árbol de Resultados" enabled="true">
        <boolProp name="ResultCollector.error_logging">false</boolProp>
        <objProp>
          <name>saveConfig</name>
          <value class="SampleSaveConfiguration">
            <time>true</time>
            <latency>true</latency>
            <timestamp>true</timestamp>
            <success>true</success>
            <label>true</label>
            <code>true</code>
            <message>true</message>
            <threadName>true</threadName>
            <dataType>true</dataType>
            <encoding>false</encoding>
            <assertions>true</assertions>
            <subresults>true</subresults>
            <responseData>false</responseData>
            <samplerData>false</samplerData>
            <xml>true</xml>
            <fieldNames>false</fieldNames>
            <responseHeaders>false</responseHeaders>
            <requestHeaders>false</requestHeaders>
            <responseDataOnError>false</responseDataOnError>
            <saveAssertionResultsFailureMessage>false</saveAssertionResultsFailureMessage>
            <assertionsResultsToSave>0</assertionsResultsToSave>
            <bytes>true</bytes>
          </value>
        </objProp>
        <stringProp name="filename"></stringProp>
      </ResultCollector>
      <hashTree/>
    </hashTree>
  </hashTree>
</jmeterTestPlan>';
        break;

      case 'page':

        $file  = "JMeter-$type.jmx";
        $fp    = fopen($file, 'w');

        fwrite($fp, jmeter('header'));

        foreach ($rows as $node) {

          extract($node);

          // Si el término asociado es de Manuales saltamos
          if (in_array($tipodepagina, array(41, 42))) {
            continue;
          }

          /*
          filename
          filesize
          file
          uid
          $query = "
            SELECT
              `files`.`filepath`,
              `files`.`filemime`,
              `files`.`filesize`
            FROM `image_attach`
            LEFT JOIN `files` ON `files`.`nid` = `image_attach`.`iid`
            WHERE `image_attach`.`nid` = $nid
              AND `files`.`filename` = '_original';
          ";
          $query = "
            SELECT
              `files`.`filename`,
              `files`.`filepath`,
              `files`.`filemime`,
              `files`.`filesize`
            FROM `files`
            WHERE `files`.`nid` = $nid
          ";
          $link  = db_open('company_vieja');
          $files = db_fetch(db_query($link, $query));
          if ($files) {
            print_r($node);
            print_r($files);
          }
          db_close($link);
          */

          $testname  = "Import node $type $nid";
          e("Generating code '$testname'...");

          if (!$departamento OR !$destinatario OR !$tipodepagina) {
            $testname .= ' NOTAX';
          }

          $data = '
        <HTTPSamplerProxy guiclass="HttpTestSampleGui" testclass="HTTPSamplerProxy" testname="' . $testname . '" enabled="true">
          <elementProp name="HTTPsampler.Arguments" elementType="Arguments" guiclass="HTTPArgumentsPanel" testclass="Arguments" enabled="true">
            <collectionProp name="Arguments.arguments">
              ' . jmeter('elementProp', array('type', $type)) . '
              ' . jmeter('elementProp', array('title', $title)) . '
              ' . jmeter('elementProp', array('body', urlencode($body))) . '
              ' . jmeter('elementProp', array('taxonomy[14]', $departamento)) . '
              ' . jmeter('elementProp', array('taxonomy[5][]', $destinatario)) . '
              ' . jmeter('elementProp', array('taxonomy[12]', $tipodepagina)) . '
              ' . jmeter('elementProp', array('nid_reference', $nid_reference)) . '
            </collectionProp>
          </elementProp>
          <stringProp name="HTTPSampler.domain"></stringProp>
          <stringProp name="HTTPSampler.port"></stringProp>
          <stringProp name="HTTPSampler.connect_timeout"></stringProp>
          <stringProp name="HTTPSampler.response_timeout"></stringProp>
          <stringProp name="HTTPSampler.protocol"></stringProp>
          <stringProp name="HTTPSampler.contentEncoding">utf-8</stringProp>
          <stringProp name="HTTPSampler.path">http://172.21.1.94/company_migration/rest/node</stringProp>
          <stringProp name="HTTPSampler.method">POST</stringProp>
          <boolProp name="HTTPSampler.follow_redirects">true</boolProp>
          <boolProp name="HTTPSampler.auto_redirects">false</boolProp>
          <boolProp name="HTTPSampler.use_keepalive">true</boolProp>
          <boolProp name="HTTPSampler.DO_MULTIPART_POST">false</boolProp>
          <stringProp name="HTTPSampler.implementation">Java</stringProp>
          <boolProp name="HTTPSampler.monitor">false</boolProp>
          <stringProp name="HTTPSampler.embedded_url_re"></stringProp>
        </HTTPSamplerProxy>
        <hashTree/>';
          fwrite($fp, $data);
        }

        fwrite($fp, jmeter('footer'));
        fclose($fp);
        break;

      default:
        e("Missing definition for type '$type'. Exit JMeter Subprocess.");
        break;
    }
  }

  switch ($type) {

    case 'page':

      $link  = db_open('company_vieja');
      $query = "
        SELECT
          `node`.`nid`,
          `node`.`type`,
          'es' AS 'language',
          `node`.`title`,
          MAX(`node_revisions`.`body`) AS body,
          `node`.`uid`,
          `node`.`status`,
          `node`.`created`,
          `node`.`changed`,
          `node`.`comment`,
          0 AS promote,
          `node`.`moderate`,
          `node`.`sticky`,
          0 AS tnid,
          0 AS translate,
          CASE
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 82  THEN 54 -- > Secretaría General
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 83  THEN 53 -- > Dirección General
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 84  THEN 63 -- > Informática
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 85  THEN 60 -- > Financiero
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 86  THEN 61 -- > Sanitarias
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 87  THEN 62 -- > Sociales
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 88  THEN 64 -- > Inspección
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 89  THEN 65 -- > Sindicatos
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 92  THEN 56 -- > Asustons Generales
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 103 THEN 57 -- > Formación
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 104 THEN 55 -- > Acción Social
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 110 THEN 66 -- > CC.OO
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 111 THEN 67 -- > CSIF
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 112 THEN 68 -- > SAP
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 113 THEN 69 -- > UGT
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 114 THEN 70 -- > USO
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 123 THEN 58 -- > Jornadas
            WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 125 THEN 59 -- > Personal
          END AS departamento,
          CASE
            WHEN CAST(GROUP_CONCAT(dsto.`tid`) AS CHAR) = 100 THEN 14 -- > SS.PP.
            WHEN CAST(GROUP_CONCAT(dsto.`tid`) AS CHAR) = 101 THEN 13 -- > SS.CC.
            WHEN CAST(GROUP_CONCAT(dsto.`tid`) AS CHAR) = 102 THEN 13 -- > General
          END AS destinatario,
          CASE
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 93  THEN 44 -- > Normativa
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 94  THEN 42 -- > Solo hay 1 nodo aquí
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 95  THEN 43 -- > Información Genérica
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 96  THEN 45 -- > Resoluciones
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 97  THEN 48 -- > Ordenes de Servicio
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 98  THEN 46 -- > Instrucciones
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 99  THEN 49 -- > Otras Normas
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 109 THEN 50 -- > Convenios
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 120 THEN 41 -- > Manuales SIGMA
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 121 THEN 42 -- > Otros Manuales
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 140 THEN 47 -- > Criterios
            WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 142 THEN 51 -- > Memorias company
          END AS tipodepagina,
          -- CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) AS departamento,
          -- CAST(GROUP_CONCAT(dsto.`tid`) AS CHAR) AS destinatario,
          -- CAST(GROUP_CONCAT(page.`tid`) AS CHAR) AS tipodepagina,
          `node`.`nid` AS nid_reference
        FROM `node`
        INNER JOIN `node_revisions` ON `node_revisions`.`vid` = `node`.`vid`
        INNER JOIN `term_node` ON `term_node`.`nid` = `node`.`nid`
        LEFT JOIN `term_data` dpto ON dpto.`tid` = `term_node`.`tid` AND dpto.`vid` = 19 -- Departamentos
        LEFT JOIN `term_data` dsto ON dsto.`tid` = `term_node`.`tid` AND dsto.`vid` = 21 -- Destinatario
        LEFT JOIN `term_data` page ON page.`tid` = `term_node`.`tid` AND page.`vid` = 20 -- Tipo de Página
        WHERE `type` = 'page'
        GROUP BY `node`.`nid`
        ORDER BY `node`.`nid` ASC
      ";
      $rows  = db_fetch(db_query($link, $query));
      jmeter($type, $rows);
      db_close($link);
      break;

    case 'taxonomy':

      $link  = db_open('company_vieja');
      $dev   = db_open('company_nueva');
      $query = "SELECT * FROM `content_field_palabras_clave`";
      $rows  = db_fetch(db_query($link, $query));

      foreach ($rows as $row) {

        extract($row);
        $tags = explode(',', $field_palabras_clave_value);

        foreach ($tags as $tag) {

          $tag    = trim($tag);
          $query  = "SELECT `tid` FROM `term_data` WHERE `vid` = '9' AND `name` = '$tag'";
          $result = db_fetch(db_query($dev, $query));

          if (!$result) {
            echo "\nINSERT INTO `company_nueva`.`term_data` (`vid`, `name`, `description`) VALUES ('9', '$tag', '');";
            db_query($dev, "INSERT INTO `term_data` (`vid`, `name`, `description`) VALUES ('9', '$tag', '')");
            $query  = "SELECT `tid` FROM `term_data` WHERE `vid` = '9' AND `name` = '$tag'";
            $result = db_fetch(db_query($dev, $query));
          }
          $tid   = $result[0]['tid'];
          $query = "INSERT INTO `company_nueva`.`term_node` (`nid`, `vid`, `tid`) VALUES ('$nid', '$vid', (SELECT `tid` FROM `company_nueva`.`term_data` WHERE `vid` = '9' AND `name` = '$tag'));";
          echo "\n$query";
          db_query($dev, $query);
        }
      }
      db_close($dev);
      db_close($link);
      break;

    default:
      e("Missing definition for type '$type'. Exit Main Process.");
      break;
  }
}
//init();
function init() {
  $query = "
    SELECT `TABLE_NAME`
    FROM `information_schema`.`columns`
    WHERE `table_schema` = 'myproject'
  ";

  $pages  = array('page_nosearch', 'page_view', 'page_views');
  $info   = db_open('information_schema');
  $link   = db_open('myproject');
  $tables = db_fetch(db_query($info, $query));
  $result = array();

  foreach ($tables as $table) {

    extract($table);
    $query = "SELECT * FROM $TABLE_NAME";
    $rows  = db_fetch(db_query($link, $query));

    foreach ($rows as $row) {
      foreach ($row as $field => $data) {
        foreach ($pages as $page) {
          if (strstr($data, $page)) {
            $result[$TABLE_NAME][$field]++;
            //echo "$TABLE_NAME -> $field = " . substr($data, 0, 120) . "...\n";
          }
        }
      }
    }
  }
  print_r($result);
  db_close($info);
  db_close($link);
}

/*******************************************************************************
 * MySQLi Functions
 ******************************************************************************/

/**
 * Connect to database
 */
function db_open($database) {

  global $server, $user, $password;

  $link = mysqli_connect($server, $user, $password, $database);

  if (mysqli_connect_errno()) {
    throw new Exception('Connection failed: ' . mysqli_connect_error());
  }
  else {
    mysqli_query($link, 'SET NAMES utf8');
  }

  return $link;
}

/**
 * Execute query in database
 */
function db_query($link, $query) {

  if (!isset($link)) {
    throw new Exception('Connection with MySQL server needed before execute a SQL query.');
  }
  else {
    return mysqli_query($link, $query);
  }
}

/**
 * Fetch a db query result in array associative
 */
function db_fetch($data) {

  $rows = array();

  if ($data) {
    while ($fetched = mysqli_fetch_assoc($data)) {
      $rows[] = $fetched;
    }
    mysqli_free_result($data);
  }

  return $rows;
}

/**
 * Disconnect from database
 */
function db_close($link) {
  mysqli_close($link);
}
