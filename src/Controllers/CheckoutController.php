<?php

class CheckoutController
{
    public function show(): void
    {
        $items = Cart::detailedItems();
        $total = Cart::total();

        if (empty($items)) {
            header('Location: ' . BASE_URL . '/carrito');
            exit;
        }

        require __DIR__ . '/../../views/checkout/index.php';
    }

    public function process(): void
    {
        $items = Cart::detailedItems();
        if (empty($items)) {
            header('Location: ' . BASE_URL . '/carrito');
            exit;
        }

        $shipping = [
            'name'    => trim($_POST['name'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'city'    => trim($_POST['city'] ?? ''),
            'phone'   => trim($_POST['phone'] ?? ''),
        ];
        
        if (in_array('', $shipping, true)) {
            $error = 'Por favor completa todos los datos de envío.';
            $total = Cart::total();
            require __DIR__ . '/../../views/checkout/index.php';
            return;
        }

        $userId = $_SESSION['user']['id'] ?? null;

        try {
            $orderModel = new Order();
            $orderId = $orderModel->createFromCart($items, $shipping, $userId, $paymentMethod);
            Cart::clear();

            header('Location: ' . BASE_URL . '/pedido-confirmado/' . $orderId);
            exit;
        } catch (Throwable $e) {
            $error = 'Ocurrió un error procesando tu pedido: ' . $e->getMessage();
            $total = Cart::total();
            require __DIR__ . '/../../views/checkout/index.php';
        }
    }

    public function confirmation(array $params): void
    {
        $orderModel = new Order();
        $order = $orderModel->find((int) $params['id']);

        if (!$order) {
            http_response_code(404);
            echo 'Pedido no encontrado';
            return;
        }

        require __DIR__ . '/../../views/checkout/confirmation.php';
    }
}
