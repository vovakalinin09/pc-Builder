<?php
require_once __DIR__ . '/../internal/cart.php';
$cart = new CartStorage();
header('Content-Type: application/json');
echo json_encode(['count' => $cart->getTotalQuantity()]);