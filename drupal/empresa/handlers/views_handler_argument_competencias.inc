<?php

class views_handler_argument_competencias extends views_handler_argument {
function construct() {
    parent::construct();        	     
  }

  function query() {
    $this->ensure_my_table();
    $maxima_puntuacion = 3;    
    
    //obtenemos la tabla de campos seleccionados
    $campos = explode( ',' , urldecode( $this->argument ) );
    

    //una vez tenemos la tabla de fid, averiguamos cuales son los que estan activos    
    $consulta = array();

    foreach (  $campos as $campo  )
    	$consulta[] = "$this->table_alias.$campo = $maxima_puntuacion";    		
    
    if( !empty($consulta) ) 
    	$this->query->add_where('empresa_argument_competencias', implode(" OR ", $consulta) );
    	
  }
  
}