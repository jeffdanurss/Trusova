<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="confirmation">
    <h1>¡Gracias por tu compra!</h1>
    <p>Tu pedido <strong>#<?= $order['id'] ?></strong> fue registrado con estado <strong><?= htmlspecialchars($order['status']) ?></strong>.</p>

    <table class="cart-table">
        <thead><tr><th>Producto</th><th>Precio</th><th>Cant.</th><th>Subtotal</th></tr></thead>
        <tbody>
        <?php foreach ($order['items'] as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td>$<?= number_format($item['unit_price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>$<?= number_format($item['unit_price'] * $item['quantity'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Total: $<?= number_format($order['total'], 2) ?></h3>
    <p>Enviaremos tu pedido a: <?= htmlspecialchars($order['shipping_address']) ?>, <?= htmlspecialchars($order['shipping_city']) ?></p>

    <a href="<?= BASE_URL ?>/" class="btn btn-primary">Seguir comprando</a>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
