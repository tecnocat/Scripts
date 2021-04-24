<?php

/**
 * tecnocat
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
 * @details    Bibtext resource file.
 * @category   COMPANY
 * @version    $Id: biblio_resource.inc 0 2011-11-07 17:28:24 $
 * @author     tecnocat
 * @file       /sites/all/modules/custom/editor_contenidos/resources/biblio_resource.inc
 * @date       2011-11-07 17:28:24
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 *
 */


/**
 * Constants
 */
define('BIBLIO_PERM_RETRIEVE', 'access biblio content');
define('BIBLIO_PERM_CREATE',        'import from file');
define('BIBLIO_PERM_UPDATE',        'import from file');
define('BIBLIO_PERM_DELETE',       'administer biblio');
define('BIBLIO_PERM_INDEX',    'access biblio content');


/**
 * Called by hook hook_services_resources().
 */
function _biblio_resource_definition() {

  $resources = array(

    'biblio' => array(

      // Retrieve method
      'retrieve' => array(
        'file'   => array(
          'type'   => 'inc',
          'module' => 'editor_contenidos',
          'name'   => 'resources/biblio_resource',
        ),
        'help'                    => t('Retrieves a biblio'),
        'callback'                => '_biblio_retrieve',
        'access callback'         => '_biblio_access',
        'access arguments'        => array(BIBLIO_PERM_RETRIEVE),
        'access arguments append' => true,
        'args'                    => array(
          array(
            'name'        => 'id',
            'type'        => 'int',
            'description' => t('The id of the biblio to get'),
            'source'      => array('path' => '0'),
            'optional'    => false,
          ),
        ),
      ),

      // Create method
      'create' => array(
        'file' => array(
          'type'   => 'inc',
          'module' => 'editor_contenidos',
          'name'   => 'resources/biblio_resource',
        ),
        'help'                    => t('Creates a biblio'),
        'callback'                => '_biblio_create',
        'access callback'         => '_biblio_access',
        'access arguments'        => array(BIBLIO_PERM_CREATE),
        'access arguments append' => false,
        'args'                    => array(
          array(
            'name'        => 'bibtex',
            'type'        => 'string',
            'description' => t('The biblio string in bibtex format'),
            'source'      => 'data',
            'optional'    => false,
          ),
        ),
      ),

      // Update method
      'update' => array(
        'file' => array(
          'type'   => 'inc',
          'module' => 'editor_contenidos',
          'name'   => 'resources/biblio_resource',
        ),
        'help'                    => t('Updates a biblio'),
        'callback'                => '_biblio_update',
        'access callback'         => '_biblio_access',
        'access arguments'        => array(BIBLIO_PERM_UPDATE),
        'access arguments append' => true,
        'args'                    => array(
          array(
            'name'        => 'id',
            'type'        => 'int',
            'description' => t('The id of the biblio to update'),
            'source'      => array('path' => '0'),
            'optional'    => false,
          ),
          array(
            'name'        => 'data',
            'type'        => 'string',
            'description' => t('The biblio data string'),
            'source'      => 'data',
            'optional'    => false,
          ),
        ),
      ),

      // Delete method
      'delete' => array(
        'file' => array(
          'type'   => 'inc',
          'module' => 'editor_contenidos',
          'name'   => 'resources/biblio_resource',
        ),
        'help'                    => t('Deletes a biblio'),
        'callback'                => '_biblio_delete',
        'access callback'         => '_biblio_access',
        'access arguments'        => array(BIBLIO_PERM_DELETE),
        'access arguments append' => true,
        'args'                    => array(
          array(
            'name'        => 'nid',
            'type'        => 'int',
            'description' => t('The id of the biblio to delete'),
            'source'      => array('path' => '0'),
            'optional'    => false,
          ),
        ),
      ),

      // Index method
      'index'  => array(
        'file' => array(
          'type'   => 'inc',
          'module' => 'editor_contenidos',
          'name'   => 'resources/biblio_resource',
        ),
        'help'                    => t('Retrieves a listing of biblio'),
        'callback'                => '_biblio_index',
        'access callback'         => '_biblio_access',
        'access arguments'        => array(BIBLIO_PERM_INDEX),
        'access arguments append' => false,
        'args'                    => array(
          array(
            'name'          => 'page',
            'type'          => 'int',
            'description'   => '',
            'source'        => array('param' => 'page'),
            'optional'      => true,
            'default value' => 0,
          ),
          array(
            'name'          => 'parameters',
            'type'          => 'array',
            'description'   => '',
            'source'        => 'param',
            'optional'      => true,
            'default value' => array(),
          ),
        ),
      ),
    ),
  );

  return $resources;
}


/**
 * Retrieve method
 */
function _biblio_retrieve($id = false) {

  if (is_numeric($id)) {

    $node = node_load($id);

    if ($node) {
      return $node;
    }
    else {
      return services_error(t('The node not exist.'), 404);
    }
  }
  else {
    return services_error(t('Invalid nid.'), 406);
  }
}


/**
 * Create method
 */
function _biblio_create($bibtex = false) {

  if (module_exists('biblio')) {
    module_load_include('inc', 'biblio', 'includes/biblio.import.export');
  }
  else {
    return services_error(t('Module biblio not exists.'), 500);
  }

  $terms           = array();
  $batch           = false;
  $mtime           = microtime();
  $save            = true;
  $string          = true;
  list($nid, $dup) = biblio_bibtex_biblio_import($bibtex, $terms, $batch, $mtime, $save, $string);
  $result          = array('nid' => $nid, 'dup' => $dup);

  return $result;
}


/**
 * Update method
 */
function _biblio_update($id, $data) {
  return _biblio_create($data);
}


/**
 * Delete method
 */
function _biblio_delete($id) {

  if (is_numeric($id)) {

    $node = node_load($id);

    if ($node) {

      node_delete($node->nid);
      $node = node_load($id);

      if ($node) {
        return services_error(t('Node can not be delete, check permissions.'), 401);
      }
      else {
        return $id;
      }
    }
    else {
      return services_error(t('The node not exist.'), 404);
    }
  }
  else {
    return services_error(t('Invalid nid.'), 406);
  }
}


/**
 * Index method
 */
function _biblio_index() {
  return services_error('Index not alowed at this moment', 501);
}


/**
 * Access callback
 */
function _biblio_access($op) {

  global $user;

  switch ($op) {

    case BIBLIO_PERM_RETRIEVE:
    case BIBLIO_PERM_CREATE:
    case BIBLIO_PERM_UPDATE:
    case BIBLIO_PERM_DELETE:
    case BIBLIO_PERM_INDEX:
      //$access_any = user_access($op . ' any biblio');
      //$access_own = user_access($op . ' own biblio');
      //$access     = ($access_any OR ($access_own AND $user->uid == $biblio->uid));
      $access = user_access($op);
      break;

    default:
      $access = false;
      break;
  }

  return $access;
}