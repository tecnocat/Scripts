SELECT COUNT(*) AS Total, n.type, n.* FROM node n GROUP BY n.type ORDER BY Total DESC;

/*
148  páginas
62   fotos
44   libros
17   proyectillos
15   cuencos
10   presentaciones
14,8 horas para migrar el portal
*/


SELECT * FROM company_nueva.node WHERE nid >= 5000;

SELECT * FROM node WHERE TYPE = 'page' ORDER BY nid ASC;

TRUNCATE watchdog;

SELECT
  `node`.`nid`,
  `node`.`type`,
  'es' AS 'language',
  `node`.`title`,
  MAX(`node_revisions`.`body`) AS body,
  `node`.`uid`,
  `node`.`status`,
  `node`.`created`,
  `node`.`changed`,
  `node`.`comment`,
  0 AS promote,
  `node`.`moderate`,
  `node`.`sticky`,
  0 AS tnid,
  0 AS translate,
  `node`.`nid` AS nid_reference,
  GROUP_CONCAT(dpto.`tid`) AS tid_dpto,
  GROUP_CONCAT(dsto.`tid`) AS tid_dsto,
  GROUP_CONCAT(page.`tid`) AS tid_page
  /*
  CASE
    WHEN `term_node`.`tid` = 82  THEN 54 -- > Secretaría General
    WHEN `term_node`.`tid` = 83  THEN 53 -- > Dirección General
    WHEN `term_node`.`tid` = 84  THEN 63 -- > Informática
    WHEN `term_node`.`tid` = 85  THEN 60 -- > Financiero
    WHEN `term_node`.`tid` = 86  THEN 61 -- > Sanitarias
    WHEN `term_node`.`tid` = 87  THEN 62 -- > Sociales
    WHEN `term_node`.`tid` = 88  THEN 64 -- > Inspección
    WHEN `term_node`.`tid` = 89  THEN 65 -- > Sindicatos
    WHEN `term_node`.`tid` = 92  THEN 56 -- > Asustons Generales
    WHEN `term_node`.`tid` = 103 THEN 57 -- > Formación
    WHEN `term_node`.`tid` = 104 THEN 55 -- > Acción Social
    WHEN `term_node`.`tid` = 110 THEN 66 -- > CC.OO
    WHEN `term_node`.`tid` = 111 THEN 67 -- > CSIF
    WHEN `term_node`.`tid` = 112 THEN 68 -- > SAP
    WHEN `term_node`.`tid` = 113 THEN 69 -- > UGT
    WHEN `term_node`.`tid` = 114 THEN 70 -- > USO
    WHEN `term_node`.`tid` = 123 THEN 58 -- > Jornadas
    WHEN `term_node`.`tid` = 125 THEN 59 -- > Personal
  END AS tid_dpto,
  CASE
    when `term_node`.`tid` = 100 then 14 -- > SS.PP.
    when `term_node`.`tid` = 101 then 13 -- > SS.CC.
    WHEN `term_node`.`tid` = 102 then 13 -- > General
  end as tid_dsto,
  CASE
    WHEN `term_node`.`tid` = 95  THEN 43 -- > Información Genérica
    WHEN `term_node`.`tid` = 93  THEN 44 -- > Normativa
    WHEN `term_node`.`tid` = 96  THEN 45 -- > -Resoluciones
    WHEN `term_node`.`tid` = 98  THEN 46 -- > -Instrucciones
    WHEN `term_node`.`tid` = 140 THEN 47 -- > -Criterios
    WHEN `term_node`.`tid` = 97  THEN 48 -- > -Ordenes de Servicio
    WHEN `term_node`.`tid` = 121 THEN 49 -- > -Otras Normas
    WHEN `term_node`.`tid` = 109 THEN 50 -- > -Convenios
    WHEN `term_node`.`tid` = 142 THEN 51 -- > Memorias company
  end as tid_page,
  GROUP_CONCAT(dpto.`name`) as departamento,
  GROUP_CONCAT(dsto.`name`) AS destinatario,
  GROUP_CONCAT(page.`name`) AS tipopagina
  */
FROM `node`
INNER JOIN `node_revisions` ON `node_revisions`.`vid` = `node`.`vid`
INNER JOIN `term_node` ON `term_node`.`nid` = `node`.`nid`
LEFT JOIN `term_data` dpto ON dpto.`tid` = `term_node`.`tid` AND dpto.`vid` = 19 -- Departamentos
LEFT JOIN `term_data` dsto ON dsto.`tid` = `term_node`.`tid` AND dsto.`vid` = 21 -- Destinatario
LEFT JOIN `term_data` page ON page.`tid` = `term_node`.`tid` AND page.`vid` = 20 -- Tipo de Página
WHERE `type` = 'page'
GROUP BY `node`.`nid`
ORDER BY `node`.`nid` ASC
LIMIT 10;


