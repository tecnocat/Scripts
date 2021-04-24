<?php

/**
 * Mauro José Vigliotti
 *
 * @section LICENSE
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @details    SHAREABLE resource file.
 * @category   COMPANY
 * @version    $Id: shareable_resource.inc 0 2012-03-06 11:28:24 $
 * @author     Mauro José Vigliotti
 * @file       /sites/all/modules/custom/editor_contenidos/resources/shareable_resource.inc
 * @date       2012-02-09 17:28:24
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 *
 */


/**
 * Constants
 */
define('SHAREABLE_PERM_CREATE',    'share own documents');
define('SHAREABLE_PERM_DELETE',    'share own documents');

/**
 * Called by hook hook_services_resources().
 */
function _shareable_resource_definition() {

  $resources = array(

    'shareable' => array(

      // CREATE method
      'create' => array(
        'file' => array(
          'type' => 'inc',
          'module' => 'editor_contenidos',
          'name' => 'resources/shareable_resource'
        ),
        'help'                    => t('shares documents with other users'),
        'callback'                => '_shareable_create',
        'access callback'         => '_shareable_access',
        'access arguments'        => array(SHAREABLE_PERM_CREATE),
        'access arguments append' => false,
        'args'                    => array(
          array(
            'name'        => 'nid',
            'type'        => 'int',
            'description' => t('Node id'),
            'source'      => 'data',
            'optional'    => false,
          ),
          array(
            'name'        => 'uid',
            'type'        => 'array',
            'description' => t('User id'),
            'source'      => 'data',
            'optional'    => false,
          ),
        ),
      ),

      // DELETE method
      'delete' => array(
        'file' => array(
          'type' => 'inc',
          'module' => 'editor_contenidos',
          'name' => 'resources/shareable_resource'
        ),
        'help'                    => t('remove share from documents'),
        'callback'                => '_shareable_delete',
        'access callback'         => '_shareable_access',
        'access arguments'        => array(SHAREABLE_PERM_DELETE),
        'access arguments append' => false,
        'args'                    => array(
          array(
            'name'        => 'dnid',
            'type'        => 'int',
            'description' => t('Node id to unshare'),
            'source'      => array('path' => '0'),
            'optional'    => false,
          ),
        ),
      ),


    ),
  );

  return $resources;
}

/**
 * Create method
 */
function _shareable_create($params = false) {
  $nid = $params['nid'];
  $uid = $params['uid'];

  // get current user
  global $user;
  $node = node_load($nid);

  // check if current user is author
  if(_editor_contenidos_access($user, $node)){
    // share contents
    if (_editor_contenidos_share($nid, $uid)) {
      return(t('Sharing complete.'));
    }
    else {
      return services_error(t('Invalid query.'), 406);
    }
  }else{
    return services_error(t('User has no access to current document'), 406);
  }
}

/**
 * Delete method
 */
function _shareable_delete($dnid = false) {
  // check if current user is author
  if(_editor_contenidos_access($user, $node)){
    // share contents
    if (_editor_contenidos_share($dnid)) {
      return(t('Removed shares complete.'));
    }
    else {
      return services_error(t('Invalid query.'), 406);
    }
  }else{
    return services_error(t('User has no access to current document'), 406);
  }
}

/**
 * Access callback
 */
function _shareable_access($op) {
  global $user;

  switch ($op) {

    case SHAREABLE_PERM_CREATE:
    case SHAREABLE_PERM_DELETE:
      $access = user_access($op);
      break;

    default:
      $access = false;
      break;
  }

  return $access;
}