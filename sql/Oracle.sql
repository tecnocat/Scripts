/* funciones de fechas */
create table pruebafechas (
fecha date,
sellotemporal timestamp,
sellotemporaltz timestamp with time zone
);
insert into pruebafechas values (sysdate, systimestamp, systimestamp);
select * from pruebafechas;
select sysdate - to_date('2000-02-02', 'YYYY-MM-DD') from dual; /* tabla de Oracle */
select sysdate + 101 from dual;
select sysdate + interval '1-00' year to month from dual;

/* configuracion independiente por cada usuario */
select * from database_properties;
select to_char(to_date('01-FEB-50','DD-MON-RR'), 'DD-MM-YYYY') from dual;
select to_char(sysdate, 'Month') from dual;

/* cambio de idioma de fechas, dias, etc... */
alter session set nls_date_language = 'French';
select to_char(sysdate, 'Month') from dual;
select to_char(sysdate, 'DayDD", de "Month"de "YYYY') from dual;

/* diferencia entre motor (en MySQL MyISAM o InnoDB) */
select count(*) from HR.TIME_PERFORMANCE_FINAL;

/* creacion de intervalos (en MySQL auto_increment) */
create table nombres (
nombreid int primary key,
nombre varchar2(10)
);
insert into nombres (nombreid,nombre) values (2, 'paco');
create sequence secuencia
start with 1
increment by 1
minvalue 1
maxvalue 5;
insert into nombres values (secuencia.nextval, 'paco');
select * from nombres;

/* tablas temporales con datos no visibles entre sessiones */
create global temporary table T (
x varchar2(10),
Fecha date
) on commit preserve rows;
insert into T values('Tunante', sysdate);
select * from T;
truncate table hr.T;
commit;
drop table T;

/* selects con alias */
select  tpf.numvuelo "vuelo", tpf.origen "origen"
from time_perfomance_final tpf;

/* concatenar */
select 'Jose' || ' Carlos' from dual;

/* condiciones varias */
select first_name,
decode(
  substr(first_name,1,1),
  'P', 'Es una P', 'No es una P',
  'R', 'Es una R', 'No es una R',
  'Ni P ni R'
) from employees;
select nvl(to_char(department_id), 'Sin departamento') from employees;

/* fechas */
select * from employees where hire_date < to_date('01-01-1998', 'DD-MM-YYYY');
select * from employees where sysdate - hire_date > 7000;

/* ordenacion */
select department_id from employees
order by department_id nulls first;

/* pruebas varias */
create index f_name_ix on employees(first_name);
explain plan for /* delante explica que ejecutara dbms */

/* ejercicios */
/* 4 */
select 
  e.first_name "Nombre",
  e.salary "Salario"
from employees e 
where e.first_name like upper('J%')
and e.salary between 5000 and 80000;
/* 4 bis */
select e.first_name || ' ' || e.last_name "Nombre"
from employees e;

select e.employee_id, d.department_id 
from employees e, departments d
where e.department_id = d.department_id(+)
and d.department_id is null;

select e.first_name || ' ' || e.last_name "Nombre",
e.hire_date "Fichado en",
nvl(b.first_name, 'SIN RESPONSABLE DIRECTO') "Boss",
nvl(d.department_name, 'SIN DEPARTAMENTO ASIGNADO') "Departamento"
from employees e
left join employees b on b.manager_id = e.employee_id
left join departments d on d.department_id = e.department_id;

create table time_performance_1_dia as
select * from time_performance_final where FechaVuelo = to_date('2010-01-01', 'YYYY-MM-DD');

/* agrega el conteo final en null */
select job_id, count(*)
from employees
group by rollup(job_id);

/* retrasos de vuelos agrupados con total por aerolinea y estado */
select row_number() over(order by d.department_name) orden,
d.department_name "Departamento", sum(e.salary) "Sueldo",
grouping(d.department_name) "Grouping"
from employees e
left join departments d on e.department_id = d.department_id
group by cube(d.department_name)
order by d.department_name;

/* grupos de sueldos por departamentos y totales */
select row_number() over(order by la.nombreaerolinea,ce.nombreestado) as orden,
ce.nombreestado as Estado,
la.nombreaerolinea as Aerolinea,
count(*) as Total,
sum(tp.retrasototal) as Retrasos,
avg(tp.retrasototal) as Media
from time_performance_1_dia tp
inner join lineas_aereas la on la.aerolineaid = tp.aerolineaid
inner join cod_estados ce on ce.codestado = tp.codestadoorigen
where tp.retraso15 > 0
group by cube(la.nombreaerolinea,ce.nombreestado);

select (
  select max(em.salary) 
  from employees em
) - e.salary as Diferencia,
e.salary as Salario,
e.first_name || ' ' || e.last_name as Pinche
from employees e;

/* diferencia salario max department y empleados department */
select 
  max(e.salary) as Maximo
from employees e 
group by e.department_id;

declare
  fucking varchar2(100) default 'yeah yeah';
  -- morefucking tabla.campo%TYPE; <- asigna el tipo automaticamente
begin
  select first_name into fucking from employees where employee_id = 100;
  dbms_output.put_line(fucking);
end;

-- Ejemplo funcion

create or replace function CalcularNuevoSalario(
  SalarioAct employees.salary%type, 
  PorcentajeSubida number)
return employees.salary%type is
vSalarioNew employees.salary%type;
begin
  vSalarioNew := SalarioAct * (1 + PorcentajeSubida);
  return vSalarioNew;
end;

-- ¿Como podemos utilizar una funcion?

create table SubidaSueldos (
EmpId number(10,0),
Subida number(3,2));
insert into SubidaSueldos
select employee_id, round(dbms_random.value(0,1),2) from employees;
select * from subidasueldos;

select ss.EmpId,ss.Subida,e.salary from subidasueldos ss inner join employees e  
on ss.empId = e.employee_id;

begin
  for c in ( 
    select ss.EmpId,ss.Subida,e.salary 
    from subidasueldos ss 
    inner join employees e  on ss.empId = e.employee_id
  ) 
  loop
    update employees set salary = CalcularNuevoSalario(c.salary,c.subida) 
    where employee_id = c.empId;
  end loop;
  commit;
end;

select * from employees;
  
  
create or replace function porcentaje(
  salario employees.salary%type, 
  subida number) return employees.salary%type is sueldo employees.salary%type;
begin
  sueldo := salario * (1 + subida);
  return sueldo;
end;

select e.first_name,porcentaje(e.salary, 10) from employees e 
where employee_id = 100;

select sum(e.salary,function()) from employees e where employee_id = 100;

create or replace type empleado as object(
  eid number(10,0),
  nombre varchar2(100),
  salario number(10,0)
);

create or replace type empleados as table of empleado;

create or replace function FuncionEmpleados return empleados is tabla empleados;

begin
  select empleado(employee_id,first_name,salary) bulk collect into tabla from employees;
  return tabla;
end;

select * from table(FuncionEmpleados);