<?php

class Database {
     private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        // Si todavía no existe una conexión, la creamos
        if (self::$instance === null) {
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                DB_HOST,
                DB_PORT,
                DB_NAME
            );

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die('Error de conexión a la base de datos: ' . $e->getMessage());
            }
        }

        // Si ya existía, simplemente la devolvemos (no crea una nueva)
        return self::$instance;
    }
    
}