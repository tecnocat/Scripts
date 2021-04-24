/* ejemplos de fechas */
INSERT INTO fechas VALUES ('', '2011-22-02', '2011-22-02 11:33', '2011-22-02 11:33', '11:33', '2011')
SELECT * FROM fechas
INSERT INTO fechas VALUES ('', CURRENT_DATE, NOW(), CURRENT_TIMESTAMP, TIME(NOW()), EXTRACT(YEAR FROM NOW()))
SELECT ADDDATE(CURRENT_TIMESTAMP + 1 MONTH)

/* con engine InnoDB tarda mas que con MyISAM */
SELECT COUNT(*) FROM `time_performance_final`

/* cambiando engine se mejora el rendimiento */
CREATE TABLE `time_performance_final_myisam` (
  `Año` YEAR(4) DEFAULT NULL,
  `FechaVuelo` DATE DEFAULT NULL,
  `CodComp` CHAR(10) DEFAULT NULL,
  `AeroLineaId` SMALLINT(6) DEFAULT NULL,
  `Comp` CHAR(10) DEFAULT NULL,
  `NumTail` CHAR(10) DEFAULT NULL,
  `NumVuelo` CHAR(5) DEFAULT NULL,
  `Origen` CHAR(4) DEFAULT NULL,
  `NombreCiudadOrigen` VARCHAR(40) DEFAULT NULL,
  `CodEstadoOrigen` CHAR(3) DEFAULT NULL,
  `CodEstadoFipsOrigen` CHAR(3) DEFAULT NULL,
  `NombreEstadoOrigen` VARCHAR(40) DEFAULT NULL,
  `CodWACOrigen` TINYINT(4) DEFAULT NULL,
  `Destino` CHAR(3) DEFAULT NULL,
  `NombreCiudadDestino` VARCHAR(40) DEFAULT NULL,
  `CodEstadoDestino` CHAR(3) DEFAULT NULL,
  `CodEstadoFipsDestino` CHAR(3) DEFAULT NULL,
  `NombreEstadoDestino` VARCHAR(40) DEFAULT NULL,
  `CodWACDestino` TINYINT(4) DEFAULT NULL,
  `HoraSalidaPrev` TIME DEFAULT NULL,
  `HoraSalida` TIME DEFAULT NULL,
  `Retraso15` TINYINT(4) DEFAULT NULL,
  `GrupoRetraso` TINYINT(4) DEFAULT NULL,
  `HoraLlegadaPrevista` TIME DEFAULT NULL,
  `HoraLlegada` TIME DEFAULT NULL,
  `Cancelada` TINYINT(4) DEFAULT NULL,
  `CodCancelacion` CHAR(1) DEFAULT NULL,
  `Desviado` TINYINT(4) DEFAULT NULL,
  `TiempoVueloPrevisto` SMALLINT(6) DEFAULT NULL,
  `TiempoVuelo` SMALLINT(6) DEFAULT NULL,
  `Distancia` SMALLINT(6) DEFAULT NULL,
  `RetrasoComp` SMALLINT(6) DEFAULT NULL,
  `RetrasoClima` SMALLINT(6) DEFAULT NULL,
  `RetrasoSNA` SMALLINT(6) DEFAULT NULL,
  `RetrasoSeguridad` SMALLINT(6) DEFAULT NULL,
  `RetrasoTripulacion` SMALLINT(6) DEFAULT NULL,
  `RetrasoTotal` SMALLINT(6) DEFAULT NULL
) ENGINE=MYISAM

SELECT COUNT(*) FROM `time_performance_final_myisam`

/* uso de pseudoclaves (CURRENT_TIMESTAMP) */
INSERT INTO nombres (nombre) VALUES (CURRENT_TIMESTAMP)

/* filtrando mejor que LIKE '%' */
SELECT first_name AS 'Nombre',
	CASE WHEN SUBSTR(first_name,1,1) = 'p' THEN 'Empieza por P'
	ELSE 'No empieza por P'
	END AS 'Descripcion'
FROM sakila.actor a;

/* select con if */
SELECT IF (1<2,3,4) AS 'Condition';
SELECT 
	address2 AS 'Direccion 1', 
	COALESCE(
		IF(TRIM(sakila.address.address2) = '', 'No tiene segunda direccion', address2), 
		'No tiene segunda dirección'
	) AS 'Direccion 2' 
FROM sakila.address;

