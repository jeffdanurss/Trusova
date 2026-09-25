<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Finalizar compra</h1>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="checkout-layout">
    <form action="<?= BASE_URL ?>/checkout" method="post" class="checkout-form">
        <h3>Datos de envío</h3>
        <label>Nombre completo
            <input type="text" name="name" required>
        </label>
        <label>Dirección
            <input type="text" name="address" required>
        </label>
        <label>Ciudad
            <input type="text" name="city" required>
        </label>
        <label>Teléfono
            <input type="text" name="phone" required>
        </label>

        <h3>Método de pago</h3>
        <label><input type="radio" name="payment_method" value="tarjeta" checked> Tarjeta de crédito/débito (simulado)</label>
        <label><input type="radio" name="payment_method" value="transferencia"> Transferencia bancaria (simulado)</label>

        <button type="submit" class="btn btn-primary">Confirmar y pagar</button>
    </form>

    <aside class="order-summary">
        <h3>Resumen del pedido</h3>
        <p>Total a pagar: <strong>$<?= number_format($total, 2) ?></strong></p>
        <a href="<?= BASE_URL ?>/carrito">&larr; Volver al carrito</a>
    </aside>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
