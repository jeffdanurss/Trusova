<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="catalog-layout">
    <aside class="filters">
        <h3>Categorías</h3>
        <ul>
            <li><a href="<?= BASE_URL ?>/">Todas</a></li>
            <?php foreach ($categories as $cat): ?>
                <li>
                    <a href="<?= BASE_URL ?>/?category=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <h3>Marcas</h3>
        <ul>
            <?php foreach ($brands as $brand): ?>
                <li><a href="<?= BASE_URL ?>/?brand=<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <section class="product-grid">
        <?php if (empty($products)): ?>
            <p>No se encontraron relojes con esos filtros.</p>
        <?php endif; ?>

        <?php foreach ($products as $p): ?>
            <a class="product-card" href="<?= BASE_URL ?>/producto/<?= $p['slug'] ?>">
                <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                <div class="product-card-body">
                    <span class="brand"><?= htmlspecialchars($p['brand_name'] ?? '') ?></span>
                    <h4><?= htmlspecialchars($p['name']) ?></h4>
                    <p class="price">$<?= number_format($p['price'], 2) ?></p>
                    <?php if ($p['stock'] <= 0): ?>
                        <span class="badge out">Agotado</span>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </section>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
