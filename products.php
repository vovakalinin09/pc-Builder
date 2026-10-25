<?php
require_once __DIR__ . '/db.php';

class Product {
    public function __construct(
        public int $id = 0,
        public string $title = '',
        public string $description = '',
        public float $price = 0,
        public ?int $categoryId = null,
        public string $type = 'component',
        public string $imageUrl = '/static/images/placeholder.jpg'
    ) {}
}

class ProductRepository {
    public function getAll(?int $categoryId = null, ?string $type = null): array {
        $conn = openDbConnection();
        $sql = "SELECT f_id, f_title, f_description, f_price, f_category_id, f_type, f_image_url FROM t_product WHERE 1=1";
        $params = [];
        $types = "";
        if ($categoryId !== null) {
            $sql .= " AND f_category_id = ?";
            $params[] = $categoryId;
            $types .= "i";
        }
        if ($type !== null) {
            $sql .= " AND f_type = ?";
            $params[] = $type;
            $types .= "s";
        }
        $sql .= " ORDER BY f_price ASC";
        $stmt = $conn->prepare($sql);
        if (!empty($params)) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = new Product(
                (int)$row['f_id'],
                $row['f_title'],
                $row['f_description'],
                (float)$row['f_price'],
                $row['f_category_id'] ? (int)$row['f_category_id'] : null,
                $row['f_type'],
                $row['f_image_url']
            );
        }
        $conn->close();
        return $products;
    }

    public function getById(int $id): ?Product {
        $conn = openDbConnection();
        $stmt = $conn->prepare("SELECT f_id, f_title, f_description, f_price, f_category_id, f_type, f_image_url FROM t_product WHERE f_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Product(
                (int)$row['f_id'],
                $row['f_title'],
                $row['f_description'],
                (float)$row['f_price'],
                $row['f_category_id'] ? (int)$row['f_category_id'] : null,
                $row['f_type'],
                $row['f_image_url']
            );
        }
        return null;
    }

    public function add(Product $product): bool {
        $conn = openDbConnection();
        $stmt = $conn->prepare("INSERT INTO t_product (f_title, f_description, f_price, f_category_id, f_type, f_image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiss", $product->title, $product->description, $product->price, $product->categoryId, $product->type, $product->imageUrl);
        $success = $stmt->execute();
        $conn->close();
        return $success;
    }

    public function update(Product $product): bool {
        $conn = openDbConnection();
        $stmt = $conn->prepare("UPDATE t_product SET f_title=?, f_description=?, f_price=?, f_category_id=?, f_type=?, f_image_url=? WHERE f_id=?");
        $stmt->bind_param("ssdissi", $product->title, $product->description, $product->price, $product->categoryId, $product->type, $product->imageUrl, $product->id);
        $success = $stmt->execute();
        $conn->close();
        return $success;
    }

    public function delete(int $id): bool {
        $conn = openDbConnection();
        $stmt = $conn->prepare("DELETE FROM t_product WHERE f_id=?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $conn->close();
        return $success;
    }
}