SELECT
  `node`.`nid`,
  `node`.`type`,
  'es' AS 'language',
  `node`.`title`,
  MAX(`node_revisions`.`body`) AS body,
  `node`.`uid`,
  `node`.`status`,
  `node`.`created`,
  `node`.`changed`,
  `node`.`comment`,
  0 AS promote,
  `node`.`moderate`,
  `node`.`sticky`,
  0 AS tnid,
  0 AS translate,
  CASE
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 82  THEN 54 -- > Secretaría General
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 83  THEN 53 -- > Dirección General
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 84  THEN 63 -- > Informática
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 85  THEN 60 -- > Financiero
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 86  THEN 61 -- > Sanitarias
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 87  THEN 62 -- > Sociales
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 88  THEN 64 -- > Inspección
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 89  THEN 65 -- > Sindicatos
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 92  THEN 56 -- > Asustons Generales
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 103 THEN 57 -- > Formación
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 104 THEN 55 -- > Acción Social
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 110 THEN 66 -- > CC.OO.
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 111 THEN 67 -- > CSIF
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 112 THEN 68 -- > SAP
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 113 THEN 69 -- > UGT
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 114 THEN 70 -- > USO
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 123 THEN 58 -- > Jornadas
    WHEN CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) = 125 THEN 59 -- > Personal
  END AS departamento,
  CASE
    WHEN CAST(GROUP_CONCAT(dsto.`tid`) AS CHAR) = 100 THEN 14 -- > SS.PP.
    WHEN CAST(GROUP_CONCAT(dsto.`tid`) AS CHAR) = 101 THEN 13 -- > SS.CC.
    WHEN CAST(GROUP_CONCAT(dsto.`tid`) AS CHAR) = 102 THEN 13 -- > General
  END AS destinatario,
  CASE
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 93  THEN 44 -- > Normativa
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 94  THEN 42 -- > Solo hay 1 nodo aquí
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 95  THEN 43 -- > Información Genérica
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 96  THEN 45 -- > Resoluciones
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 97  THEN 48 -- > Ordenes de Servicio
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 98  THEN 46 -- > Instrucciones
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 99  THEN 49 -- > Otras Normas
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 109 THEN 50 -- > Convenios
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 120 THEN 41 -- > Manuales SIGMA
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 121 THEN 42 -- > Otros Manuales
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 140 THEN 47 -- > Criterios
    WHEN CAST(GROUP_CONCAT(page.`tid`) AS CHAR) = 142 THEN 51 -- > Memorias company
  END AS tipodepagina,
  CAST(GROUP_CONCAT(page.`tid`) AS CHAR) AS page2,
  -- CAST(GROUP_CONCAT(dpto.`tid`) AS CHAR) AS departamento,
  -- CAST(GROUP_CONCAT(dsto.`tid`) AS CHAR) AS destinatario,
  -- CAST(GROUP_CONCAT(page.`tid`) AS CHAR) AS tipodepagina,
  `node`.`nid` AS nid_reference
FROM `node`
INNER JOIN `node_revisions` ON `node_revisions`.`vid` = `node`.`vid`
INNER JOIN `term_node` ON `term_node`.`nid` = `node`.`nid`
LEFT JOIN `term_data` dpto ON dpto.`tid` = `term_node`.`tid` AND dpto.`vid` = 19 -- Departamentos
LEFT JOIN `term_data` dsto ON dsto.`tid` = `term_node`.`tid` AND dsto.`vid` = 21 -- Destinatario
LEFT JOIN `term_data` page ON page.`tid` = `term_node`.`tid` AND page.`vid` = 20 -- Tipo de Página
WHERE `type` = 'page'
AND `node`.`nid` = 38
GROUP BY `node`.`nid`
ORDER BY `node`.`nid` ASC;

SELECT tid, NAME FROM company_vieja.term_data WHERE vid = 20;
SELECT tid, NAME FROM company_nueva.term_data WHERE vid = 11;

SELECT *
FROM `users` u
LEFT JOIN
WHERE u.`uid` NOT IN (0, 1)
ORDER BY u.`uid` ASC;

SELECT *
FROM `image_attach`
LEFT JOIN `files` ON `files`.`nid` = `image_attach`.`iid`
WHERE `image_attach`.`nid` = 6 AND `files`.`filename` = '_original';

SELECT * FROM files WHERE nid = 466;
SELECT * FROM image_attach WHERE nid = 6;
SELECT * FROM information_schema.columns WHERE table_schema = 'company_vieja' AND column_name = 'iid';
SELECT n.nid, n.title FROM node n WHERE n.status = 1 AND TYPE = 'image' AND nid = '466' ORDER BY n.sticky DESC, n.title ASC;