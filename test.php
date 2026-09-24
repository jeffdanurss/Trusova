<?php
require __DIR__ . '/src/Config/config.php';
require __DIR__ . '/src/Core/Database.php';


try {
    $pdo = Database::getConnection();
    echo "✅ ¡Conexión exitosa usando la clase Database!";

    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<br>Tablas: " . implode(', ', $tablas);

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}