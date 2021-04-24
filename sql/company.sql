-- Con esta SQL sacamos los tipos de contenido y vemos si tienen o no contenidos
SELECT COUNT(*), n.* FROM company_vieja.node n GROUP BY n.type ORDER BY COUNT(*) DESC;

-- Primero buscamos todas las tablas que usan el campo nid para obtener despues las referencias
SELECT table_name FROM information_schema.columns WHERE table_schema = 'company_vieja' AND column_name ='nid';

-- Ahora lanzamos esta query para ver si hay registros en dichas tablas
SELECT *
FROM company_vieja.node n
INNER JOIN company_vieja.scheduler a ON a.nid = n.nid
WHERE n.type = 'page';

SELECT MAX(`nid`) + 1 FROM `node`;

SELECT *
FROM information_schema.tables
WHERE table_name   = 'node'
  AND table_schema = 'company_nueva';

DELETE FROM node WHERE nid = 5000;
INSERT INTO `node` (`nid`, `vid`, `type`, `language`, `title`, `uid`, `status`, `created`, `changed`, `comment`, `promote`, `moderate`, `sticky`, `tnid`, `translate`, `nid_reference`)
VALUES (5000, 5000, 'dummy', 'es', 'Dummy content to set nid', 0, 1, UNIX_TIMESTAMP(NOW()), UNIX_TIMESTAMP(NOW()), 0, 0, 0, 0, 0, 0, 69);



SELECT DISTINCT n.`nid` AS drupal_nid
FROM `node` n
INNER JOIN `acl_node` a ON a.`nid` = n.`nid`
WHERE n.`type` = 'page'



SELECT * FROM company_vieja.node WHERE nid = 1673;

TRUNCATE company.watchdog;

DELETE FROM company_nueva.node WHERE nid > 5000;

SELECT
  -- This shit is for Pentaho
  CONCAT('http://172.21.1.94/company/pentaho/node/', `nid`, '.xml') AS pentaho_url,
  `nid` AS nid_tbl_node,
  `nid` + 5000 AS nid_new,
  `vid` + 5000 AS vid_new,
  `type` AS type_tbl_node,
  'es' AS language_tbl_node,
  `title` AS title_tbl_node,
  `uid` AS uid_tbl_node,
  `status` AS status_tbl_node,
  `created` AS created_tbl_node,
  `changed` AS changed_tbl_node,
  `comment` AS comment_tbl_node,
  0 AS promote_tbl_node,
  `moderate` AS moderate_tbl_node,
  `sticky` AS sticky_tbl_node,
  0 AS tnid_tbl_node,
  0 AS translate_tbl_node
FROM `company_vieja`.`node`
WHERE `type` = 'page';



SELECT *
FROM company_vieja.term_data
WHERE vid = 19; -- 19/Departamentos, 20/Tipo de Información, 21/Destinatarios