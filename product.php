<?php
require_once __DIR__ . '/../../internal/bootstrap.php';
require_once __DIR__ . '/../../internal/deps.php';
$productRepo = getProductRepository();
$categoryRepo = getCategoryRepository();
$id = (int)($_GET['id'] ?? 0);
$product = $productRepo->getById($id);
if (!$product) {
    header('HTTP/1.0 404 Not Found');
    echo 'Товар не найден';
    exit;
}
$categories = $categoryRepo->getAll();
ob_start();
?>
<div class="product-detail">
    <img src="<?= htmlspecialchars($product->imageUrl) ?>" alt="<?= htmlspecialchars($product->title) ?>" style="max-width: 400px;">
    <h1><?= htmlspecialchars($product->title) ?></h1>
    <p><?= nl2br(htmlspecialchars($product->description)) ?></p>
    <div class="price"><?= number_format($product->price, 0, '.', ' ') ?> ₽</div>
    <form action="/pages/shop/cart.php" method="POST">
        <input type="hidden" name="product_id" value="<?= $product->id ?>">
        <input type="hidden" name="action" value="add">
        <button type="submit" class="btn-buy">В корзину</button>
    </form>
    <p><a href="/pages/shop/index.php">← Назад в каталог</a></p>
</div>
<?php
$content = ob_get_clean();
require_once 'layout.php';
renderShopLayout($product->title, function() use ($content) { echo $content; }, $categories);
?>