/**
 * UPDATE NODE TITLES WITH THEIR REVISIONS TOO
 */
UPDATE `node_revision`, `node`
SET `node_revision`.`title` = CONCAT(`node`.`type`, ' : ', `node_revision`.`title`),
`node`.`title` = CONCAT(`node`.`type`, ' : ', `node`.`title`)
WHERE `node_revision`.`nid` = `node`.`nid`
AND `node`.`type` IN (
  'attraction', 'banner', 'banner_carousel', 'calendar_park',
  'faq', 'highlight', 'image', 'park', 'partner', 'service',
  'text', 'vacant', 'video'
)
AND `node_revision`.`title` NOT LIKE CONCAT(`node`.`type`, '%')
AND `node`.`title` NOT LIKE CONCAT(`node`.`type`, '%');

SELECT `node`.`title`, `node`.`type`, `node_revision`.`title`
FROM `node`
INNER JOIN `node_revision` ON `node_revision`.`nid` = `node`.`nid`
WHERE `node`.`type` IN (
  'attraction', 'banner', 'banner_carousel', 'calendar_park',
  'faq', 'highlight', 'image', 'park', 'partner', 'service',
  'text', 'vacant', 'video'
)
AND `node`.`title` LIKE CONCAT(`node`.`type`, '%')
OR `node_revision`.`title` LIKE CONCAT(`node`.`type`, '%');

/**
 * UPDATE THEMES IN PARQUES REUNIDOS WHEN COPY TO ANOTHER PARK
 */
UPDATE fau_dev.block SET theme = 'FAU_theme' WHERE theme = 'ZOO_theme';
UPDATE fau_dev.block SET theme = 'FAU_mobiletheme' WHERE theme = 'ZOO_mobiletheme';
UPDATE fau_dev.page_manager_handlers SET conf = REPLACE(conf, 'ZOO_theme', 'FAU_theme');
UPDATE fau_dev.page_manager_handlers SET conf = REPLACE(conf, 'ZOO_mobiletheme', 'FAU_mobiletheme');
UPDATE fau_dev.skinr_skins SET theme = 'FAU_theme' WHERE theme = 'ZOO_theme';
UPDATE fau_dev.skinr_skins SET theme = 'FAU_mobiletheme' WHERE theme = 'ZOO_mobiletheme';
INSERT INTO fau_dev.variable (
  SELECT
    REPLACE(NAME, 'ZOO', 'FAU') AS NAME,
    REPLACE(VALUE, 'ZOO_theme', 'FAU_theme') AS VALUE
  FROM fau_dev.variable
  WHERE NAME LIKE '%ZOO_theme%'
);
INSERT INTO fau_dev.variable (
  SELECT
    REPLACE(NAME, 'ZOO', 'FAU') AS NAME,
    REPLACE(VALUE, 'ZOO_mobiletheme', 'FAU_mobiletheme') AS VALUE
  FROM fau_dev.variable
  WHERE NAME LIKE '%ZOO_mobiletheme%'
);
UPDATE fau_dev.system SET STATUS = 1 WHERE NAME IN ('FAU_theme', 'FAU_mobiletheme') AND TYPE = 'theme';