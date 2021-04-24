-- Insert node terms departamentos
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`term_node` (
  SELECT
    `node`.`nid`,
    `node`.`vid`,
    CASE
      WHEN `term_data`.`tid` = 82  THEN 54 -- > Secretaría General
      WHEN `term_data`.`tid` = 83  THEN 53 -- > Dirección General
      WHEN `term_data`.`tid` = 84  THEN 63 -- > Informática
      WHEN `term_data`.`tid` = 85  THEN 60 -- > Financiero
      WHEN `term_data`.`tid` = 86  THEN 61 -- > Sanitarias
      WHEN `term_data`.`tid` = 87  THEN 62 -- > Sociales
      WHEN `term_data`.`tid` = 88  THEN 64 -- > Inspección
      WHEN `term_data`.`tid` = 89  THEN 65 -- > Sindicatos
      WHEN `term_data`.`tid` = 92  THEN 56 -- > Asustons Generales
      WHEN `term_data`.`tid` = 103 THEN 57 -- > Formación
      WHEN `term_data`.`tid` = 104 THEN 55 -- > Acción Social
      WHEN `term_data`.`tid` = 110 THEN 66 -- > CC.OO
      WHEN `term_data`.`tid` = 111 THEN 67 -- > CSIF
      WHEN `term_data`.`tid` = 112 THEN 68 -- > SAP
      WHEN `term_data`.`tid` = 113 THEN 69 -- > UGT
      WHEN `term_data`.`tid` = 114 THEN 70 -- > USO
      WHEN `term_data`.`tid` = 123 THEN 58 -- > Jornadas
      WHEN `term_data`.`tid` = 125 THEN 59 -- > Personal
    END AS 'tid'
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_node` ON `term_node`.`nid` = `node`.`nid`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_data` ON `term_data`.`tid` = `term_node`.`tid` AND `term_data`.`vid` = 19 -- Departamentos
  ORDER BY `node`.`nid` ASC
);

-- Insert node terms tipo de pagina
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`term_node` (
  SELECT
    `node`.`nid`,
    `node`.`vid`,
    CASE
      WHEN `term_data`.`tid` = 93  THEN 44 -- > Normativa
      WHEN `term_data`.`tid` = 94  THEN 42 -- > Solo hay 1 nodo aquí
      WHEN `term_data`.`tid` = 95  THEN 43 -- > Información Genérica
      WHEN `term_data`.`tid` = 96  THEN 45 -- > Resoluciones
      WHEN `term_data`.`tid` = 97  THEN 48 -- > Ordenes de Servicio
      WHEN `term_data`.`tid` = 98  THEN 46 -- > Instrucciones
      WHEN `term_data`.`tid` = 99  THEN 49 -- > Otras Normas
      WHEN `term_data`.`tid` = 109 THEN 50 -- > Convenios
      WHEN `term_data`.`tid` = 120 THEN 41 -- > Manuales SIGMA
      WHEN `term_data`.`tid` = 121 THEN 42 -- > Otros Manuales
      WHEN `term_data`.`tid` = 140 THEN 47 -- > Criterios
      WHEN `term_data`.`tid` = 142 THEN 51 -- > Memorias company
    END AS 'tid'
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_node` ON `term_node`.`nid` = `node`.`nid`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_data` ON `term_data`.`tid` = `term_node`.`tid` AND `term_data`.`vid` = 20 -- Tipo de Página
  ORDER BY `node`.`nid` ASC
);

