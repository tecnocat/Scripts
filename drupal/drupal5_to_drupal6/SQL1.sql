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
 * @details    Company migration proccess
 * @category   Migration
 * @version    $Id: Script.sql 0 2012-02-22 09:14:34 $
 * @author     tecnocat
 * @file       /Script.sql
 * @date       2012-02-22 09:14:34
 * @copyright  GNU Public License.
 * @link       http://www.gnu.org/licenses/gpl.html
 */

/*******************************************************************************
 * INIT PROCCESS
 ******************************************************************************/
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`accesslog`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`acl`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`acl_node`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`acl_user`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`cache`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`cache_block`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`cache_content`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`cache_filter`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`cache_form`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`cache_menu`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`cache_page`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`cache_rules`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`cache_views`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`cache_views_data`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`comments`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_ambito`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_anexos`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_conclusiones`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_date`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_files`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_image`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_lugar`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_pasos_multiples`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_plazo`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_proyecto_gui`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_telefonos`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_acta`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_banner`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_book`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_contact`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_cuencos`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_image`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_infraestructura`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_page`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_presuntacion`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_procedimiento`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_proyectos`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_reserva_de_ala`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_servicios`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`download_count`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`favorites`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`files`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`history`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`node`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`node_access`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`node_comment_statistics`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`node_counter`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`node_revisions`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`poll`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`poll_choices`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`poll_votes`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`print_node_conf`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`print_page_counter`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`profile_values`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`search_index`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`search_node_links`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`search_total`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`sessions`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`term_node`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`url_alias`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`users_roles`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`votingapi_cache`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`votingapi_vote`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`watchdog`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`webform`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`webform_component`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`webform_emails`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`webform_last_download`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`webform_roles`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`webform_submissions`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`webform_submitted_data`;

/*******************************************************************************
 * USERS PROCCESS
 ******************************************************************************/
-- Reset users
DELETE FROM `DATABASE_PLACEHOLDER_DRUPAL6`.`users` WHERE `users`.`uid` > 1;

-- Copy users
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`users` (
  SELECT
    `users`.`uid`,
    `users`.`name`,
    `users`.`pass`,
    `users`.`mail`,
    `users`.`mode`,
    `users`.`sort`,
    `users`.`threshold`,
    `users`.`theme`,
    `users`.`signature`,
    0 AS signature_format,
    `users`.`created`,
    `users`.`access`,
    `users`.`login`,
    `users`.`status`,
    `users`.`timezone`,
    `users`.`language`,
    `users`.`picture`,
    `users`.`init`,
    `users`.`data`,
    'Europe/Madrid' AS 'timezone_name'
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`users`
  WHERE `users`.`uid` > 1
  ORDER BY `users`.`uid` ASC
);

-- Reset passwords, pictures, languages and timezones
UPDATE `DATABASE_PLACEHOLDER_DRUPAL6`.`users`
SET
  `pass`     = MD5('company'),
  `picture`  = '',
  `language` = 'es',
  `timezone` = '3600'
WHERE `users`.`uid` > 1;

-- Update admin data
UPDATE `DATABASE_PLACEHOLDER_DRUPAL6`.`users`
SET
  `created` = (SELECT `users`.`created` FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`users` WHERE `users`.`uid` = 1),
  `access`  = (SELECT `users`.`access` FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`users` WHERE `users`.`uid` = 1),
  `login`   = (SELECT `users`.`login` FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`users` WHERE `users`.`uid` = 1)
WHERE `users`.`uid` = 1;

-- Set default settings for uid 1
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`profile_values` VALUES (1, 1, 'Administrador');
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`users_roles` VALUES (1, 3); -- Informática
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`users_roles` VALUES (1, 6); -- Editor
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`users_roles` VALUES (1, 7); -- Administrador
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`users_roles` VALUES (1, 8); -- Modificador de Departamento

-- Set default name for users
DELETE FROM `DATABASE_PLACEHOLDER_DRUPAL6`.`profile_values` WHERE `uid` > 1;
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`profile_values` (
  SELECT
  1 AS fid,
  `users`.`uid`,
  `users`.`name` AS 'value'
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`users`
  WHERE `users`.`uid` > 1
);

