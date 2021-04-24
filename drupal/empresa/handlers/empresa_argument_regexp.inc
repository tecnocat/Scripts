<?php

class empresa_argument_regexp extends views_handler_argument {
  function construct() 
  {
    parent::construct();                                                
  }
  
  
  function option_definition() 
  {
    $options = parent::option_definition();
    $options['tabla'] = array('default' =>  'node' , 'translatable' => false);    
    $options['campo'] = array('default' =>  ''     , 'translatable' => false);          
    return $options;    
  }  
    
  function options_form(&$form, &$form_state) 
  {           
     parent::options_form($form, $form_state);
                      
     $form['tabla'] = array(
       '#type'          => 'textfield',
       '#title'         => 'El campo se encuentra en la tabla',
       '#default_value' => $this->options['tabla'],
       '#description'   => 'Debe introducirse el nombre interno de drupal(por ejemplo: node o content_type_empresa).',
       '#size'          => 30,
       '#maxlength'     => 60,
       '#required'      => TRUE,
     );
     $form['campo'] = array(
       '#type'          => 'textfield',
       '#title'         => 'Campo',
       '#default_value' => $this->options['campo'],
       '#description'   => 'Debe introducirse el nombre interno de drupal(por ejemplo: name o profile_apellido).',
       '#size'          => 30,
       '#maxlength'     => 60,
       '#required'      => TRUE,
     );
  }
  
  
  function query() 
  {    
    $this->ensure_my_table();          
    
    /*1- SELECCION DE TABLA
           SI SE REFIERE A UN CAMPO DE tipo de contenido CREAMOS EL JOIN Y AÑADIMOS LA RELACION
           SI SE REFIERE A UN CAMPO DE nodo, TOMAMOS COMO TABLA LA DE nodo QUE APUNTE ESTA RELACION*/
    if ($this->options['tabla'] == 'node'){
      $tabla = $this->table_alias; //pertenece a nodo el handler
    }
    else {
      //creamos el join  
      $join = new views_join();
      $join->construct($this->options['tabla'],$this->table_alias,"vid","vid");
      
      ///configuramos el alias 
      $tabla = $this->options['tabla']."_".$this->options['campo'];
      
      //añadimos la relacion a la consulta   
      $this->query->add_relationship($tabla,$join,$this->table_alias);      
    }          
              

     
    //2- OBTENEMOS EL PATRON DE BUSQUEDA
          
    //quitamos espacios de sobra
    $patron = preg_replace('/\s\s+/',' ',trim($this->argument));
    
    //detectamos las palabras    
    $patron = explode(' ', $patron);  
    
    //creamos el patron de búsqueda
    $patron = "%".implode('%',$patron)."%";  

    
    
    //3- CREAMOS LA CONSULTA
    $campo = $this->options['campo'];
    $condicion = "$tabla.$campo LIKE '%s'";
    /*         
    if ($this->options['tabla'] == 'perfil'){
      $fid = db_result( db_query("SELECT fid FROM {profile_fields} WHERE name LIKE '%s'",$this->options['campo']) );
    
      //en caso de error salimos
      if(!$fid)
        return;
        
      $condicion = "$tabla.fid=$fid AND $tabla.value LIKE '%s'";  
    }
    else if ($this->options['tabla'] == 'usuario'){
      $campo = $this->options['campo'];
      $condicion = "$tabla.$campo LIKE '%s'";
    }
    //*/
    //4- la ejecutamos
    $this->query->add_where("nodoRegexp_".$this->options['tabla']."_".$this->options['campo'], $condicion , $patron);    
  }
  
  function ui_name($short = FALSE)
  {
    $grupo = ucwords($this->options['tabla']);
    $campo = $this->options['campo']." Exp. Reg.";
    return "$grupo: $campo";    
  }
}