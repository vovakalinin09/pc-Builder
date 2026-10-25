SET NAMES utf8mb4;
-- Категории
INSERT INTO t_category (f_id, f_name, f_slug) VALUES
(1, 'Процессоры', 'cpu'),
(2, 'Видеокарты', 'gpu'),
(3, 'Материнские платы', 'motherboard'),
(4, 'Оперативная память', 'ram'),
(5, 'SSD накопители', 'ssd'),
(6, 'Блоки питания', 'psu'),
(7, 'Корпуса', 'case'),
(8, 'Готовые сборки', 'prebuilt');

-- Комплектующие (бюджетный, средний, дорогой сегменты)
INSERT INTO t_product (f_title, f_description, f_price, f_category_id, f_type) VALUES

-- Процессоры
('AMD Athlon 3000G', '2 ядра / 4 потока, для офисных задач', 3450.00, 1, 'component'),
('Intel Pentium Gold G6405', '2 ядра / 4 потока, 4.1 ГГц', 3750.00, 1, 'component'),
('AMD Ryzen 3 4100', '4 ядра / 8 потоков, отличный старт', 6200.00, 1, 'component'),
('Intel Core i3-12100F', '4 ядра / 8 потоков, 12-е поколение', 7590.00, 1, 'component'),
('AMD Ryzen 5 5600', '6 ядер / 12 потоков, лучший для игр', 12400.00, 1, 'component'),
('Intel Core i5-12400F', '6 ядер / 12 потоков, высокая частота', 12800.00, 1, 'component'),
('AMD Ryzen 7 5800X', '8 ядер / 16 потоков, топ AM4', 19500.00, 1, 'component'),
('Intel Core i7-12700K', '12 ядер (8+4), разгон', 29900.00, 1, 'component'),
('AMD Ryzen 9 7950X', '16 ядер / 32 потока, DDR5', 56900.00, 1, 'component'),

-- Видеокарты
('GeForce GT 1030', '2GB GDDR5, для офиса', 7950.00, 2, 'component'),
('Radeon RX 550', '4GB, 128-bit, бюджетная', 8450.00, 2, 'component'),
('GeForce GTX 1650', '4GB GDDR6, без доп.питания', 13900.00, 2, 'component'),
('Radeon RX 6600', '8GB, отличный FPS в Full HD', 26900.00, 2, 'component'),
('GeForce RTX 3060', '12GB, DLSS, трассировка', 32400.00, 2, 'component'),
('Radeon RX 6800 XT', '16GB, конкурент RTX 3080', 54900.00, 2, 'component'),
('GeForce RTX 4070 Ti', '12GB GDDR6X, высокая производительность', 84900.00, 2, 'component'),
('GeForce RTX 4090', '24GB, лучшая для 4K', 169900.00, 2, 'component'),

-- Остальные категории упрощённо (по 3-4 товара)
('MSI B450M-A PRO MAX', 'mATX, AM4, хороший выбор', 4990.00, 3, 'component'),
('Gigabyte B660M DS3H', 'LGA1700, DDR4, PCIe 4.0', 8890.00, 3, 'component'),
('ASUS ROG STRIX Z790-E', 'LGA1700, DDR5, топ для i9', 28500.00, 3, 'component'),

('Kingston 8GB DDR4 2666', 'Бюджетная плашка', 2250.00, 4, 'component'),
('Corsair 16GB (2x8) DDR4 3200', 'Двухканальный набор', 4750.00, 4, 'component'),
('G.Skill 32GB (2x16) DDR5 6000', 'RGB, высокая частота', 12990.00, 4, 'component'),

('Kingston 240GB SATA SSD', 'Бюджетный объём', 2350.00, 5, 'component'),
('WD Blue 1TB M.2 NVMe', 'Скорость 2400 МБ/с', 5290.00, 5, 'component'),
('Samsung 980 Pro 2TB', 'PCIe 4.0, 7000 МБ/с', 18490.00, 5, 'component'),

('AeroCool VX 500W', '80+ Bronze, базовый', 2590.00, 6, 'component'),
('Corsair CX650F 650W', '80+ Bronze, модульный', 5290.00, 6, 'component'),
('Seasonic Focus 850W Gold', '80+ Gold, полностью модульный', 11990.00, 6, 'component'),

('AeroCool Cylon', 'MIDI-Tower, акриловое окно', 2890.00, 7, 'component'),
('Fractal Design Meshify C', 'Отличная вентиляция, компактный', 6890.00, 7, 'component'),
('Lian Li O11 Dynamic XL', 'Для водяного охлаждения', 15990.00, 7, 'component'),

-- Готовые сборки (бюджетная, средняя, дорогая)
('Сборка "Эконом"', 
 'Процессор AMD Athlon 3000G, 8GB DDR4, SSD 240GB, блок 500W, корпус AeroCool Cylon', 
 14490.00, 8, 'build'),

('Сборка "Стандарт"', 
 'Ryzen 5 5600, GeForce RTX 3060 12GB, 16GB DDR4, SSD 1TB NVMe, блок 650W', 
 68900.00, 8, 'build'),

('Сборка "Флагман"', 
 'Ryzen 9 7950X, GeForce RTX 4090, 32GB DDR5, 2TB NVMe PCIe 4.0, блок 850W Gold', 
 279900.00, 8, 'build');