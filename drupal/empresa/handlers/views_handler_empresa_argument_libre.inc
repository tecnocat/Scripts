<?php

class views_handler_empresa_argument_libre extends views_handler_argument {
function construct() {
    parent::construct();
    $this->campos_empresa  = array( 'field_empresa_pais_value','field_empresa_provincia_value','field_empresa_ciudad_value','field_empresa_sector_value');    
    $this->campos_nodo = array( 'title' );
  
  }

  function query() {
    $this->ensure_my_table();
    
    //definimos los campos sobre los que haremos la busqueda
    $consulta = array();
    $tabla_nodo = 'node';
    
    
    foreach ($this->campos_nodo as $campo)
    	$consulta[]= "$tabla_nodo.$campo LIKE '%s'";
    foreach ($this->campos_empresa as $campo)    	
    	$consulta[]= "$this->table_alias.$campo LIKE '%s'";
    
    $sustitucion = array();
    for($i=0; $i < count($consulta);$i++ )
        $sustitucion[] = "%{$this->argument}%";
 		  
    if( !empty($consulta) ){ 
    	$this->query->add_where('empresa_argument_libre', implode(" OR ", $consulta),$sustitucion  );
    	//$this->query->add_groupby("$this->table_alias.vid"  );//evita la repeticion
    }	
	
  }
  
}