/* select con LIKE */
SELECT * FROM actor WHERE actor.first_name LIKE 'F%'; 
/*
'F%' busca en los indices por que existe rango,
rango FAAAAAAAA hasta FZZZZZZZZ
'%F%' no busca en los indices por que no hay rango 
rango AAAAAAAAA hasta ZZZZZZZZZ
*/

/* ordenacion con nulos */
SELECT address2 FROM address ORDER BY COALESCE(address2,'A') ASC;

CREATE TABLE pruebadistinc (a CHAR(10));
INSERT INTO pruebadistinc VALUES ('A'),('A'),('B');
SELECT DISTINCT a FROM pruebadistinc; 

/* practicas */
/* 3 */
SELECT DISTINCT a.first_name AS "Nombre empleado"
FROM actor a
ORDER BY first_name;
/* 5 */
SELECT 
	f.title AS 'Pelicula',
	f.film_id AS 'Id',
	CASE 
		WHEN f.rental_duration < 3 THEN 'Alquiler Corto'
		WHEN f.rental_duration BETWEEN 3 AND 5 THEN 'Alquiler Normal'
		WHEN f.rental_duration > 5 THEN 'Alquiler Largo'
		ELSE 'Alquiler super largo'
	END AS 'Alquiler'
FROM film f;
/* 6 */
SELECT 
	empleados.Nombre, 
	empleados.Apellidos
WHERE SUBDATE(CURRENT_DATE,INTERVAL 50 YEAR) > empleados.FechaNacimiento
ORDER BY empleados.FechaNacimiento;


/* joins */
/* MODO ANSI */
SELECT c.NombreCiudad AS 'Ciudad', d.CodigoPostal AS 'C.P.'
FROM ciudades c INNER JOIN direcciones d ON d.CiudadId = c.CiudadId
WHERE d.CodigoPostal = '13251' ORDER BY c.NombreCiudad;

/* MODO TRADICIONAL */
SELECT c.NombreCiudad AS 'Ciudad', d.CodigoPostal AS 'C.P.'
FROM ciudades c, direcciones d WHERE d.CiudadId = c.CiudadId
AND d.CodigoPostal = '13251' ORDER BY c.NombreCiudad;

/* ejercicios join */
/* 1 */
SELECT 
	a.actor_id AS 'Actor Id',
	a.first_name AS 'Nombre',
	a.last_name AS 'Apellido',
	f.film_id AS 'Film ID',
	f.title AS 'Pelicula'
FROM actor a
	INNER JOIN film_actor fa ON fa.actor_id = a.actor_id
	INNER JOIN film f ON f.film_id = fa.film_id
ORDER BY a.first_name;
/* 2 */
SELECT
	CONCAT(c.first_name,' ',c.last_name) AS 'Nombre',
	c.email AS 'E-mail'
FROM customer c
	INNER JOIN address a ON a.address_id = c.address_id
	INNER JOIN city i ON i.city_id = a.city_id
	INNER JOIN country o ON o.country_id = i.country_id
ORDER BY c.first_name;

/* 5 */
SELECT DISTINCT i.city_id, i.city FROM city i
	LEFT JOIN address a ON a.city_id = i.city_id
	LEFT JOIN customer c ON c.address_id = a.address_id
	LEFT JOIN rental r ON r.customer_id = c.customer_id
WHERE a.city_id IS NULL 
	OR c.address_id IS NULL 
	OR c.customer_id IS NULL;

/* ejercicio salarios */
CREATE TABLE catsalariales(cid CHAR(1), sueldo_min INTEGER, sueldo_max INTEGER);
INSERT INTO catsalariales VALUES ('C', 0, 20000),('B', 20001, 40000),('A', 40001, 60001);
/*  inner saca solo los que correspondan */
SELECT e.Nombre, e.Salario, c.cid AS 'Categoria' FROM empleados e
INNER JOIN catsalariales c ON e.Salario BETWEEN c.sueldo_min AND c.sueldo_max;
/* left saca todas aunque no correspondan */
SELECT e.Nombre, e.Salario, c.cid AS 'Categoria' FROM empleados e
LEFT JOIN catsalariales c ON e.Salario BETWEEN c.sueldo_min AND c.sueldo_max;

/* peliculas sin idioma original */
SELECT 
	COALESCE(f.original_language_id,'Sin Idioma original') AS 'Idioma ID',
	l.NAME AS 'Idioma',
	COUNT(*) AS 'Peliculas'
FROM film f
	LEFT JOIN LANGUAGE l ON l.language_id = f.language_id
GROUP BY l.language_id;

/* peliculas alquiladas en los ultimos 10 años */
SELECT 
	i.film_id AS 'Film ID',
	f.title AS 'Pelicula',
	COUNT(*) AS 'Veces'
