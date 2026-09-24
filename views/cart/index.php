<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Tu carrito</h1>

<?php if (empty($items)): ?>
    <p>Tu carrito está vacío. <a href="<?= BASE_URL ?>/">Ver catálogo</a></p>
<?php else: ?>
    <table class="cart-table">
        <thead>
            <tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td>
                    <div class="cart-item">
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="">
                        <?= htmlspecialchars($item['name']) ?>
                    </div>
                </td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td>
                    <form action="<?= BASE_URL ?>/carrito/actualizar" method="post" class="qty-form">
                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                        <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" max="<?= $item['stock'] ?>" onchange="this.form.submit()">
                    </form>
                </td>
                <td>$<?= number_format($item['subtotal'], 2) ?></td>
                <td>
                    <form action="<?= BASE_URL ?>/carrito/eliminar" method="post">
                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                        <button type="submit" class="btn-link">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="cart-summary">
        <h3>Total: $<?= number_format($total, 2) ?></h3>
        <a href="<?= BASE_URL ?>/checkout" class="btn btn-primary">Proceder al pago</a>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
