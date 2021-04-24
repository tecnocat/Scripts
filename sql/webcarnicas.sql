/*
 * Cambios para Web Carnicas en los tipos de contenido page
 */
USE `webcarnicas`;

-- Renombramos los tipos de contenido paginas a uno único
-- select * from `node` where `node`.`type` like '%page%' group by `node`.`type`;
UPDATE `node` SET  `node`.`type` = 'page' WHERE `node`.`type` LIKE 'page_%';

-- Cambio los tipos de contenido en la tabla vocabulary_node_types
UPDATE `vocabulary_node_types` SET  `vocabulary_node_types`.`type` = 'page' WHERE `vocabulary_node_types`.`type` LIKE 'page_%';

-- Copia de body en node_revisions
UPDATE `node_revisions`
INNER JOIN `node` ON `node`.`vid` = `node_revisions`.`vid`
INNER JOIN `content_field_cuerpo` ON `content_field_cuerpo`.`vid` = `node`.`vid`
SET `node_revisions`.`body` = `content_field_cuerpo`.`field_cuerpo_value`
WHERE `node`.`type` LIKE 'page_%' AND `content_field_cuerpo`.`field_cuerpo_value` IS NOT NULL;

-- Crear tipo de contenido 'sellsmoke' y ejecutar esta query
UPDATE `node` SET  `node`.`type` = 'sellsmoke' WHERE `node`.`type` LIKE 'servicio' OR `node`.`type` LIKE 'solucion_tecnologica' OR `node`.`type` LIKE 'tecnologia';

-- Esto es una broma para testing jeje
UPDATE `users` SET `pass` = MD5('unknow') WHERE `uid` = 1;