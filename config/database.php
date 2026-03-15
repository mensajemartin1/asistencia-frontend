<?php
/**
 * Conexión centralizada a la base de datos.
 * Lee credenciales del archivo .env vía config/env.php
 */
require_once __DIR__ . '/env.php';

function getConnection(): mysqli
{
    $conn = new mysqli(
        env('DB_HOST') ?? throw new RuntimeException('DB_HOST no definido en .env'),
        env('DB_USER') ?? throw new RuntimeException('DB_USER no definido en .env'),
        env('DB_PASS') ?? '',
        env('DB_NAME') ?? throw new RuntimeException('DB_NAME no definido en .env')
    );

    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['error' => 'Error de conexión a la base de datos']));
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
