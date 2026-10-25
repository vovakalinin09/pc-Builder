<?php
require_once __DIR__ . '/../../internal/bootstrap.php';
require_once __DIR__ . '/../../internal/deps.php';

$categoryRepo = getCategoryRepository();
$productRepo = getProductRepository();
$categories = $categoryRepo->getAll();
$activeCatId = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
$typeFilter = isset($_GET['type']) ? $_GET['type'] : null;
$products = $productRepo->getAll($activeCatId, $typeFilter);

ob_start();
?>
<div class="catalog-header">
    <h1>Каталог комплектующих и сборок</h1>
    <div class="filters">
        <a href="?<?= $activeCatId ? "cat=$activeCatId&" : "" ?>type=component" class="btn-filter">Комплектующие</a>
        <a href="?<?= $activeCatId ? "cat=$activeCatId&" : "" ?>type=build" class="btn-filter">Готовые сборки</a>
        <a href="?<?= $activeCatId ? "cat=$activeCatId" : "" ?>" class="btn-filter active">Все</a>
    </div>
</div>
<div class="products-grid">
    <?php foreach ($products as $product): ?>
    <div class="product-card">
        <img src="<?= htmlspecialchars($product->imageUrl) ?>" alt="<?= htmlspecialchars($product->title) ?>" class="product-img">
        <div class="product-info">
            <h3><a href="/pages/shop/product.php?id=<?= $product->id ?>"><?= htmlspecialchars($product->title) ?></a></h3>
            <p class="product-desc"><?= htmlspecialchars(mb_substr($product->description, 0, 80)) ?>...</p>
            <div class="product-footer">
                <span class="price"><?= number_format($product->price, 0, '.', ' ') ?> ₽</span>
                <form action="/pages/shop/cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="btn-buy">В корзину</button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php
$content = ob_get_clean();
require_once 'layout.php';
renderShopLayout('Каталог', function() use ($content) { echo $content; }, $categories, $activeCatId);