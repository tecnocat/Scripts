<?php

/**
 * This shit of spaguetti code is from dramirez AKA pistacho AKA paco pil
 */

function _message_resource_create($data) {

	  if(!is_object($data)){
	     $data = (object)$data;
	  }


      if (!isset($data->subject)) {
        return services_error('Missing message attribute subject', 406);
      }

      if (!isset($data->body['value'])) {
        return services_error('Missing message attribute message', 406);
      }

      if (!isset($data->recipient)) {
        return services_error('Missing recipient attribute message', 406);
      }
      $recipients=array();
      $recipient = explode(',',$data->recipient);
      foreach ($recipient as $r) {
          $recipients[]=user_load_by_name($r);
      }


	  $message = (object)array();
	  $message->subject = $data->subject;
	  $message->body = $data->body['value'];
	  $message->format = $data->body['format'];


     $validate = privatemsg_new_thread($recipients, $message->subject,$message->body, $message);

     return ($validate) ? array('mid'=>$validate['message']->mid): services_error('Bad Request',406);


}


/*******************RETRIEVE***************/
function _message_resource_retrieve($id) {

  $data = message_resource_get_message($id);

  // Filter some sensible data
  $data->author = array(
    'uid'  => $data->author->uid,
    'name' => $data->author->name,
    'mail' => $data->author->mail,
  );

  // Unset innecesary data
  unset($data->user);

  return $data;
}

/********************** DELETE******************/
function _message_resource_delete($id) {
	  privatemsg_message_change_delete($id,1);
      return (object)array(
        'id' => $id,
      );
 }

/***************** INDEX ****************/
function _message_resource_index($page, $parameters) {
    global $user;
    $query = _privatemsg_assemble_query('list', $user, 'inbox');
    $list = array();
    $count=0;

    //filter by latest month messages
    $query->condition('pm.timestamp', 'UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL -30 DAY))', '>');

    foreach ($query->execute() as $thread) {
        $list[] = $thread;
    }
    return $list;
}

/********************** ACCESS ***********************/
function _message_resource_access($op) {
      global $user;

      $access = FALSE;
      $access_perm = '';
      switch ($op) {
        case 'view':
                $access_perm = 'read privatemsg';
            break;
        case 'delete':
                $access_perm = 'delete privatemsg';
                break;
        case 'create':
               $access_perm = 'write privatemsg';
              break;
        case 'index':
            $access_perm = 'read all private messages';
          break;
      }

      $access = privatemsg_user_access($access_perm,$user);

      return $access;
}

 function message_resource_get_message($id) {
    $message =   privatemsg_message_load($id);

    return $message;
}

/***************** DEFINITION************************/
function _message_resource_definition() {
	return 	array (
		'message' => array (
			'retrieve' => array (
	            'file' => array('type' => 'inc', 'module' => 'editor_contenidos', 'name' => 'resources/message_resource'),
				'help' => 'Retrieves a message',
				'callback' => '_message_resource_retrieve',
	            'access callback' => '_message_resource_access',
				'access arguments' => array ('view' ),
		 		'access arguments append' => TRUE,
		 		'args' => array (
	                array (
	                	'name' => 'id',
	                	'type' => 'int',
	                	'description' => 'The id of the message to get',
	                	'source' => array ('path' => '0' ),
	                	'optional' => FALSE
	                ) ,
                ) ,
	        ) ,
			'create' => array (
	            'file' => array('type' => 'inc', 'module' => 'editor_contenidos', 'name' => 'resources/message_resource'),
			    'help' => 'Creates a message',
		 		'callback' => '_message_resource_create',
		 		'access arguments' => array ('create' ),
	 			'access arguments append' => TRUE,
	            'access callback' => '_message_resource_access',
	 			'args' => array (
                    array (
                    	'name' => 'message',
                    	'type' => 'array',
                    	'description' => 'The message object',
                    	'source' => 'data',
                    	'optional' => FALSE
                    ) ,
               ) ,
            ),
			'delete' => array (
                'file' => array('type' => 'inc', 'module' => 'editor_contenidos', 'name' => 'resources/message_resource'),
				'help' => 'Deletes a message',
		 		'callback' => '_message_resource_delete',
                 'access callback' => '_message_resource_access',
	 			'access arguments' => array ('delete' ),
                'access arguments append' => TRUE,
	 			'args' => array (
                    array (
                    	'name' => 'id',
                    	'type' => 'int',
                    	'description' => 'The id of the message to delete',
                        'source' => array ('path' => '0' ),
                         'optional' => FALSE
                    ),
                  ) ,
		     ) ,
		     'index' => array(
		     'file' => array('type' => 'inc', 'module' => 'editor_contenidos', 'name' => 'resources/message_resource'),
           	 'help' => 'Retrieves a listing of messages',
           	 'callback' => '_message_resource_index',
             'access callback' => '_message_resource_access',
             'access arguments' => array('index'),
              'access arguments append' => FALSE,
                'args' => array(
		         array(
                   'name' => 'page',
                   'type' => 'int',
                   'description' => '',
                   'source' => array(
                     'param' => 'page',
                   ),
                   'optional' => TRUE,
                   'default value' => 0,
                 ),
                 array(
                   'name' => 'parameters',
                   'type' => 'array',
                   'description' => '',
                   'source' => 'param',
                   'optional' => TRUE,
                   'default value' => array(),
                 ),
               ),
             ),
		 ) ,
	 );
}