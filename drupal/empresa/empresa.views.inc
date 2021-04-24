<?php

function empresa_views_data(){		
    
    $data['empresa']['table']['group']  = t('Empresa');
    $data['empresa']['table']['join']['node'] = array(
      'table' => 'content_type_empresa',
      'left_field' => 'vid',
      'field' => 'vid',
    );
    $data['empresa']['table']['join']['node_revisions'] = array(
      'table' => 'content_type_empresa',
      'left_field' => 'vid',
      'field' => 'vid',
    );
    

   	$data['empresa']['competencias'] = array(
	  	'title' => 'Competencias',
  		'help' => 'Análisis de las competencias, sólo contempla las especialidades(aquellas etiquetadas con 3)', 
     	'argument' => array(
          'handler' => 'views_handler_argument_competencias',
        ),
        'field' => array(
    		'handler' => 'views_handler_field_competencias',
    	),    	    
	);
	
	$data['empresa']['tabla_competencias'] = array(
	  	'title' => 'Tabla de competencias',
  		'help' => 'Tabla con todas las competencias de una empresa',      	
        'field' => array(
    		'handler' => 'views_handler_field_tabla_competencias',
    	),    	    
	);
	//*/

	$data['empresa']['libre'] = array(
	  	'title' => 'Búsqueda libre(empresa)',
  		'help' => 'Busca el texto introducido en el nombre de empresa, el país, la provincia, la ciudad y el sector de la empresa .La empresa se introducirá si la cadena coincide parcialmente con uno de los campos', 
     	'argument' => array(
          'handler' => 'views_handler_empresa_argument_libre',
        ),
   	);
   	
   	$data['empresa']['sector'] = array(
	  	'title' => 'Sector(personalizado)',
  		'help' => 'Permite interpretar varios campos separados por comas', 
     	'argument' => array(
          'handler' => 'views_handler_empresa_argument_sector',
        ),
   	);
   	
   	$data['empresa']['puntuacion_total'] = array(
	  	'title' => 'Puntuación total',
  		'help' => 'La suma de competencias', 
     	'sort' => array(
          'handler' => 'views_handler_empresa_sort_total',
        ),
   	);
   	
   	$data['empresa']['empresas_relacionadas'] = array(
        'title' => 'Ordenar por: Empresas relacionadas',
        'help' => 'Se ordena por mayor número de coincidencias de las especialidades en relación al vid que se pase. También excluye a la empresa de referencia de la vista', 
        'argument' => array(
          'handler' => 'views_handler_empresas_relacionadas',
        ),
    );
   	
    $data['empresa']['orden'] = array(
        'title' => 'Ordenar ',
        'help' => 'ordena segun el criterio que se le pase: nombre de empresa, interes, ultimos añadidos o aleatorio', 
        'argument' => array(
          'handler' => 'views_handler_empresa_argument_orden',
        ),
    );
    
    $data['node']['contenido_expresiones_regulares'] = array(
      'title' => 'Comparacion por palabras',
      'help' => 'Realiza una busqueda mediante expresiones regulares del campo que se introduzca en la configuracion(también incluye cualquier tipo de contenido, solo es necesario especificar su tabla). Pueden asignarsele relaciones',        
      'argument' => array(
        'handler' => 'empresa_argument_regexp',
      ),          
    );
  	return $data;	
}

function empresa_views_handlers() 
{	
  return array(
    'info' => array(
      'path' => drupal_get_path('module', 'empresa')."/handlers",
    ),
    'handlers' => array(    	
    	'views_handler_argument_competencias'    => array('parent' => 'views_handler_argument'),
    	'views_handler_field_competencias' 	     => array('parent' => 'views_handler_field'),
    	'views_handler_field_tabla_competencias' => array('parent' => 'views_handler_field'),
    	'views_handler_empresa_argument_libre'   => array('parent' => 'views_handler_argument'),
    	'views_handler_empresa_argument_sector'  => array('parent' => 'views_handler_argument'),    
      'views_handler_empresa_sort_total'	 	   => array('parent' => 'views_handler_sort'),   	   	
      'views_handler_empresas_relacionadas'    => array('parent' => 'views_handler_argument'),
      'views_handler_empresa_argument_orden'   => array('parent' => 'views_handler_argument'),
      'empresa_argument_regexp'                => array('parent' => 'views_handler_argument'),
    ),
  ); 
}