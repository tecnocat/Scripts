SELECT COUNT(*), TYPE FROM company_vieja.node GROUP BY TYPE ORDER BY COUNT(*) DESC;

SELECT n.nid, n.vid, n.type, n.title, v.nid, v.vid, v.type, v.title
FROM company_nueva.node n, company_vieja.node v
WHERE n.type = 'album' AND v.type ='acidfree';

SELECT CONCAT('WHEN `term_data`.`tid` = ', vieja.`tid`, ' THEN ', nueva.`tid`, ' -- > ', vieja.`name`) AS 'CASE'
FROM `company_nueva`.`term_data` nueva
LEFT JOIN `company_vieja`.`term_data` vieja ON vieja.`name` = nueva.`name`
WHERE nueva.`vid` = 7
ORDER BY vieja.`tid` ASC;

SELECT CONCAT('WHEN `term_data`.`tid` = ', vieja.`tid`, ' THEN ', nueva.`tid`, ' -- > ', vieja.`name`) AS 'CASE'
FROM `company_nueva`.`term_data` nueva
LEFT JOIN `company_vieja`.`term_data` vieja ON vieja.`name` = nueva.`name`
WHERE vieja.`vid` = 18
ORDER BY vieja.`tid` ASC;

SELECT * FROM company_vieja.term_data ORDER BY vid ASC;

SELECT *
FROM node n
INNER JOIN term_node t ON t.nid = n.nid
LEFT JOIN term_data d ON d.tid = t.tid
WHERE n.nid IN (1140, 1125, 481);

SELECT * FROM node WHERE TYPE = 'image';

/*
pillar tipo image asociado a las taxonomias que son albums y deberían asociarse a n.type = 'album'
el resto de images son fotos que deberían estar asociadas a los terms de album
*/

-- Change some images to albums
SELECT
  `node`.`nid`,
  `node`.`type`,
  `node`.`title`,
  `term_node`.`tid`,
  `term_data`.`vid`,
  `term_data`.`name`
FROM `company_vieja`.`node`
INNER JOIN `company_vieja`.`term_node` ON `term_node`.`nid` = `node`.`nid`
INNER JOIN `company_vieja`.`term_data` ON `term_data`.`tid` = `term_node`.`tid`
WHERE `node`.`type` = 'acidfree'
LIMIT 5000;

SELECT
  `node`.`nid`,
  `node`.`vid`,
  `node`.`type`,
  `node`.`title`,
  `term_node`.*
FROM `company_nueva`.`node`
INNER JOIN `company_nueva`.`term_node` ON `term_node`.`nid` = `node`.`nid`
WHERE `node`.`nid` IN (
  SELECT `node`.`nid`
  FROM `company_vieja`.`node`
  INNER JOIN `company_vieja`.`term_node` ON `term_node`.`nid` = `node`.`nid`
  INNER JOIN `company_vieja`.`term_data` ON `term_data`.`tid` = `term_node`.`tid`
  WHERE `node`.`type` = 'acidfree'
);

SELECT i.*, n.*, f.* FROM node n
INNER JOIN content_field_image i ON i.nid = n.nid
INNER JOIN files f ON f.fid = i.field_image_fid
WHERE n.nid = 337;

INSERT INTO `company_nueva`.`term_node` (
  SELECT
    `node`.`nid`,
    `node`.`vid`,
    14 AS tid
  FROM `company_vieja`.`node`
  INNER JOIN `company_vieja`.`term_node` ON `term_node`.`nid` = `node`.`nid`
  WHERE `term_node`.`tid` = 102
);

TRUNCATE `company_nueva`.`acl`;
TRUNCATE `company_nueva`.`acl_node`;
TRUNCATE `company_nueva`.`acl_user`;
TRUNCATE `company_nueva`.`content_access`;
TRUNCATE `company_nueva`.`node_access`;

SELECT *
FROM `company_nueva`.`acl`
INNER JOIN `company_nueva`.`acl_node` ON `acl_node`.`acl_id` = `acl`.`acl_id`
INNER JOIN `company_nueva`.`content_access` ON `content_access`.`nid` = `acl_node`.`nid`
INNER JOIN `company_nueva`.`node_access` ON `node_access`.`nid` = `acl_node`.`nid`
INNER JOIN `company_nueva`.`role` ON `role`.`rid` = `node_access`.`gid`

SELECT `node`.`nid`, `node`.`vid`, `term_node`.`tid`
FROM `company_vieja`.`node`
INNER JOIN `company_vieja`.`term_node` ON `term_node`.`nid` = `node`.`nid`
WHERE `term_node`.`tid` BETWEEN 100 AND 102
ORDER BY `node`.`nid` ASC;

SELECT * FROM files WHERE filepath LIKE '%NOVEDADES%' ORDER BY 'filename';

UPDATE `company_nueva`.`node_revisions` SET `body` = REPLACE(`body`, 'system/files', 'sites/default/files/antiguos_ficheros');
UPDATE `company_nueva`.`node_revisions` SET `teaser` = REPLACE(`teaser`, 'system/files', 'sites/default/files/antiguos_ficheros');
SELECT * FROM node_revisions WHERE body LIKE '%EFECTIVOS+%';