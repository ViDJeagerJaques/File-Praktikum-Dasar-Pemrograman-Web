SELECT * FROM `2products` WHERE status = 'Aktif';

SELECT * FROM `2products` WHERE harga_jual > 100000;

SELECT * FROM `2products` WHERE stok_awal < 10;

SELECT * FROM `2products` WHERE harga_jual BETWEEN 100000 AND 500000;

SELECT * FROM `2products` WHERE nama_barang LIKE '%Mouse%';

SELECT * FROM `5customers` WHERE nama_lengkap LIKE 'A%';

SELECT * FROM `2products` ORDER BY harga_jual ASC;

SELECT * FROM `2products` ORDER BY stok_awal DESC LIMIT 5;

SELECT
  o.order_number,
  c.nama_lengkap AS customer,
  o.order_date,
  p.amount,
  p.status
FROM `11orders` o
JOIN `5customers` c ON c.id = o.customer_id
JOIN `13payments` p ON p.order_id = o.id
WHERE p.status = 'Lunas';

SELECT
  o.order_number,
  c.nama_lengkap AS customer,
  o.order_date,
  o.total_amount,
  o.status
FROM `11orders` o
JOIN `5customers` c ON c.id = o.customer_id
WHERE o.order_date BETWEEN '2026-11-17 00:00:00' AND '2026-11-17 23:59:59';

SELECT
  o.order_number,
  COUNT(oi.id) AS jumlah_item
FROM `11orders` o
LEFT JOIN `12order_items` oi ON oi.order_id = o.id
GROUP BY o.order_number;

SELECT
  o.order_number,
  SUM(oi.qty * oi.unit_price - oi.discount) AS grand_total
FROM `11orders` o
JOIN `12order_items` oi ON oi.order_id = o.id
GROUP BY o.order_number;

SELECT COUNT(*) AS jumlah_produk_aktif FROM `2products` WHERE status = 'Aktif';

SELECT AVG(harga_jual) AS rata_rata_harga FROM `2products`;

SELECT MIN(harga_jual) AS harga_termurah, MAX(harga_jual) AS harga_termahal FROM `2products`;


SELECT
  o.order_number,
  c.nama_lengkap AS customer,
  COUNT(oi.id) AS jumlah_item
FROM `11orders` o
JOIN `5customers` c ON c.id = o.customer_id
LEFT JOIN `12order_items` oi ON oi.order_id = o.id
GROUP BY o.order_number, c.nama_lengkap;

SELECT
  o.order_number,
  c.nama_lengkap AS customer,
  SUM(oi.qty * oi.unit_price - oi.discount) AS grand_total
FROM `11orders` o
JOIN `5customers` c ON c.id = o.customer_id
JOIN `12order_items` oi ON oi.order_id = o.id
GROUP BY o.order_number, c.nama_lengkap;