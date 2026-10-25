<?php
require_once __DIR__ . '/db.php';

class Category {
    public function __construct(
        public int $id = 0,
        public string $name = '',
        public string $slug = ''
    ) {}
}

class CategoryRepository {
    public function getAll(): array {
        $conn = openDbConnection();
        $result = $conn->query("SELECT f_id, f_name, f_slug FROM t_category ORDER BY f_name");
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = new Category(
                (int)$row['f_id'],
                $row['f_name'],
                $row['f_slug']
            );
        }
        $conn->close();
        return $categories;
    }

    public function getById(int $id): ?Category {
        $conn = openDbConnection();
        $stmt = $conn->prepare("SELECT f_id, f_name, f_slug FROM t_category WHERE f_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Category((int)$row['f_id'], $row['f_name'], $row['f_slug']);
        }
        return null;
    }

    public function add(string $name, string $slug): bool {
        $conn = openDbConnection();
        $stmt = $conn->prepare("INSERT INTO t_category (f_name, f_slug) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $slug);
        $success = $stmt->execute();
        $conn->close();
        return $success;
    }

    public function update(int $id, string $name, string $slug): bool {
        $conn = openDbConnection();
        $stmt = $conn->prepare("UPDATE t_category SET f_name=?, f_slug=? WHERE f_id=?");
        $stmt->bind_param("ssi", $name, $slug, $id);
        $success = $stmt->execute();
        $conn->close();
        return $success;
    }

    public function delete(int $id): bool {
        $conn = openDbConnection();
        $stmt = $conn->prepare("DELETE FROM t_category WHERE f_id=?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $conn->close();
        return $success;
    }
}