<?php
require_once __DIR__ . '/categories.php';
require_once __DIR__ . '/products.php';
require_once __DIR__ . '/cart.php';
require_once __DIR__ . '/orders.php';

function getCategoryRepository(): CategoryRepository {
    return new CategoryRepository();
}

function getProductRepository(): ProductRepository {
    return new ProductRepository();
}

function getCartStorage(): CartStorage {
    return new CartStorage();
}

function getOrderRepository(): OrderRepository {
    return new OrderRepository();
}