-- Set default user for tecnocat
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`users` (`uid`, `name`, `pass`, `mail`, `mode`, `sort`, `threshold`, `theme`, `signature`, `signature_format`, `created`, `access`, `login`, `status`, `timezone`, `language`, `picture`, `init`, `data`, `timezone_name`) VALUES ('2', 'company', MD5('company'), 'root@company.com', '0', '0', '0', '', '', '0', '1197637223', '1331304273', '1330618287', '1', '3600', 'es', '', 'root@company.com', 'a:1:{s:7:"contact";i:1;}', 'Europe/Madrid');
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`profile_values` VALUES (1, 2, 'tecnocat');

/*******************************************************************************
 * NODE PROCCESS
 ******************************************************************************/
-- Reset nodes
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`node`;

-- Copy nodes
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`node` (
  SELECT
    `node`.`nid`,
    `node`.`vid`,
    CASE
      WHEN `node`.`type` = 'acidfree' THEN 'album'
      WHEN `node`.`type` = 'prestamos' THEN 'presuntacion'
      ELSE `node`.`type`
    END AS 'type',
    'es' AS 'language',
    `node`.`title`,
    `node`.`uid`,
    `node`.`status`,
    `node`.`created`,
    `node`.`changed`,
    `node`.`comment`,
    0 AS promote,
    `node`.`moderate`,
    `node`.`sticky`,
    0 AS tnid,
    0 AS translate
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node`
  WHERE `node`.`type` IN ('page', 'image', 'proyectos', 'cuencos', 'prestamos', 'acidfree', 'forum', 'poll')
  ORDER BY `node`.`nid` ASC
);

-- Copy revisions
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`node_revisions` (
  SELECT `node_revisions`.* FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node_revisions`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`node` ON `node`.`nid` = `node_revisions`.`nid`
  WHERE `node`.`type` IN ('page', 'image', 'proyectos', 'cuencos', 'prestamos', 'acidfree', 'forum', 'poll')
  ORDER BY `node_revisions`.`nid` ASC
);
-- Reset format
UPDATE `DATABASE_PLACEHOLDER_DRUPAL6`.`node_revisions` SET `node_revisions`.`format` = 1 WHERE `node_revisions`.`format` = 2 OR `node_revisions`.`format` = 3;

-- Copy comments statistics
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`node_comment_statistics` (
  SELECT `node_comment_statistics`.* FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node_comment_statistics`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`node` ON `node`.`nid` = `node_comment_statistics`.`nid`
  WHERE `node`.`type` IN ('page', 'image', 'proyectos', 'cuencos', 'prestamos', 'acidfree', 'forum', 'poll')
  ORDER BY `node_comment_statistics`.`nid` ASC
);

-- Copy node counter
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`node_counter` (
  SELECT `node_counter`.* FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node_counter`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`node` ON `node`.`nid` = `node_counter`.`nid`
  WHERE `node`.`type` IN ('page', 'image', 'proyectos', 'cuencos', 'prestamos', 'acidfree', 'forum', 'poll')
  ORDER BY `node_counter`.`nid` ASC
);

-- Copy polls
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`poll` (SELECT * FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`poll`);
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`poll_choices` (SELECT * FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`poll_choices`);
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`poll_votes` (SELECT * FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`poll_votes`);

