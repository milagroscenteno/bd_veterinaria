
<?php

$host = 'localhost';
$db   = 'Veterianaria';
$user = 'root';
$pass = '';

try {
    $conexion = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $conexion->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {
    http_response_code(500);

    die('Error al conectar con la base de datos: '
        . $e->getMessage());
}
