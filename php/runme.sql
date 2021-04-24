-- Use database
USE c1gnfetc;

-- Truncate the affected rows
DELETE FROM `etc_pm_home_energy` WHERE `house_bridge_id` = 3; -- Prueba02

-- Insert the new ones
