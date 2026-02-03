INSERT INTO users (username, password, full_name, role) VALUES
('admin', '$2y$10$8OWJGqi3gGULmPaXIX58BuyrYxqAhwy4tKJUpYnueWI37PV/txS7m', 'Administrador', 'admin'),
('chofer', '$2y$10$8OWJGqi3gGULmPaXIX58BuyrYxqAhwy4tKJUpYnueWI37PV/txS7m', 'Chofer Principal', 'driver');

INSERT INTO manifests (qr_code, client_name, address, expected_items) VALUES
('M-001', 'Cliente A', 'Calle Falsa 123', '5 Cajas'),
('M-002', 'Cliente B', 'Av Siempre Viva 742', '2 Contenedores');
