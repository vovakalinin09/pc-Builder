<?php
require_once __DIR__ . '/../../internal/bootstrap.php';
require_once __DIR__ . '/../../internal/deps.php';
$categoryRepo = getCategoryRepository();
$productRepo = getProductRepository();
$categoriesCount = count($categoryRepo->getAll());
$productsCount = count($productRepo->getAll());
$content = function() use ($categoriesCount, $productsCount) { ?>
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Категории</h3>
            <p class="stat-number"><?= $categoriesCount ?></p>
        </div>
        <div class="stat-card">
            <h3>Товары</h3>
            <p class="stat-number"><?= $productsCount ?></p>
        </div>
        <div class="stat-card">
            <h3>Заказы (сегодня)</h3>
            <p class="stat-number">—</p>
        </div>
    </div>
<?php };
require_once 'layout.php';
renderAdminLayout('Панель управления', $content, 1);
?>