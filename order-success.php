<?php
require_once __DIR__ . '/../../internal/bootstrap.php';
require_once __DIR__ . '/../../internal/deps.php';
$orderId = (int)($_GET['id'] ?? 0);
$categories = getCategoryRepository()->getAll();
ob_start();
?>
<h1>Заказ успешно оформлен!</h1>
<p>Номер вашего заказа: <strong>#<?= $orderId ?></strong></p>
<p>Наш менеджер свяжется с вами в ближайшее время.</p>
<p><a href="/pages/shop/index.php">Продолжить покупки</a></p>
<?php
$content = ob_get_clean();
require_once 'layout.php';
renderShopLayout('Заказ оформлен', function() use ($content) { echo $content; }, $categories);
?>