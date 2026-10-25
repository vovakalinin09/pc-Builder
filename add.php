<?php
require_once __DIR__ . '/../../../internal/bootstrap.php';
require_once __DIR__ . '/../../../internal/deps.php';
$productRepo = getProductRepository();
$categoryRepo = getCategoryRepository();
$categories = $categoryRepo->getAll();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = new Product(
        title: trim($_POST['title']),
        description: trim($_POST['description']),
        price: (float)$_POST['price'],
        categoryId: $_POST['category_id'] ? (int)$_POST['category_id'] : null,
        type: $_POST['type'],
        imageUrl: $_POST['image_url'] ?: '/static/images/placeholder.jpg'
    );
    if ($product->title && $product->price > 0) {
        if ($productRepo->add($product)) {
            header('Location: list.php');
            exit;
        } else $error = 'Ошибка добавления';
    } else $error = 'Заполните обязательные поля';
}
$content = function() use ($categories, $error) { ?>
    <h2>Добавить товар</h2>
    <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <label>Название: <input type="text" name="title" required></label><br>
        <label>Описание: <textarea name="description" required></textarea></label><br>
        <label>Цена: <input type="number" step="0.01" name="price" required></label><br>
        <label>Категория:
            <select name="category_id">
                <option value="">Без категории</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c->id ?>"><?= htmlspecialchars($c->name) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <label>Тип:
            <select name="type">
                <option value="component">Комплектующее</option>
                <option value="build">Готовая сборка</option>
            </select>
        </label><br>
        <label>URL изображения: <input type="text" name="image_url" placeholder="/static/images/placeholder.jpg"></label><br>
        <button type="submit">Сохранить</button>
    </form>
<?php };
require_once '../layout.php';
renderAdminLayout('Добавить товар', $content, 3);
?>