FROM inventory i
	INNER JOIN film f ON i.film_id = f.film_id
	INNER JOIN rental r ON r.inventory_id = i.inventory_id
WHERE SUBDATE(CURRENT_DATE,INTERVAL 10 YEAR) < r.rental_date
GROUP BY i.film_id;

/* nombre de clientes que viven en London */
/* select (select (select city_id))) etc. */
SELECT CONCAT(c.first_name,' ',c.last_name) AS Nombre,
(
SELECT i.city 
FROM city i
INNER JOIN address a ON a.city_id = i.city_id
WHERE a.address_id = c.address_id
AND i.city = 'London'
) AS Ciudad
FROM customer c
ORDER BY Ciudad DESC;

/* progreso diario */
CREATE TABLE NumeroClientes (
fecha DATE,
apuntados INT);

INSERT INTO NumeroClientes VALUES
('2011-01-01', 10),
('2011-01-02', 14),
('2011-01-03', 28),
('2011-01-04', 22),
('2011-01-05', 14),
('2011-01-06', 18);

/* update a partir de subconsulta */
SELECT * FROM almacenes LIMIT 100;
SELECT * FROM direcciones LIMIT 100;
SELECT * FROM ciudades LIMIT 100;

ALTER TABLE ciudades ADD NumAlmacenes TINYINT;

SELECT c.CiudadId,COUNT(*) AS Almacenes FROM ciudades c
INNER JOIN direcciones d ON d.CiudadId = c.CiudadId
INNER JOIN almacenes a ON a.DireccionId = d.DireccionId
GROUP BY c.CiudadId;

UPDATE ciudades cd SET c.NumAlmacenes = (
  SELECT c.CiudadId,COUNT(*) AS Almacenes FROM ciudades c
  INNER JOIN direcciones d ON d.CiudadId = c.CiudadId
  INNER JOIN almacenes a ON a.DireccionId = d.DireccionId
  WHERE c.CiudadId = cd.CiudadId
  GROUP BY c.CiudadId
); /* mysql no permite la tabla a updatear en la subsonculta */

UPDATE ciudades cd LEFT JOIN (
  SELECT c.CiudadId,COUNT(*) AS Almacenes FROM ciudades c
  INNER JOIN direcciones d ON d.CiudadId = c.CiudadId
  INNER JOIN almacenes a ON a.DireccionId = d.DireccionId
  GROUP BY c.CiudadId
) AS Subconsulta ON cd.CiudadId = Subconsulta.CiudadId
SET cd.NumAlmacenes = COALESCE(Subconsulta.Almacenes, 0);


/* ejercicio telefonos */
CREATE TABLE Personnel
(emp_id INTEGER PRIMARY KEY,
first_name CHAR(20) NOT NULL,
last_name CHAR(20) NOT NULL);

CREATE TABLE Phones
(emp_id INTEGER NOT NULL,
phone_type CHAR(5) NOT NULL,
phone_nbr CHAR(12) NOT NULL,
PRIMARY KEY (emp_id, phone_type),
FOREIGN KEY (emp_id) REFERENCES Personnel(emp_id));

-- Inserción de datos

DELETE FROM Personnel;

INSERT INTO Personnel VALUES 
(1, 'Juan', 'Martinez'),
(2, 'Diana', 'Pulido'),
(3, 'Raul', 'Colgado'),
(4, 'Jose', 'Casas');

DELETE FROM phones;

INSERT INTO Phones VALUES 
(1,'Casa', '9122222'),
(2,'Movil', '937497'),
(2,'Casa', '9579754'),
(4,'Movil', '9479475');

/* nombre, telefono movil, telefono casa */
SELECT 
  CONCAT(p.first_name,' ',p.last_name) AS Nombre,
  IF(COALESCE(TRIM(h.phone_type),'Casa') = 'Casa', 'N/A', h.phone_nbr) AS Movil,
  IF(COALESCE(TRIM(h.phone_type),'Movil') = 'Movil', 'N/A', h.phone_nbr) AS Casa
FROM Personnel p
LEFT JOIN phones h ON p.emp_id = h.emp_id
ORDER BY Nombre;

SELECT * FROM (SELECT 
  CONCAT(p.first_name,' ',p.last_name) AS Nombre,
  IF(COALESCE(TRIM(h.phone_type),'Casa') = 'Casa', 'N/A', h.phone_nbr) AS Movil,
  IF(COALESCE(TRIM(h.phone_type),'Movil') = 'Movil', 'N/A', h.phone_nbr) AS Casa
FROM Personnel p
LEFT JOIN phones h ON p.emp_id = h.emp_id) AS Subquery;
/* todas las subconsultas necesitan un alias */

