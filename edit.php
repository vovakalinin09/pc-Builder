<?php
require_once __DIR__ . '/../../../internal/bootstrap.php';
require_once __DIR__ . '/../../../internal/deps.php';
$productRepo = getProductRepository();
$categoryRepo = getCategoryRepository();
$id = (int)($_GET['id'] ?? 0);
$product = $productRepo->getById($id);
if (!$product) { header('Location: list.php'); exit; }
$categories = $categoryRepo->getAll();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product->title = trim($_POST['title']);
    $product->description = trim($_POST['description']);
    $product->price = (float)$_POST['price'];
    $product->categoryId = $_POST['category_id'] ? (int)$_POST['category_id'] : null;
    $product->type = $_POST['type'];
    $product->imageUrl = $_POST['image_url'] ?: '/static/images/placeholder.jpg';
    if ($product->title && $product->price > 0) {
        if ($productRepo->update($product)) {
            header('Location: list.php');
            exit;
        } else $error = 'Ошибка обновления';
    } else $error = 'Заполните обязательные поля';
}
$content = function() use ($product, $categories, $error) { ?>
    <h2>Редактировать товар</h2>
    <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <label>Название: <input type="text" name="title" value="<?= htmlspecialchars($product->title) ?>" required></label><br>
        <label>Описание: <textarea name="description" required><?= htmlspecialchars($product->description) ?></textarea></label><br>
        <label>Цена: <input type="number" step="0.01" name="price" value="<?= $product->price ?>" required></label><br>
        <label>Категория:
            <select name="category_id">
                <option value="">Без категории</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c->id ?>" <?= $c->id == $product->categoryId ? 'selected' : '' ?>><?= htmlspecialchars($c->name) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <label>Тип:
            <select name="type">
                <option value="component" <?= $product->type == 'component' ? 'selected' : '' ?>>Комплектующее</option>
                <option value="build" <?= $product->type == 'build' ? 'selected' : '' ?>>Готовая сборка</option>
            </select>
        </label><br>
        <label>URL изображения: <input type="text" name="image_url" value="<?= htmlspecialchars($product->imageUrl) ?>"></label><br>
        <button type="submit">Обновить</button>
    </form>
<?php };
require_once '../layout.php';
renderAdminLayout('Редактировать товар', $content, 3);
?>