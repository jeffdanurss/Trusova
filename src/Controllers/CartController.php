<?php

class CartController
{
    public function show(): void
    {
        $items = Cart::detailedItems();
        $total = Cart::total();
        require __DIR__ . '/../../views/cart/index.php';
    }

    public function add(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty = max(1, (int) ($_POST['qty'] ?? 1));

        if ($productId > 0) {
            Cart::add($productId, $qty);
        }

        header('Location: ' . BASE_URL . '/carrito');
        exit;
    }

    public function update(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty = (int) ($_POST['qty'] ?? 0);
        Cart::update($productId, $qty);

        header('Location: ' . BASE_URL . '/carrito');
        exit;
    }

    public function remove(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        Cart::remove($productId);

        header('Location: ' . BASE_URL . '/carrito');
        exit;
    }
}