/* ejercicio quejas */
CREATE TABLE Quejas
(Qid INTEGER,
 NombrePaciente VARCHAR(50));

CREATE TABLE Defensores
(Qid INTEGER,
 NombreDefensor VARCHAR(50));


CREATE TABLE EstadosDefensores(
EstadoId CHAR(2),
DesEstado VARCHAR(20),
Secuencia TINYINT);

CREATE TABLE HEventosLegales(
Qid   INTEGER,
NombreDefensor VARCHAR(50),
EstadoFinal   CHAR(2),
FechaEvento DATE);

-- Incluir las PK y las FK minimas necesarias...

ALTER TABLE Quejas ADD PRIMARY KEY (Qid);

ALTER TABLE Defensores ADD PRIMARY KEY (Qid,NombreDefensor);

ALTER TABLE EstadosDefensores ADD PRIMARY KEY (EstadoId);


ALTER TABLE HEventosLegales ADD PRIMARY KEY (Qid,nombreDefensor,EstadoFinal);

ALTER TABLE HEventosLegales ADD CONSTRAINT fk_Estados FOREIGN KEY (EstadoFinal) REFERENCES EstadosDefensores(EstadoId);


ALTER TABLE HEventosLegales ADD CONSTRAINT fk_Quejas FOREIGN KEY (Qid) REFERENCES quejas(QId);

-- Insercion de valores

INSERT INTO Quejas VALUES (10, 'Smith');
INSERT INTO Quejas VALUES (20, 'Jones');
INSERT INTO Quejas VALUES (30,'Brown');


INSERT INTO defensores VALUES (10, 'Johnson');
INSERT INTO defensores VALUES (10, 'Meyer');
INSERT INTO defensores VALUES (10, 'Dow');
INSERT INTO defensores VALUES (20, 'Baker');
INSERT INTO defensores VALUES (20, 'Meyer');
INSERT INTO defensores VALUES (30, 'Johnson');

INSERT INTO estadosdefensores VALUES ('AP','Awaiting review panel',1);
INSERT INTO estadosdefensores VALUES ('OR','Panel opinion rendered',2);
INSERT INTO estadosdefensores VALUES ('SF','Suit filed',3);
INSERT INTO estadosdefensores VALUES ('CL','Closed',4);

INSERT INTO HEventosLegales VALUES (10, 'Johnson', 'AP', '1994-01-01');
INSERT INTO HEventosLegales VALUES (10, 'Johnson', 'OR', '1994-02-01');
INSERT INTO HEventosLegales VALUES (10, 'Johnson', 'SF', '1994-03-01');
INSERT INTO HEventosLegales VALUES (10, 'Johnson', 'CL', '1994-04-01');
INSERT INTO HEventosLegales VALUES (10, 'Meyer', 'AP', '1994-01-01');
INSERT INTO HEventosLegales VALUES (10, 'Meyer', 'OR', '1994-02-01');
INSERT INTO HEventosLegales VALUES (10, 'Meyer', 'SF', '1994-03-01');
INSERT INTO HEventosLegales VALUES (10, 'Dow', 'AP', '1994-01-01');
INSERT INTO HEventosLegales VALUES (10, 'Dow', 'OR', '1994-02-01');
INSERT INTO HEventosLegales VALUES (20, 'Meyer', 'AP', '1994-01-01');
INSERT INTO HEventosLegales VALUES (20, 'Meyer', 'OR', '1994-02-01');
INSERT INTO HEventosLegales VALUES (20, 'Baker', 'AP', '1994-01-01');
INSERT INTO HEventosLegales VALUES (30, 'Johnson', 'AP', '1994-01-01');

SELECT * FROM quejas;
SELECT * FROM defensores;
SELECT * FROM EstadosDefensores ORDER BY Secuencia;
SELECT * FROM HEventosLegales ORDER BY NombreDefensor;

/* ID queja, Paciente, Estado queja */ 
/* sacar estado de las quejas en su menor valor */
SELECT SQ2.Qid, quejas.NombrePaciente, SQ2.Estado
FROM quejas
LEFT JOIN (SELECT * FROM (
SELECT h.Qid,
COUNT(*) AS Estado,
h.NombreDefensor
FROM HEventosLegales h
GROUP BY h.Qid,h.NombreDefensor
ORDER BY Estado) AS SQ
GROUP BY SQ.Qid) AS SQ2 ON SQ2.Qid = quejas.Qid;

