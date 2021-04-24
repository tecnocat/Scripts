<?php
class views_handler_empresa_argument_orden extends views_handler_argument {
  function construct() {
    parent::construct();    
    $this->comodin = 'field_empresa_c%';//comodin MYSQL
    $this->maxima_puntuacion = 3;
  }   
/*
  function option_definition() {
    $options = parent::option_definition();    
    $options['orden']    = array('default' => 'ASC' , 'translatable' => FALSE);
  
    return $options;    
  }

  function options_form(&$form, &$form_state) {
    if ($this->argument)
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
 //*/   
  function query() 
  {
    $this->ensure_my_table();

    
    if(empty($this->argument))
      $this->argument ="aleatorio";
      
    switch($this->argument){
      
      case 'recientes':
        $formula = "node_revisions.timestamp"; 
        $orden   = "DESC";
      break;
      
      case 'alfabetico':
        $formula = "node.title"; 
        $orden   = "ASC";
      break;
        
      case 'interes':
         $resultado = db_query(
          "SELECT CONCAT(field_name,'_value')
       FROM {content_group_fields}
       WHERE type_name='empresa' 
       AND field_name like '%s' ",
             $this->comodin
        );
        
        $campos=array();
        while ($campo = db_result($resultado)  )
            $campos[] = $campo;
            
        
        $formula = implode('+' , $campos);
        $orden = "DESC";
        
      break;
      case 'random':  
      case 'aleatorio':  
        $formula = "RAND()";
        $orden   = "DESC";
      break;  
    }  
    
    $this->query->add_orderby(NULL, $formula, $orden, "_orden");      
  }
}  