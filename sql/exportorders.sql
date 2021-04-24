-- Se entiende la SQL, para que explicarlo ¿no?
SELECT
  CASE
    WHEN SUBDATE(CURRENT_TIMESTAMP,INTERVAL 24 HOUR) > o.`date_add`
    THEN 'Antiguo' ELSE 'Nuevo'
  END AS 'estado',
  o.`id_order`,
  c.`id_cliente_entidad`,
  CONCAT(c.firstname," ",c.lastname) AS customer,
  c.`id_entidad`,
  c.`id_oficina`,
  o.`date_add`,
  o.`date_upd`,
  p.`product_name`,
  p.`product_quantity`,
  '|',
  o.*
FROM `orders` o
LEFT JOIN `customer` c ON c.`id_customer` = o.`id_customer`
LEFT JOIN `order_history` h ON h.`id_order` = o.`id_order`
LEFT JOIN `order_detail` p ON p.`id_order` = o.`id_order`
WHERE o.`id_order` = 27
  AND h.`id_order_state` = 12;