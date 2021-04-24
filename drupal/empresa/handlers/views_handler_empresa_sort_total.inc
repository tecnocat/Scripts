<?php
// $Id: views_handler_sort_random.inc,v 1.1 2008/09/03 19:21:28 merlinofchaos Exp $

/**
 * Handle a random sort.
 */
class views_handler_empresa_sort_total extends views_handler_sort {
    function construct() {
    	parent::construct();    
    	
    	$this->comodin = 'field_empresa_c%';//comodin MYSQL
 	}    
    function query() {
        
        $this->ensure_my_table();    
        
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

        if (!empty($formula)) {
          $this->query->add_orderby(NULL, $formula, $this->options['order'], "_puntuacion");
        }
        
        
    }    
}