-- Insert node terms destinatario
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`term_node` (
  SELECT
    `node`.`nid`,
    `node`.`vid`,
    CASE
      WHEN `term_data`.`tid` = 100 THEN 14 -- > SS.PP.
      WHEN `term_data`.`tid` = 101 THEN 13 -- > SS.CC.
      WHEN `term_data`.`tid` = 102 THEN 13 -- > General
    END AS 'tid'
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_node` ON `term_node`.`nid` = `node`.`nid`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_data` ON `term_data`.`tid` = `term_node`.`tid` AND `term_data`.`vid` = 21 -- Destinatarios
  ORDER BY `node`.`nid` ASC
);
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`term_node` (
  SELECT
    `node`.`nid`,
    `node`.`vid`,
    14 AS 'tid'
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_node` ON `term_node`.`nid` = `node`.`nid`
  WHERE `term_node`.`tid` = 102
  ORDER BY `node`.`nid` ASC
);

-- Insert node terms albums de fotos
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`term_node` (
  SELECT
    `node`.`nid`,
    `node`.`vid`,
    CASE
      WHEN `term_data`.`tid` = 78  THEN 190 -- > III Concurso de Pintura 2007
      WHEN `term_data`.`tid` = 79  THEN 189 -- > II Concurso de Pintura 2006
      WHEN `term_data`.`tid` = 80  THEN 188 -- > I Concurso de Pintura 2005
      WHEN `term_data`.`tid` = 130 THEN 186 -- > Despedida de Armando Bronca Segura
      WHEN `term_data`.`tid` = 144 THEN 191 -- > IV Concurso de Pintura 2008
      WHEN `term_data`.`tid` = 145 THEN 187 -- > Despedida Clavel
      WHEN `term_data`.`tid` = 180 THEN 194 -- > Modalidad B
      WHEN `term_data`.`tid` = 181 THEN 193 -- > Modalidad A
      WHEN `term_data`.`tid` = 185 THEN 195 -- > V Concurso de Pintura 2009
      WHEN `term_data`.`tid` = 212 THEN 215 -- > Otros
      WHEN `term_data`.`tid` = 76  THEN (   -- > Album itself, need to parse with its name
        CASE
          WHEN `node`.`title` = 'III Concurso de Pintura 2007'       THEN 190
          WHEN `node`.`title` = 'II Concurso de Pintura 2006'        THEN 189
          WHEN `node`.`title` = 'I Concurso de Pintura 2005'         THEN 188
          WHEN `node`.`title` = 'Despedida de Armando Bronca Segura' THEN 186
          WHEN `node`.`title` = 'IV Concurso de Pintura 2008'        THEN 191
          WHEN `node`.`title` = 'Primer Certamen de TRUFART 2009'    THEN 192
          WHEN `node`.`title` = 'Despedida Clavel'                   THEN 187
          WHEN `node`.`title` = 'V Concurso de Pintura 2009'         THEN 195
          ELSE 215 -- > Set 'Otros' as default album with no previous cases
        END
      )
      WHEN `term_data`.`tid` = 177 THEN (   -- > Sub-albums, need to parse with its name
        CASE
          WHEN `node`.`title` = 'Modalidad A'                        THEN 193
          WHEN `node`.`title` = 'Modalidad B'                        THEN 194
        END
      )
    END AS 'tid'
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_node` ON `term_node`.`nid` = `node`.`nid`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_data` ON `term_data`.`tid` = `term_node`.`tid` AND `term_data`.`vid` = 18 -- Albums de fotos
  ORDER BY `node`.`nid` ASC
);

-- Insert node terms frecuencia de cubos
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`term_node` (
  SELECT
    `node`.`nid`,
    `node`.`vid`,
    CASE
      WHEN `term_data`.`tid` = 203 THEN 37 -- > Diario
      WHEN `term_data`.`tid` = 204 THEN 40 -- > Semanal
      WHEN `term_data`.`tid` = 205 THEN 38 -- > Mensual
      WHEN `term_data`.`tid` = 206 THEN 39 -- > Puntual
    END AS 'tid'
  FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`node`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_node` ON `term_node`.`nid` = `node`.`nid`
  INNER JOIN `DATABASE_PLACEHOLDER_DRUPAL5`.`term_data` ON `term_data`.`tid` = `term_node`.`tid` AND `term_data`.`vid` = 24 -- Frecuencia de cubos
  ORDER BY `node`.`nid` ASC
);

/*******************************************************************************
 * ACCESS AND STATISTICS PROCCESS
 ******************************************************************************/
-- Reset accesslog
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`accesslog`;

-- Copy accesslogs
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`accesslog` (SELECT * FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`accesslog`);

-- Reset acl permissions
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`acl`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`acl_node`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`acl_user`;

-- Copy acl permissions
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`acl` (SELECT *, NULL AS 'number' FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`acl`);
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`acl_node` (SELECT *, 0 AS 'priority' FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`acl_node`);
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`acl_user` (SELECT * FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`acl_user`);

-- Reset votingapi
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`votingapi_cache`;
TRUNCATE `DATABASE_PLACEHOLDER_DRUPAL6`.`votingapi_vote`;

-- Copy votingapi
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`votingapi_cache` (SELECT * FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`votingapi_cache`);
INSERT INTO `DATABASE_PLACEHOLDER_DRUPAL6`.`votingapi_vote` (SELECT * FROM `DATABASE_PLACEHOLDER_DRUPAL5`.`votingapi_vote`);