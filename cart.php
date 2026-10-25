<?php
require_once __DIR__ . '/../../internal/bootstrap.php';
require_once __DIR__ . '/../../internal/deps.php';

$cart = getCartStorage();
$productRepo = getProductRepository();

// Обработка POST-запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = (int)($_POST['product_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    if ($action === 'add' && $productId > 0) {
        $cart->addItem($productId, $quantity);
        header('Location: /pages/shop/cart.php');
        exit;
    } elseif ($action === 'remove' && $productId > 0) {
        $cart->removeItem($productId);
        header('Location: /pages/shop/cart.php');
        exit;
    } elseif ($action === 'update' && $productId > 0) {
        $cart->updateQuantity($productId, $quantity);
        header('Location: /pages/shop/cart.php');
        exit;
    } elseif ($action === 'checkout') {
        // Заглушка для оформления заказа
        $cart->clear();
        header('Location: /pages/shop/order-success.php');
        exit;
    }
}

// Получаем товары в корзине
$cartItems = $cart->getCart();
$productsInCart = [];
$totalPrice = 0;

if (!empty($cartItems)) {
    $allProducts = $productRepo->getAll();
    $productMap = [];
    foreach ($allProducts as $p) {
        $productMap[$p->id] = $p;
    }
    foreach ($cartItems as $pid => $qty) {
        if (isset($productMap[$pid])) {
            $productsInCart[] = ['product' => $productMap[$pid], 'quantity' => $qty];
            $totalPrice += $productMap[$pid]->price * $qty;
        }
    }
}

ob_start();
?>
<h1>Корзина</h1>
<?php if (empty($productsInCart)): ?>
    <p>Ваша корзина пуста. <a href="/pages/shop/index.php">Вернуться к покупкам</a></p>
<?php else: ?>
    <form method="POST" id="cart-form">
        <table class="cart-table">
            <thead>
                <tr><th>Товар</th><th>Цена</th><th>Количество</th><th>Сумма</th><th></th></tr>
            </thead>
            <tbody>
                <?php foreach ($productsInCart as $item):
                    $product = $item['product'];
                    $qty = $item['quantity'];
                    $subtotal = $product->price * $qty;
                ?>
                <tr>
                    <td><a href="/pages/shop/product.php?id=<?= $product->id ?>"><?= htmlspecialchars($product->title) ?></a></td>
                    <td><?= number_format($product->price, 0, '.', ' ') ?> ₽</td>
                    <td><input type="number" name="qty[<?= $product->id ?>]" value="<?= $qty ?>" min="1" class="qty-input" data-id="<?= $product->id ?>"></td>
                    <td><?= number_format($subtotal, 0, '.', ' ') ?> ₽</td>
                    <td><button type="submit" name="action" value="remove" name="product_id" value="<?= $product->id ?>" class="remove-btn">Удалить</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot><tr><td colspan="3"><strong>Итого:</strong></td><td colspan="2"><strong><?= number_format($totalPrice, 0, '.', ' ') ?> ₽</strong></td></tr></tfoot>
        </table>
        <div class="checkout-form">
            <h3>Оформление заказа</h3>
            <input type="text" name="client_name" placeholder="Ваше имя" required>
            <input type="tel" name="client_phone" placeholder="Телефон" required>
            <input type="email" name="client_email" placeholder="Email">
            <button type="submit" name="action" value="checkout" class="btn-checkout">Оформить заказ</button>
        </div>
    </form>
    <script>
        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.dataset.id;
                const quantity = this.value;
                fetch('/pages/shop/cart.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `action=update&product_id=${productId}&quantity=${quantity}`
                }).then(() => location.reload());
            });
        });
    </script>
<?php endif;
$content = ob_get_clean();

$categoryRepo = getCategoryRepository();
$categories = $categoryRepo->getAll();
require_once 'layout.php';
renderShopLayout('Корзина', function() use ($content) { echo $content; }, $categories, null);