<?php /* UTF-8 Verified (áéíóú) */

// $Id$

class views_handler_field_tabla_competencias extends views_handler_field {
  function construct() {
    parent::construct();
    $this->additional_fields['vid'] = 'vid';
  }

  function query() {
    $this->ensure_my_table();
    $this->add_additional_fields();
  }

  function render($values) {
    $vid = $values->{$this->aliases['vid']};
    $query = "
      SELECT
        f.field_name as field_name,
        g.label as group_label,
        f.label as field_label
      FROM {content_group_fields} gf
        LEFT JOIN {content_group} g ON gf.group_name = g.group_name
        LEFT JOIN {content_node_field_instance} f ON gf.field_name = f.field_name
      WHERE gf.type_name = 'empresa'
    ";
    $resultado = db_query($query);
    $query = "SELECT %s FROM {content_type_empresa} WHERE vid = %d";
    while ($fila = db_fetch_object($resultado)) {
      $puntuacion = db_result(db_query($query, $fila->field_name . "_value", $vid));
      if(!empty($puntuacion)){
        $puntuaciones[] = array(
          'score' => $puntuacion,
          'competence' => $fila->field_label,
          'group' => $fila->group_label,
        );
      }
    }
    array_multisort($puntuaciones);
    rsort($puntuaciones);
    foreach ($puntuaciones as $array) {
      $inc++;
      $class = ($inc % 2) ? 'odd' : 'even';
      $table .= '
        <tr class="' . $class . '">
          <td class="empresa-competencia-grupo">' . $array['group'] . '</td>
          <td class="empresa-competencia">' . $array['competence'] . '</td>
          <td class="empresa-competencia-puntuacion">' . $array['score'] . '</td>
        </tr>
      ';
    }
    $header = '
      <tr>
        <th>' . t('Competences group') . '</th>
        <th>' . t('Competence') . '</th>
        <th>' . t('Score') . '</th>
      </tr>
    ';
    return '<table class="empresa-competencias">' . $header . $table . '</table>';
  }
}