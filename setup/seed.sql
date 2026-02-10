INSERT INTO users (role, full_name, email, password_hash) VALUES
('admin', 'Nettedersin Admin', 'admin@nettedersin.com', '$2y$10$examplehash'),
('teacher', 'Ayşe Öğretmen', 'ayse@nettedersin.com', '$2y$10$examplehash'),
('student', 'Mehmet Öğrenci', 'mehmet@nettedersin.com', '$2y$10$examplehash');

INSERT INTO packages (name, billing_type, price, trial_days) VALUES
('Starter', 'monthly', 299.00, 7),
('Pro', 'monthly', 599.00, 7),
('Elite', 'monthly', 999.00, 14);
