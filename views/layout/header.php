<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?= SITE_NAME ?>
        </title>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css"> 
    </head>
    <body>
        <header class="site-header">
            <div class="container header-inner">
                <a href="<?= BASE_URL ?>/" class="logo">⌚ <?= SITE_NAME ?></a>
                <form action="<?= BASE_URL ?>/" method="get" class="search-form">
                    <input type="text" name="q" placeholder="Buscar relojes..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button type="submit">Buscar</button>
                </form>
                <nav class="main-nav">
                    <a href="<?= BASE_URL ?>/carrito">🛒 Carrito (<?= Cart::count() ?>)</a>
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <a href="<?= BASE_URL ?>/admin">Panel Admin</a>
                            <?php endif; ?>
                            <span class="user-greeting">Hola, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                            <a href="<?= BASE_URL ?>/logout">Salir</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/login">Ingresar</a>
                            <a href="<?= BASE_URL ?>/registro">Crear cuenta</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>
<main class="container">