-- Copy fields
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_ambito` (SELECT `vid`, `nid`, `delta`, `field_ambito_value` FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`content_field_ambito`);

-- Copy cuencs
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_cuencos` (
  SELECT
    `vid`,
    `nid`,
    `field_servidor_value`,
    `field_directorio_value`,
    `field_plantilla_value`,
    `field_programa_value`,
    `field_entorno_value`,
    `field_dpto_destinatario_value`
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_cuencos`
  ORDER BY `content_type_cuencos`.`vid` ASC
);

-- Copy image
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_image` (
  SELECT
    `vid`,
    `nid`,
    `field_autor_value`,
    `field_tecnica_value`
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_image`
  ORDER BY `content_type_image`.`vid` ASC
);
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_lugar` (
  SELECT `vid`, `nid`, `field_lugar_value`
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_image`
  ORDER BY `content_type_image`.`vid` ASC
);

-- Copy presuntacion
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_presuntacion` (
  SELECT
    `vid`,
    `nid`,
    `field_presuntacion_value`,
    `field_beneficiario_value`,
    `field_cuanta_value`,
    `field_documentacion_value`,
    '' AS field_more_info_value,
    `field_normativa_value`
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_prestamos`
  ORDER BY `content_type_prestamos`.`vid` ASC
);

-- Reset proyecto-ui
UPDATE `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_proyectos`
  SET `field_prioridad_value`   = 'Alta'
  WHERE `field_prioridad_value` = '1-Alta';
UPDATE `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_proyectos`
  SET `field_prioridad_value`   = 'Media'
  WHERE `field_prioridad_value` = '2-Media';
UPDATE `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_proyectos`
  SET `field_prioridad_value`   = 'Baja'
  WHERE `field_prioridad_value` = '3-Baja' OR `field_prioridad_value` = '' OR `field_prioridad_value` IS NULL;
UPDATE `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_proyectos`
  SET `field_urgencia_value`    = 'Muy urgente'
  WHERE `field_urgencia_value`  = '1-Muy urgente';
UPDATE `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_proyectos`
  SET `field_urgencia_value`    = 'Urgente'
  WHERE `field_urgencia_value`  = '2-Urgente' OR `field_urgencia_value` = 'urgente';
UPDATE `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_proyectos`
  SET `field_urgencia_value`    = 'Poco urgente'
  WHERE `field_urgencia_value`  = '3-Poco urgente' OR `field_urgencia_value` = 'poco urgente';
UPDATE `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_proyectos`
  SET `field_urgencia_value`    = 'Nada urgente'
  WHERE `field_urgencia_value`  = '4-Nada urgente' OR `field_urgencia_value` = 'nada urgente' OR `field_urgencia_value` = '' OR `field_urgencia_value` IS NULL;

-- Copy proyecto-ui
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`content_type_proyectos` (
  SELECT
    `content_type_proyectos`.`vid`,
    `content_type_proyectos`.`nid`,
    CASE
      WHEN `term_data`.`tid` = 147 THEN 'Construcción'
      WHEN `term_data`.`tid` = 148 THEN 'Producción'
      WHEN `term_data`.`tid` = 149 THEN 'Gestación'
      ELSE ''
    END AS 'field_fase_value',
    `field_area_value`,
    `field_prioridad_value`,
    `field_urgencia_value`,
    `field_equipohumano_value` AS 'field_equipo_value',
    `field_codigoestado_value` AS 'field_codigo_estado_value',
    `field_presupuesto_value`,
    NULL AS 'field_contratacion_value',
    '' AS 'field_recursos_value',
    '' AS 'field_seguimiento_value',
    '' AS 'field_technical_value',
    '' AS 'field_entornos_value',
    '' AS 'field_lecciones_value',
    0 AS 'field_actas_nid',
    `field_responsable_uid`,
    0 AS 'field_forum_nid',
    `field_justificacion_value`,
    CASE
      WHEN `field_justificacion_format` = 2 THEN 3 -- > PHP Code
      WHEN `field_justificacion_format` = 3 THEN 2 -- > Full HTML
    END AS `field_justificacion_format`,
    `field_estado_value`,
    CASE
      WHEN `field_estado_format` = 2 THEN 3 -- > PHP Code
      WHEN `field_estado_format` = 3 THEN 2 -- > Full HTML
    END AS `field_estado_format`
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_proyectos`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_node` ON `term_node`.`nid` = `content_type_proyectos`.`nid`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_data` ON `term_data`.`tid` = `term_node`.`tid` AND `term_data`.`vid` = 23 -- Proyecto Tipo
  ORDER BY `content_type_proyectos`.`vid` ASC
);
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_plazo` (
  SELECT
    `vid`,
    `nid`,
    `field_plazo_value`
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`content_type_proyectos`
  ORDER BY `content_type_proyectos`.`vid` ASC
);

/*******************************************************************************
 * FILES PROCCESS
 ******************************************************************************/
-- Reset files
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`files`;

-- Copy files
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`files` (
  SELECT
    `fid`,
    '1' AS 'uid',
    `filename`,
    CONCAT('sites/default/files/antiguos_', `filepath`) AS 'filepath',
    `filemime`,
    `filesize`,
    1 AS 'status',
    UNIX_TIMESTAMP() AS 'timestamp'
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`files`
  WHERE `files`.`filename` NOT IN ('thumbnail', 'preview')
  ORDER BY `fid` ASC
);

-- Reset files from fields
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_image`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_files`;

-- Copy files to fields
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_image` (
  SELECT
    `node`.`vid`,
    `node`.`nid`,
    `files`.`fid` AS 'field_image_fid',
    1 AS field_image_list,
    NULL AS 'field_image_data'
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`files` ON `files`.`nid` = `node`.`nid`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_node` ON `term_node`.`nid` = `node`.`nid`
  WHERE `node`.`type` = 'image'
  AND `files`.`filename` NOT IN ('thumbnail', 'preview')
  ORDER BY `fid` ASC
);

-- Update old filepaths
UPDATE `DATABASE_PLACEHOLDER_DRUPAL6`.`node_revisions` SET `body` = REPLACE(`body`, 'system/files', 'sites/default/files/antiguos_ficheros');
UPDATE `DATABASE_PLACEHOLDER_DRUPAL6`.`node_revisions` SET `teaser` = REPLACE(`teaser`, 'system/files', 'sites/default/files/antiguos_ficheros');

-- Procedure to files image
DELIMITER $$

USE `DATABASE_PLACEHOLDER_DRUPAL6`$$

DROP PROCEDURE IF EXISTS `company_custom_procedure`$$

CREATE DEFINER=`DATABASE_PLACEHOLDER_USER`@`%` PROCEDURE `company_custom_procedure`()

BEGIN

    DECLARE vid1 INT;
    DECLARE nid1 INT;
    DECLARE fid1 INT;
    DECLARE title1 VARCHAR(255);
    DECLARE delta1 INT DEFAULT 0;
    DECLARE finish INT DEFAULT 0;
    DECLARE cursor1 CURSOR FOR SELECT `node`.`vid`, `node`.`nid`, `files`.`fid` FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node` INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`files` ON `node`.`nid` = `files`.`nid` ORDER BY `files`.`fid` ASC;
    DECLARE cursor2 CURSOR FOR SELECT `node`.`vid`, `node`.`nid`, `files`.`fid` FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node` INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`image_attach` ON `image_attach`.`nid` = `node`.`nid` INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`files` ON `files`.`nid` = `image_attach`.`iid` WHERE `files`.`filename` = '_original' ORDER BY `files`.`fid` ASC;
    DECLARE cursor3 CURSOR FOR SELECT `node`.`title`, `files`.`fid` FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node` INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`image_attach` ON `image_attach`.`iid` = `node`.`nid` INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`files` ON `files`.`nid` = `image_attach`.`iid` WHERE `files`.`filename` = '_original' ORDER BY `files`.`fid` ASC;

    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET finish = 1;

    OPEN cursor1;
    OPEN cursor2;
    OPEN cursor3;

    REPEAT

      FETCH cursor1 INTO vid1, nid1, fid1;
      SET delta1 = (SELECT COUNT(`content_field_files`.`delta`) FROM `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_files` WHERE `vid` = vid1);
      INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_files` VALUES (vid1, nid1, delta1, fid1, 1, NULL);

    UNTIL finish END REPEAT;

    SET finish = 0;

    REPEAT

      FETCH cursor2 INTO vid1, nid1, fid1;
      SET delta1 = (SELECT COUNT(`content_field_files`.`delta`) FROM `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_files` WHERE `vid` = vid1);
      INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`content_field_files` VALUES (vid1, nid1, delta1, fid1, 1, NULL);

    UNTIL finish END REPEAT;

    SET finish = 0;

    REPEAT

      FETCH cursor3 INTO title1, fid1;
      UPDATE `DATABASE_PLACEHOLDER_DRUPAL6`.`files` SET `files`.`filename` = title1 WHERE `files`.`fid` = fid1;

    UNTIL finish END REPEAT;

    CLOSE cursor1;
    CLOSE cursor2;
    CLOSE cursor3;

  END$$

DELIMITER ;

CALL company_custom_procedure();
DROP PROCEDURE IF EXISTS `company_custom_procedure`;

/*******************************************************************************
 * COMMENTS PROCCESS
 ******************************************************************************/
-- Reset comments
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`comments`;

-- Copy comments
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`comments` (
  SELECT
    `cid`,
    `pid`,
    `nid`,
    `uid`,
    `subject`,
    `comment`,
    `hostname`,
    `timestamp`,
    `status`,
    `format`,
    `thread`,
    `name`,
    `mail`,
    `homepage`
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`comments`
  ORDER BY `comments`.`cid` ASC
);

/*******************************************************************************
 * TERMS AND TAXONOMYS PROCCESS
 ******************************************************************************/