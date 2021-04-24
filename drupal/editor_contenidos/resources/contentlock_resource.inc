<?php

/**
 * This shit of spaguetti code is from dramirez AKA pistacho AKA paco pil
 */

	/***************** DEFINITION************************/
function _contentlock_resource_definition() {
	return 	array (
		'content_lock' => array (
			'retrieve' => array (
	            'file' => array('type' => 'inc', 'module' => 'editor_contenidos', 'name' => 'resources/contentlock_resource'),
				'help' => 'Retrieves a lock node',
				'callback' => '_contentlock_resource_retrieve',
	            'access callback' => '_contentlock_resource_access',
				'access arguments' => array ('view' ),
		 		'access arguments append' => TRUE,
		 		'args' => array (
	                array (
	                	'name' => 'id',
	                	'type' => 'int',
	                	'description' => 'The id of the node to lock',
	                	'source' => array ('path' => '0' ),
	                	'optional' => FALSE
	                ) ,
                ) ,
	        ) ,
			'create' => array (
	            'file' => array('type' => 'inc', 'module' => 'editor_contenidos', 'name' => 'resources/contentlock_resource'),
			    'help' => 'lock a node',
		 		'callback' => '_contentlock_resource_create',
		 		'access arguments' => array ('create' ),
	 			'access arguments append' => TRUE,
	            'access callback' => '_contentlock_resource_access',
	 			'args' => array (
                    array (
                    	'name' => 'content_lock',
                    	'type' => 'array',
                    	'description' => 'The node to lock',
                    	'source' => 'data',
                    	'optional' => FALSE
                    ) ,
               ) ,
            ),
			'delete' => array (
                'file' => array('type' => 'inc', 'module' => 'editor_contenidos', 'name' => 'resources/contentlock_resource'),
				'help' => 'remove references to deleted nodes from the lock tables',
		 		'callback' => '_contentlock_resource_delete',
                 'access callback' => '_contentlock_resource_access',
	 			'access arguments' => array ('delete' ),
                'access arguments append' => TRUE,
	 			'args' => array (
                    array (
                    	'name' => 'id',
                    	'type' => 'int',
                    	'description' => 'The id of node',
                        'source' => array ('path' => '0' ),
                         'optional' => FALSE
                    ),
                  ) ,
		     ) ,
		 ) ,
	 );
}

/********************** ACCESS ***********************/
function _contentlock_resource_access($op) {


      $access = FALSE;
      $access_perm = '';
      switch ($op) {
        case 'view':
                $access_perm = 'check out documents';
            break;
        case 'delete':
                $access_perm = 'check out documents';
                break;
        case 'create':
               $access_perm = 'check out documents';
              break;

      }

      return user_access($access_perm);
}

/*******************RETRIEVE***************/
function _contentlock_resource_retrieve($nid) {
  $return = content_lock_fetch_lock($nid);

   return ($return) ?  $return : array('lock'=>'false');
}

/*********** CREATE *************************/

function _contentlock_resource_create($nid) {
      global $user;

    $return = content_lock_node($nid,$user->uid);

    if($return){
        return array('lock'=>'true');
    }
    else{
       $messages=array();
       foreach(drupal_get_messages('warning') as $m){
           foreach($m as $value){
               $messages[] = strip_tags($value);
           }
       }


       return array_merge(array('lock'=>'false'),$messages);
    }

}

/**************DELETE*****************/
function _contentlock_resource_delete($nid) {

     $node = node_load($nid);

    content_lock_node_delete($node);
    return drupal_get_messages('warning');

}