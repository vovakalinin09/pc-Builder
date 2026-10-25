<?php
require_once __DIR__ . '/../../../internal/bootstrap.php';
require_once __DIR__ . '/../../../internal/deps.php';
$productRepo = getProductRepository();
$categoryRepo = getCategoryRepository();
$products = $productRepo->getAll();
$categories = [];
foreach ($categoryRepo->getAll() as $c) $categories[$c->id] = $c->name;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $productRepo->delete((int)$_POST['id']);
    header('Location: list.php');
    exit;
}
$content = function() use ($products, $categories) { ?>
    <h2>Товары</h2>
    <a href="add.php" class="btn-add">➕ Добавить товар</a>
    <table class="admin-table">
        <thead><tr><th>ID</th><th>Название</th><th>Цена</th><th>Категория</th><th>Тип</th><th>Действия</th></tr></thead>
        <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p->id ?></td>
            <td><?= htmlspecialchars($p->title) ?></td>
            <td><?= number_format($p->price, 0, '.', ' ') ?> ₽</td>
            <td><?= isset($categories[$p->categoryId]) ? htmlspecialchars($categories[$p->categoryId]) : '—' ?></td>
            <td><?= $p->type === 'component' ? 'Комплектующее' : 'Сборка' ?></td>
            <td>
                <a href="edit.php?id=<?= $p->id ?>">✏️</a>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Удалить товар?')">
                    <input type="hidden" name="id" value="<?= $p->id ?>">
                    <button type="submit" name="delete" class="btn-link">🗑️</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php };
require_once '../layout.php';
renderAdminLayout('Товары', $content, 3);
?>