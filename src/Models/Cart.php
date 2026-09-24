<?php

/**
 * Carrito simple basado en sesión PHP ($_SESSION['cart']).
 * Estructura: ['product_id' => qty, ...]
 * Esto evita tener que loguearse para comprar; en el checkout
 * se vincula el pedido al usuario si está autenticado.
 */
class Cart
{
    public static function items(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    public static function add(int $productId, int $qty = 1): void
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $qty;
    }

    public static function update(int $productId, int $qty): void
    {
        if ($qty <= 0) {
            self::remove($productId);
            return;
        }
        $_SESSION['cart'][$productId] = $qty;
    }

    public static function remove(int $productId): void
    {
        unset($_SESSION['cart'][$productId]);
    }

    public static function clear(): void
    {
        $_SESSION['cart'] = [];
    }

    public static function count(): int
    {
        return array_sum(self::items());
    }

    /**
     * Devuelve los items del carrito con datos completos de producto.
     */
    public static function detailedItems(): array
    {
        $items = self::items();
        if (empty($items)) {
            return [];
        }

        $productModel = new Product();
        $result = [];
        foreach ($items as $productId => $qty) {
            $product = $productModel->find((int) $productId);
            if ($product) {
                $product['qty'] = $qty;
                $product['subtotal'] = $product['price'] * $qty;
                $result[] = $product;
            }
        }
        return $result;
    }

    public static function total(): float
    {
        $total = 0.0;
        foreach (self::detailedItems() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }
}
