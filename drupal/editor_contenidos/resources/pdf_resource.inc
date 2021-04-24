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
 * @details    PDF resource file.
 * @category   COMPANY
 * @version    $Id: pdf_resource.inc 0 2011-11-07 17:28:24 $
 * @author     Mauro José Vigliotti
 * @file       /sites/all/modules/custom/editor_contenidos/resources/pdf_resource.inc
 * @date       2012-02-09 17:28:24
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 *
 */


/**
 * Constants
 */
define('PDF_PERM_CREATE',    'access pdf conversion');

/**
 * Called by hook hook_services_resources().
 */
function _pdf_resource_definition() {

  $resources = array(

    'pdf' => array(

      // Index method
      'create' => array(
        'file' => array('type' => 'inc', 'module' => 'editor_contenidos', 'name' => 'resources/pdf_resource'),
        'help'                    => t('Creates a pdf version of a given document'),
        'callback'                => '_pdf_create',
        'access callback'         => '_pdf_access',
        'access arguments'        => array(PDF_PERM_CREATE),
        'access arguments append' => true,
        'args'                    => array(
          array(
            'name'        => 'html',
            'type'        => 'struct',
            'description' => t('The file object to convert'),
            'source'      => 'data',
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
function _pdf_create($html) {
  $path = $_FILES['html']['tmp_name'];

  if (!empty($path)) {
    return _tcpdf_convert($path);
  }
  else {
    return services_error(t('Invalid data.'), 406);
  }
}

/**
 * Access callback
 */
function _pdf_access($op) {
  global $user;

  switch ($op) {

    case PDF_PERM_CREATE:
      $access = user_access($op);
      break;

    default:
      $access = false;
      break;
  }

  return $access;
}