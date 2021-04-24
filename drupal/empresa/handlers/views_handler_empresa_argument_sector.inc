<?php

//require_once drupal_get_path('module','contactos'). '/contactos.install';

class views_handler_empresa_argument_sector extends views_handler_argument {
  function construct() 
  {
    parent::construct();                    	          
  }

  function query() {
    $this->ensure_my_table();
        
    //obtenemos la tabla de campos seleccionados
    $campos = explode( ',' , urldecode( $this->argument ) );
    

    //una vez tenemos la tabla de fid, averiguamos cuales son los que estan activos    
    $consulta = array();

    foreach (  $campos as $campo  )
    	$consulta[] = "$this->table_alias.field_empresa_sector_value = '$campo'";    		
    
    if( !empty($consulta) ) 
    	$this->query->add_where('empresa_argument_sector', implode(" OR ", $consulta) );
    	
  }
  
}