<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="product-detail">
    <div class="product-image">
        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>
    <div class="product-info">
        <span class="brand"><?= htmlspecialchars($product['brand_name'] ?? '') ?></span>
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <p class="price">$<?= number_format($product['price'], 2) ?></p>
        <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <?php if ($product['stock'] > 0): ?>
            <form action="<?= BASE_URL ?>/carrito/agregar" method="post" class="add-to-cart-form">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <label>Cantidad:
                    <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?>">
                </label>
                <button type="submit" class="btn btn-primary">Agregar al carrito</button>
            </form>
            <p class="stock-info"><?= $product['stock'] ?> unidades disponibles</p>
        <?php else: ?>
            <p class="badge out">Producto agotado</p>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>/" class="back-link">&larr; Volver al catálogo</a>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
