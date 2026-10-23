
<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexion.php';

$metodo = $_SERVER['REQUEST_METHOD'];

$id = null;

if (isset($_GET['id'])) {
    $id = filter_var(
        $_GET['id'],
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if ($id === false || $id === null) {
        responder(400, ['error' => 'El ID debe ser un entero positivo']);
    }
}

function responder($codigo, $datos) {
    http_response_code($codigo);
    echo json_encode(
        $datos,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit;
}

function leerJson() {
    $contenido = file_get_contents('php://input');
    $datos = json_decode($contenido, true);

    if (!is_array($datos)) {
        responder(400, ['error' => 'Debes enviar un JSON válido']);
    }

    return $datos;
}

function validarMascota($datos) {
    $campos = [
        'nombre_mascota',
        'fecha_nacimiento',
        'genero',
        'raza'
    ];

    foreach ($campos as $campo) {
        if (
            !isset($datos[$campo]) ||
            !is_string($datos[$campo]) ||
            trim($datos[$campo]) === ''
        ) {
            responder(400, [
                'error' => "El campo $campo es obligatorio"
            ]);
        }
    }

    if (
        mb_strlen($datos['nombre_mascota']) > 100 ||
        mb_strlen($datos['raza']) > 100
    ) {
        responder(400, [
            'error' => 'El nombre y la raza admiten hasta 100 caracteres'
        ]);
    }

    if (!in_array($datos['genero'], ['Macho', 'Hembra'], true)) {
        responder(400, [
            'error' => 'El género debe ser Macho o Hembra'
        ]);
    }

    $fecha = DateTime::createFromFormat(
        '!Y-m-d',
        $datos['fecha_nacimiento']
    );

    if (
        !$fecha ||
        $fecha->format('Y-m-d') !== $datos['fecha_nacimiento'] ||
        $fecha > new DateTime('today')
    ) {
        responder(400, [
            'error' => 'La fecha debe ser válida y usar AAAA-MM-DD'
        ]);
    }

    return [
        'nombre_mascota' => trim($datos['nombre_mascota']),
        'fecha_nacimiento' => $datos['fecha_nacimiento'],
        'genero' => $datos['genero'],
        'raza' => trim($datos['raza'])
    ];
}

try {
    switch ($metodo) {

        // LISTAR TODAS O CONSULTAR POR ID
        case 'GET':

            if ($id !== null) {
                $sql = $conexion->prepare(
                    'SELECT * FROM mascotas WHERE id = ?'
                );
                $sql->execute([$id]);

                $mascota = $sql->fetch();

                if (!$mascota) {
                    responder(404, [
                        'error' => 'Mascota no encontrada'
                    ]);
                }

                responder(200, $mascota);
            }

            $sql = $conexion->query(
                'SELECT * FROM mascotas ORDER BY id ASC'
            );

            responder(200, $sql->fetchAll());

        // REGISTRAR UNA MASCOTA
        case 'POST':

            $datos = validarMascota(leerJson());

            $sql = $conexion->prepare(
                'INSERT INTO mascotas
                (nombre_mascota, fecha_nacimiento, genero, raza)
                VALUES
                (:nombre_mascota, :fecha_nacimiento, :genero, :raza)'
            );

            $sql->execute($datos);

            responder(201, [
                'mensaje' => 'Mascota registrada correctamente',
                'id' => (int) $conexion->lastInsertId(),
                'mascota' => $datos
            ]);

        // ACTUALIZAR UNA MASCOTA
        case 'PUT':

            if ($id === null) {
                responder(400, [
                    'error' => 'Debes indicar el ID en la URL'
                ]);
            }

            $datos = validarMascota(leerJson());

            $buscar = $conexion->prepare(
                'SELECT id FROM mascotas WHERE id = ?'
            );
            $buscar->execute([$id]);

            if (!$buscar->fetch()) {
                responder(404, [
                    'error' => 'Mascota no encontrada'
                ]);
            }

            $datos['id'] = $id;

            $sql = $conexion->prepare(
                'UPDATE mascotas SET
                    nombre_mascota = :nombre_mascota,
                    fecha_nacimiento = :fecha_nacimiento,
                    genero = :genero,
                    raza = :raza
                WHERE id = :id'
            );

            $sql->execute($datos);

            responder(200, [
                'mensaje' => 'Mascota actualizada correctamente'
            ]);

        // ELIMINAR UNA MASCOTA
        case 'DELETE':

            if ($id === null) {
                responder(400, [
                    'error' => 'Debes indicar el ID en la URL'
                ]);
            }

            $sql = $conexion->prepare(
                'DELETE FROM mascotas WHERE id = ?'
            );

            $sql->execute([$id]);

            if ($sql->rowCount() === 0) {
                responder(404, [
                    'error' => 'Mascota no encontrada'
                ]);
            }

            responder(200, [
                'mensaje' => 'Mascota eliminada correctamente'
            ]);

        default:

            header('Allow: GET, POST, PUT, DELETE');

            responder(405, [
                'error' => 'Método HTTP no permitido'
            ]);
    }

} catch (PDOException $e) {

    error_log($e->getMessage());

    responder(500, [
        'error' => 'Error interno al procesar la solicitud'
    ]);
}
