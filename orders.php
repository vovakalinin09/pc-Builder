<?php
require_once __DIR__ . '/db.php';

class OrderRepository {
    /**
     * Создаёт заказ.
     * @param string $name Имя клиента
     * @param string $phone Телефон
     * @param string|null $email Email
     * @param array $items Ассоциативный массив [product_id => quantity]
     * @param array $prices Массив цен товаров на момент покупки [product_id => price]
     * @return int|false ID заказа или false при ошибке
     */
    public function createOrder(string $name, string $phone, ?string $email, array $items, array $prices) {
        $conn = openDbConnection();
        $conn->begin_transaction();
        try {
            $timestamp = time();
            $stmt = $conn->prepare("INSERT INTO t_order (f_client_name, f_client_phone, f_client_email, f_created_stamp) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $name, $phone, $email, $timestamp);
            $stmt->execute();
            $orderId = $conn->insert_id;

            $stmtItem = $conn->prepare("INSERT INTO t_order_item (f_order_id, f_product_id, f_quantity, f_price_at_time) VALUES (?, ?, ?, ?)");
            foreach ($items as $productId => $quantity) {
                $price = $prices[$productId] ?? 0;
                $stmtItem->bind_param("iiid", $orderId, $productId, $quantity, $price);
                $stmtItem->execute();
            }
            $conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Order creation failed: " . $e->getMessage());
            return false;
        } finally {
            $conn->close();
        }
    }
    public function getAllOrders(): array {
        $conn = openDbConnection();
        $sql = "SELECT o.f_id, o.f_client_name, o.f_client_phone, o.f_client_email, 
                    o.f_created_stamp, o.f_status,
                    GROUP_CONCAT(CONCAT(p.f_title, ' (x', oi.f_quantity, ')') SEPARATOR ', ') as items,
                    SUM(oi.f_quantity * oi.f_price_at_time) as total
                FROM t_order o
                LEFT JOIN t_order_item oi ON o.f_id = oi.f_order_id
                LEFT JOIN t_product p ON oi.f_product_id = p.f_id
                GROUP BY o.f_id
                ORDER BY o.f_created_stamp DESC";
        $result = $conn->query($sql);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = [
                'id' => $row['f_id'],
                'name' => $row['f_client_name'],
                'phone' => $row['f_client_phone'],
                'email' => $row['f_client_email'],
                'date' => date('d.m.Y H:i', $row['f_created_stamp']),
                'status' => $row['f_status'],
                'items' => $row['items'],
                'total' => $row['total']
            ];
        }
        $conn->close();
        return $orders;
    }

    // Метод для обновления статуса заказа (опционально)
    public function updateStatus(int $orderId, string $status): bool {
        $allowed = ['new', 'processing', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) return false;
        $conn = openDbConnection();
        $stmt = $conn->prepare("UPDATE t_order SET f_status = ? WHERE f_id = ?");
        $stmt->bind_param("si", $status, $orderId);
        $success = $stmt->execute();
        $conn->close();
        return $success;
    }
    public function deleteOrder(int $orderId): bool {
        $conn = openDbConnection();
        // Сначала удалим связанные позиции (CASCADE уже есть, но для надёжности)
        $stmt = $conn->prepare("DELETE FROM t_order WHERE f_id = ?");
        $stmt->bind_param("i", $orderId);
        $success = $stmt->execute();
        $conn->close();
        return $success;
    }
}