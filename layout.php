<?php
function renderShopLayout(string $pageTitle, callable $contentCallback, array $categories, ?int $activeCategoryId = null): void {
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | PC Builder</title>
    <link rel="stylesheet" href="/static/css/shop.css">
    <link rel="stylesheet" href="/static/css/cart.css">
</head>
<body>
    <header class="main-header">
        <div class="container header-flex">
            <a href="/pages/shop/index.php" class="logo">🖥️ PC Builder</a>
            <nav class="main-nav">
                <ul>
                    <li><a href="/pages/shop/index.php" <?= $activeCategoryId === null ? 'class="active"' : '' ?>>Все товары</a></li>
                    <?php foreach ($categories as $cat): ?>
                        <li><a href="/pages/shop/index.php?cat=<?= $cat->id ?>" <?= $activeCategoryId === $cat->id ? 'class="active"' : '' ?>><?= htmlspecialchars($cat->name) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <div class="header-right">
                <a href="/pages/admin/dashboard.php" class="admin-link">🔧 Админка</a>
                <div class="cart-link">
                    <a href="/pages/shop/cart.php">🛒 Корзина <span id="cart-count"><?= (new CartStorage())->getTotalQuantity() ?></span></a>
                </div>
            </div>
        </div>
    </header>
    <main class="container">
        <?php $contentCallback(); ?>
    </main>
    <footer class="main-footer">
        <div class="container">
            <p>© 2025 PC Builder — Собери свой компьютер мечты</p>
        </div>
    </footer>
    <script>
        // Обновление счётчика корзины через fetch (простой вариант)
        function updateCartCount() {
            fetch('/pages/shop/cart-count.php')
                .then(r => r.json())
                .then(data => document.getElementById('cart-count').innerText = data.count)
                .catch(console.error);
        }
    </script>
</body>
</html>
<?php } ?>