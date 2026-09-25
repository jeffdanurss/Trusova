<?php

class Order
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function createFromCart(array $cartItems, array $shipping, ?int $userId, string $paymentMethod): int
    {
        $this->db->beginTransaction();

        try {
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['subtotal'];
            }

            $stmt = $this->db->prepare(
                'INSERT INTO orders (user_id, total, status, shipping_name, shipping_address, shipping_city, shipping_phone)
                 VALUES (:user_id, :total, :status, :name, :address, :city, :phone) RETURNING id'
            );
            $stmt->execute([
                'user_id' => $userId,
                'total'   => $total,
                'status'  => 'pendiente',
                'name'    => $shipping['name'],
                'address' => $shipping['address'],
                'city'    => $shipping['city'],
                'phone'   => $shipping['phone'],
            ]);
            $orderId = (int) $stmt->fetchColumn();

            $itemStmt = $this->db->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity)
                 VALUES (:order_id, :product_id, :product_name, :unit_price, :quantity)'
            );

            $productModel = new Product();
            foreach ($cartItems as $item) {
                $itemStmt->execute([
                    'order_id'     => $orderId,
                    'product_id'   => $item['id'],
                    'product_name' => $item['name'],
                    'unit_price'   => $item['price'],
                    'quantity'     => $item['qty'],
                ]);
                $productModel->decreaseStock((int) $item['id'], (int) $item['qty']);
            }

            $paymentStmt = $this->db->prepare(
                'INSERT INTO payments (order_id, method, status, transaction_id, amount)
                 VALUES (:order_id, :method, :status, :txn, :amount)'
            );
            $paymentStmt->execute([
                'order_id' => $orderId,
                'method'   => $paymentMethod,
                'status'   => 'aprobado',
                'txn'      => 'SIM-' . strtoupper(bin2hex(random_bytes(6))),
                'amount'   => $total,
            ]);

            $this->db->prepare('UPDATE orders SET status = :status WHERE id = :id')
                ->execute(['status' => 'pagado', 'id' => $orderId]);

            $this->db->commit();
            return $orderId;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();
        if (!$order) {
            return null;
        }

        $itemsStmt = $this->db->prepare('SELECT * FROM order_items WHERE order_id = :id');
        $itemsStmt->execute(['id' => $id]);
        $order['items'] = $itemsStmt->fetchAll();

        return $order;
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM orders ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE orders SET status = :status WHERE id = :id');
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
}