/* ejercicios variopintos */
CREATE TABLE time_performance_1_dia AS 
SELECT * FROM time_performance_final WHERE FechaVuelo = '2010-01-01';
SELECT * FROM cod_estados;
SELECT * FROM time_performance_1_dia;
ALTER TABLE cod_estados ADD RiesgoRetraso INT;

SELECT 
  t.CodEstadoOrigen AS CodigoEstado, 
  AVG(t.RetrasoTotal) AS Media
FROM time_performance_1_dia t
GROUP BY t.CodEstadoOrigen;

UPDATE ciudades cd LEFT JOIN (SELECT c.ciudadId,COUNT(*) NAlm FROM ciudades c
INNER JOIN direcciones d ON d.ciudadId = c.ciudadId
INNER JOIN almacenes al ON al.direccionId = d.direccionId
GROUP BY c.ciudadId) CalculaAlmacenes ON cd.ciudadId = CalculaAlmacenes.ciudadId
SET cd.NumAlmacenes = COALESCE(CalculaAlmacenes.NAlm,0);

/* catalogar codigo de riesgo en retraso vuelos */
UPDATE cod_estados c 
LEFT JOIN (
SELECT 
  t.CodEstadoOrigen AS CodigoEstado, 
  CASE 
    WHEN AVG(t.RetrasoTotal) BETWEEN 0 AND 10 THEN 0
    WHEN AVG(t.RetrasoTotal) BETWEEN 10 AND 20 THEN 1
    WHEN AVG(t.RetrasoTotal) BETWEEN 20 AND 30 THEN 2
    WHEN AVG(t.RetrasoTotal) BETWEEN 30 AND 40 THEN 3
    WHEN AVG(t.RetrasoTotal) BETWEEN 40 AND 50 THEN 4
    WHEN AVG(t.RetrasoTotal) BETWEEN 50 AND 60 THEN 5
    WHEN AVG(t.RetrasoTotal) > 60 THEN 5
  END AS Media
FROM time_performance_1_dia t
GROUP BY t.CodEstadoOrigen
) AS SQ ON c.codEstado = SQ.CodigoEstado
SET RiesgoRetraso = SQ.Media;

/* actualizar sueldos empleados */
CREATE TABLE emp_sub AS
SELECT * FROM empleados LIMIT 100;
DROP TABLE evaluacion;
CREATE TABLE evaluacion(
EmpleadoId INT,
ResEv CHAR(1));

INSERT INTO evaluacion
SELECT EmpleadoId,
CASE 
  WHEN MOD(EmpleadoId,3) = 0 THEN 'A'
  WHEN MOD(EmpleadoId,3) = 1 THEN 'B'
  WHEN MOD(EmpleadoId,3) = 2 THEN 'C'
END
FROM emp_sub;

/* aumentar salario segun calificacion */
UPDATE emp_sub e1
INNER JOIN (
  SELECT 
  e.EmpleadoId AS ID,
  CASE
    WHEN e.ResEv = 'A' THEN e2.Salario * 1.10
    WHEN e.ResEv = 'B' THEN e2.Salario * 1.05
    WHEN e.ResEv = 'C' THEN e2.Salario * 1
  END AS Aumento
  FROM emp_sub e2
  INNER JOIN evaluacion e ON e.EmpleadoId = e2.EmpleadoId
) AS Sueldaco
SET e1.Salario = Sueldaco.Aumento
WHERE e1.EmpleadoId = Sueldaco.ID;

/* sacar perdidas de productos pagados y no recibidos */
SELECT 
  o.OrdenCompraId,
  o.FechaCompra,
  p.PrecioUnitarioEuros,
  SUM((i.NumeroItemsPedidos - i.NumeroItemRecibidos) * p.PrecioUnitarioEuros) AS Dinero
FROM ordenescompra o
INNER JOIN ordenescompraitems i 
  ON i.OrdenCompraId = i.OrdenCompraId 
 AND i.NumeroItemRecibidos != i.NumeroItemsPedidos
INNER JOIN productosprecio p 
  ON p.ProductoId = i.ProductoId 
 AND ((o.FechaCompra BETWEEN p.FechaInicio AND p.FechaFin) OR p.FechaFin IS NULL)
GROUP BY p.ProductoId;

SELECT * FROM ordenescompra o WHERE o.OrdenCompraId = 85 FOR UPDATE; /* oracle */
START TRANSACTION; /* mysql */
SELECT * FROM ordenescompra o WHERE o.OrdenCompraId = 85;