<?php


class views_handler_field_competencias extends views_handler_field 
{
  function construct() {
    parent::construct();
    
    //variables auxiliares
    $this->maxima_puntuacion = 3;
    $this->competencias      = array();    
    $this->comodin           = 'field_empresa_c%'; //comodin MYSQL
    $this->texto = "";
    
    $resultado = db_query(
    	"SELECT field_name
    	 FROM {content_group_fields} 
    	 WHERE type_name='empresa' 
    	 AND field_name like '%s' ",
         $this->comodin
    );
    
    while($competencia = db_result($resultado)){
        $competencia.="_value";
        $this->competencias[]                  = $competencia;
        $this->additional_fields[$competencia] = $competencia;            
    }
  }

  function option_definition() {
    $options = parent::option_definition();
    $options['enlace'] = array('default' =>  0 , 'translatable' => false);
	  $options['num_max'] = array('default' =>  3 , 'translatable' => false);
    return $options;    
  }

  
  function options_form(&$form, &$form_state) {
  	
     parent::options_form($form, $form_state);
     $max = array(); 
     for($i=0; $i<10;$i++)
       $max[$i] = "$i";
     $form['enlace'] = array(
        '#type' => 'select',
  		'#title' => t('Enlazar cada entrada a'),
  		'#default_value' => $this->options['enlace'],
  		'#options' => array('Nada','Búsqueda por criterio "competencias"') ,
  		'#description' => 'El enlace que se fabricará para cada una de las entradas. En caso de elegir la búsqueda por criterio se usará como URL base la actual, añadiendo el párametro sector[]=entrada',
     );   
     $form['num_max'] = array(
      '#type' => 'select',
      '#title' => 'Número máximo de especialidades a mostrar',
      '#default_value' => $this->options['num_max'],
      '#options' => $max,
      '#description' => 'El número de elementos de la lista de especialidades que se mostrarán al usuario. En caso de tener que recortar el total de especialidades se escogerán al azar',
     );   
  }
  
  
  function query() {
    $this->ensure_my_table();
    $this->add_additional_fields();
  }
  
  
  function render($values) {

      $output = array(); 
      foreach($this->competencias as $competencia){
          
          if ($values->{$this->aliases[$competencia]} == $this->maxima_puntuacion){//si es una especialidad
              $competencia = preg_replace("/_value/", "", $competencia);    
              //averiguamos el nombre real del campo
              $etq = db_result(
                  db_query(
                  	"SELECT label FROM {content_node_field_instance} WHERE field_name = '%s'" , 
                    $competencia
                  )
              );
                    
              switch($this->options['enlace']){
                  case 0:
                      $output[] = $etq;
                      break;
                      
                  case 1:
                      $enlace = $_GET['q'];
                	  $opciones = array( 
                	      'query' => array( 
                	          'free'		=> "",
                	          $competencia  => 1
                	      ), 
                	  ); 
                      $output[] = l($etq , $enlace , $opciones);
                      break;     
              }           
          }            
      }   
      
      //comprobamos si es necesario filtrar el numero de elementos    
      if( count($output) >= $this->options['num_max']  ){
        //filtramos output
        shuffle($output);
        $aux = array();
        for($i=0; $i<$this->options['num_max']; $i++ )
          $aux[] = array_shift($output);
        $output = $aux;                  
      } 
 
      return implode(',' , $output); 
  }
}
//*/
