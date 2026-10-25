-- Таблица категорий
CREATE TABLE IF NOT EXISTS t_category (
    f_id INT PRIMARY KEY AUTO_INCREMENT,
    f_name VARCHAR(100) NOT NULL UNIQUE,
    f_slug VARCHAR(100) NOT NULL UNIQUE,
    f_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;;

-- Таблица товаров (комплектующие и сборки)
CREATE TABLE IF NOT EXISTS t_product (
    f_id INT PRIMARY KEY AUTO_INCREMENT,
    f_title VARCHAR(255) NOT NULL,
    f_description TEXT NOT NULL,
    f_price DECIMAL(12,2) NOT NULL,
    f_category_id INT,
    f_type ENUM('component', 'build') DEFAULT 'component',
    f_image_url VARCHAR(255) DEFAULT '/static/images/placeholder.jpg',
    f_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (f_category_id) REFERENCES t_category(f_id) ON DELETE SET NULL
)DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица заказов
CREATE TABLE IF NOT EXISTS t_order (
    f_id INT PRIMARY KEY AUTO_INCREMENT,
    f_client_name VARCHAR(100) NOT NULL,
    f_client_phone VARCHAR(20) NOT NULL,
    f_client_email VARCHAR(100),
    f_created_stamp BIGINT NOT NULL,
    f_status ENUM('new', 'processing', 'completed', 'cancelled') DEFAULT 'new'
)DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица позиций заказа
CREATE TABLE IF NOT EXISTS t_order_item (
    f_order_id INT NOT NULL,
    f_product_id INT NOT NULL,
    f_quantity INT NOT NULL DEFAULT 1,
    f_price_at_time DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (f_order_id, f_product_id),
    FOREIGN KEY (f_order_id) REFERENCES t_order(f_id) ON DELETE CASCADE,
    FOREIGN KEY (f_product_id) REFERENCES t_product(f_id) ON DELETE RESTRICT
)DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Индексы
CREATE INDEX idx_product_category ON t_product(f_category_id);
CREATE INDEX idx_order_stamp ON t_order(f_created_stamp);