<?php

class Product
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(?int $categoryId = null, ?int $brandId = null, ?string $search = null): array
    {
        $sql = 'SELECT p.*, b.name AS brand_name, c.name AS category_name
                FROM products p
                LEFT JOIN brands b ON b.id = p.brand_id
                LEFT JOIN categories c ON c.id = p.category_id
                WHERE p.is_active = TRUE';
        $params = [];

        if ($categoryId) {
            $sql .= ' AND p.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }
        if ($brandId) {
            $sql .= ' AND p.brand_id = :brand_id';
            $params['brand_id'] = $brandId;
        }
        if ($search) {
            $sql .= ' AND (p.name ILIKE :search OR p.description ILIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY p.created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, b.name AS brand_name, c.name AS category_name
             FROM products p
             LEFT JOIN brands b ON b.id = p.brand_id
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.slug = :slug'
        );
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO products (name, slug, description, price, stock, brand_id, category_id, image_url)
             VALUES (:name, :slug, :description, :price, :stock, :brand_id, :category_id, :image_url)
             RETURNING id'
        );
        $stmt->execute($data);
        return (int) $stmt->fetchColumn();
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $stmt = $this->db->prepare(
            'UPDATE products SET name=:name, slug=:slug, description=:description, price=:price,
             stock=:stock, brand_id=:brand_id, category_id=:category_id, image_url=:image_url,
             updated_at = NOW()
             WHERE id=:id'
        );
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE products SET is_active = FALSE WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function decreaseStock(int $id, int $qty): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE products SET stock = stock - :qty WHERE id = :id AND stock >= :qty'
        );
        return $stmt->execute(['qty' => $qty, 'id' => $id]);
    }
}
