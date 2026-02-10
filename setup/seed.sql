INSERT INTO plans (name, price, duration_days, created_at) VALUES
('Starter', 299.00, 30, NOW()),
('Pro', 599.00, 30, NOW()),
('Elite', 999.00, 30, NOW())
ON DUPLICATE KEY UPDATE price = VALUES(price);
