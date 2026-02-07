<?php

require_once __DIR__ . '/Config.php';

class Conexion
{
    public static function conectar(): PDO
    {
        $cadenadeconexion = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

        try {
            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            $pdo = new PDO($cadenadeconexion, DB_USER, DB_PASS, $opciones);
            return $pdo;

        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }
}
