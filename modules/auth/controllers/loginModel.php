<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/mail.php';

header('Content-Type: text/plain; charset=utf-8');

$conn   = getConnection();
$accion = $_POST['accion'] ?? '';

// ── LOGIN ──────────────────────────────────────────────────────────────────────
if ($accion === 'login') {
    $correo   = strtolower(trim($_POST['correo']   ?? ''));
    $password = $_POST['password'] ?? '';

    if (!$correo || !$password) { echo 'error:campos_vacios'; exit; }

    $stmt = $conn->prepare(
        "SELECT id, nombre, password, estado, rol, campus FROM Usuarios WHERE correo = ? LIMIT 1"
    );
    if (!$stmt) { echo 'error:db'; exit; }
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row) { echo 'error:credenciales'; exit; }

    switch ($row['estado']) {
        case 'pendiente_confirmacion':
            echo 'error:pendiente_confirmacion';
            exit;
        case 'rechazado':
            echo 'error:rechazado';
            exit;
    }

    if (!password_verify($password, $row['password'])) {
        echo 'error:credenciales';
        exit;
    }

    $_SESSION['user_id'] = $row['id'];
    $_SESSION['correo']  = $correo;
    $_SESSION['nombre']  = $row['nombre'];
    $_SESSION['rol']     = $row['rol'];
    $_SESSION['campus']  = $row['campus'] ?? '';

    // Auto-crear registro Alumnos para estudiantes si aún no existe
    if ($row['rol'] === 'estudiante') {
        $chk = $conn->prepare("SELECT id FROM Alumnos WHERE idUsuario = ? LIMIT 1");
        $chk->bind_param('i', $row['id']);
        $chk->execute();
        $exists = $chk->get_result()->num_rows > 0;
        $chk->close();

        if (!$exists) {
            // Matrícula = prefijo del correo institucional
            $matricula = strtolower(explode('@', $correo)[0]);
            $ins = $conn->prepare(
                "INSERT INTO Alumnos (idGrupo, idUsuario, nombre, matricula) VALUES (NULL, ?, ?, ?)"
            );
            $ins->bind_param('iss', $row['id'], $row['nombre'], $matricula);
            $ins->execute();
            $ins->close();
        }
    }

    echo 'ok:' . $row['rol'];
    exit;
}

// ── REGISTRO ───────────────────────────────────────────────────────────────────
if ($accion === 'registro') {
    $nombre   = trim($_POST['nombre']   ?? '');
    $correo   = strtolower(trim($_POST['correo']   ?? ''));
    $password = $_POST['password'] ?? '';
    $campus   = trim($_POST['campus']   ?? '');

    if (!$nombre || !$correo || !$password) {
        echo 'error:campos_vacios'; exit;
    }

    // Solo correos institucionales
    if (substr($correo, -strlen('@zongolica.tecnm.mx')) !== '@zongolica.tecnm.mx') {
        echo 'error:correo_invalido'; exit;
    }

    // Verificar que el correo no exista ya
    $stmt = $conn->prepare("SELECT id FROM Usuarios WHERE correo = ? LIMIT 1");
    if (!$stmt) { echo 'error:db'; exit; }
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        echo 'error:ya_existe'; exit;
    }
    $stmt->close();

    $hash   = password_hash($password, PASSWORD_BCRYPT);
    $token  = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', strtotime('+24 hours'));

    $stmt = $conn->prepare(
        "INSERT INTO Usuarios (nombre, correo, password, campus, rol, estado, token_confirmacion, token_expira)
         VALUES (?, ?, ?, ?, 'estudiante', 'pendiente_confirmacion', ?, ?)"
    );
    if (!$stmt) { echo "error:db_schema — {$conn->error}"; exit; }
    $stmt->bind_param('ssssss', $nombre, $correo, $hash, $campus, $token, $expira);

    if (!$stmt->execute()) {
        $stmt->close();
        echo 'error:db'; exit;
    }
    $stmt->close();

    mailConfirmacionCuenta($correo, $nombre, $token);

    echo 'ok';
    exit;
}

// ── RECUPERAR ──────────────────────────────────────────────────────────────────
if ($accion === 'recuperar') {
    $correo = strtolower(trim($_POST['correo'] ?? ''));

    if (!$correo) { echo 'error:campos_vacios'; exit; }

    $stmt = $conn->prepare(
        "SELECT id, nombre FROM Usuarios WHERE correo = ? AND estado = 'activo' LIMIT 1"
    );
    if (!$stmt) { echo 'error:db'; exit; }
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Respuesta idéntica para cuentas existentes y no existentes (seguridad)
    if (!$row) { echo 'ok:enviado'; exit; }

    $token  = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $stmt = $conn->prepare(
        "UPDATE Usuarios SET token_reset = ?, token_reset_expira = ? WHERE id = ?"
    );
    if (!$stmt) { echo 'error:db'; exit; }
    $stmt->bind_param('ssi', $token, $expira, $row['id']);
    $stmt->execute();
    $stmt->close();

    mailRecuperarAcceso($correo, $row['nombre'], $token);

    echo 'ok:enviado';
    exit;
}

echo 'error:accion_invalida';
