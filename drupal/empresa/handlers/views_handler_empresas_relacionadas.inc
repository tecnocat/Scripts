<?php
class views_handler_empresas_relacionadas extends views_handler_argument {
  function construct() {
    parent::construct();    
    $this->comodin = 'field_empresa_c%';//comodin MYSQL
    $this->maxima_puntuacion = 3;
  }   

  function option_definition() {
    $options = parent::option_definition();    
    $options['orden']    = array('default' => 'ASC' , 'translatable' => FALSE);
  
    return $options;    
  }

  function options_form(&$form, &$form_state) {
    
    parent::options_form($form, $form_state);
    $form['orden'] = array(
      '#type' => 'select',
      '#title' => 'Se hará una ordenación:',
      '#default_value' => $this->options['orden'],
      '#options' => array(
        'ASC' => t('Ascendente'),
        'DESC' => t('Descendente'),        
      ),      
    );    
  }
    
  function query() 
  {
    $this->ensure_my_table();
      
    $vid = $this->argument; 
    
          
    // 1- LISTADO DE COMPETENCIAS
    $query = "
      SELECT field_name 
      FROM   {content_group_fields}  
      WHERE  type_name = 'empresa' 
      AND    field_name like '%s'
    ";  
        
    $resultado = db_query($query,$this->comodin);
    
    $competencias = array();
    while( $competencia  = db_result($resultado) )
      $competencias[] = $competencia."_value";
        
    // 2- ESPECIALIDADES DE LA EMPRESA DE REFERENCIA
    $query = "
        SELECT %s
        FROM   {content_type_empresa}
        WHERE  vid = %d
    ";
    $competencias = join(',',$competencias);
    $competencias = db_fetch_array(db_query($query,$competencias,$vid));

    $especialidades = array();
    foreach( $competencias as $competencia => $puntuacion)
    {                                        
      if ($puntuacion == $this->maxima_puntuacion)
        $especialidades[] = $competencia;
    }       
    unset($competencias);    
    //tenemos las especialidades de la empresa referencia en el array $especialidades
        
    //3- CREAMOS LA FORMULA(expresion)
    $formula = array();
    foreach( $especialidades as $especialidad)
      $formula[] = "($especialidad=$this->maxima_puntuacion)";
          
    $formula = join('+',$formula);

    //4- LA AÑADIMOS A LA CONSULTA(Y EXCLUIMOS LA EMPRESA DE REFERENCIA)
    if (!empty($formula)) {
      $this->query->add_orderby(NULL, $formula, $this->options['orden'], "_similitud");
      $this->query->add_where('empresa_exclusion',"$this->table_alias.vid <> %d",$vid );

    }
    else{ //si esta vacío no muestra ninguna empresa(condicion imposible)
      $this->query->add_where('empresa_exclusion',"1=2");
    }     
        
  }
      
}