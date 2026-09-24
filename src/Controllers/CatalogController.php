<?php

class CatalogController
{
    public function index(): void
    {
        $productModel = new Product();
        $categoryModel = new Category();
        $brandModel = new Brand();

        $categoryId = isset($_GET['category']) ? (int) $_GET['category'] : null;
        $brandId = isset($_GET['brand']) ? (int) $_GET['brand'] : null;
        $search = $_GET['q'] ?? null;

        $products = $productModel->all($categoryId, $brandId, $search);
        $categories = $categoryModel->all();
        $brands = $brandModel->all();

        require __DIR__ . '/../../views/catalog/index.php';
    }

    public function show(array $params): void
    {
        $productModel = new Product();
        $product = $productModel->findBySlug($params['slug']);

        if (!$product) {
            http_response_code(404);
            echo 'Producto no encontrado';
            return;
        }

        require __DIR__ . '/../../views/catalog/show.php';
    }
}
