<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin','docente']);
require_once __DIR__ . '/../../../config/database.php';

$conn      = getConnection();
$idDocente = (int)$_SESSION['user_id'];
$accion    = $_POST['accion'] ?? $_GET['accion'] ?? '';

// ── LISTA DEL DÍA ────────────────────────────────────────────────────────────
if ($accion === 'lista_hoy') {
    header('Content-Type: text/html; charset=utf-8');
    $idGM = (int)($_GET['idGM'] ?? 0);
    if (!$idGM) { echo '<tr><td colspan="4" class="text-center text-text-muted py-4">Sin clase seleccionada</td></tr>'; exit; }

    $stmt = $conn->prepare(
        "SELECT al.nombre, al.matricula,
                COALESCE(a.estado,'—') AS estado,
                COALESCE(TIME_FORMAT(a.hora,'%H:%i'),'—') AS hora
         FROM Alumnos al
         JOIN GruposMaterias gm ON gm.idGrupo = al.idGrupo
         LEFT JOIN Asistencias a ON a.idAlumno=al.id AND a.idGrupoMateria=gm.id AND a.fecha=CURDATE()
         WHERE gm.id=? AND al.activo=1
         ORDER BY al.nombre"
    );
    $stmt->bind_param('i', $idGM);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (!$rows) { echo '<tr><td colspan="4" class="text-center text-text-muted py-4">Sin alumnos en este grupo</td></tr>'; exit; }

    $badges = ['presente'=>'badge-success','falta'=>'badge-error','retardo'=>'badge-warning','—'=>'badge-primary'];
    foreach ($rows as $r) {
        $badge = $badges[$r['estado']] ?? 'badge-primary';
        echo "<tr>
          <td>{$r['nombre']}</td>
          <td class='font-mono text-xs'>{$r['matricula']}</td>
          <td><span class='badge {$badge}'>{$r['estado']}</span></td>
          <td class='text-xs text-text-muted'>{$r['hora']}</td>
        </tr>";
    }
    exit;
}

// ── REGISTRAR ASISTENCIA ─────────────────────────────────────────────────────
if ($accion === 'registrar') {
    header('Content-Type: application/json; charset=utf-8');
    $idGM      = (int)($_POST['idGM']      ?? 0);
    $matricula = trim($_POST['matricula']  ?? '');
    $estado    = $_POST['estado'] ?? 'presente';

    if (!$idGM || !$matricula) {
        echo json_encode(['ok'=>false,'msg'=>'Datos incompletos']); exit;
    }
    if (!in_array($estado, ['presente','falta','retardo'])) {
        $estado = 'presente';
    }

    // Buscar alumno y verificar que pertenece al grupo
    $stmt = $conn->prepare(
        "SELECT al.id, al.nombre FROM Alumnos al
         JOIN GruposMaterias gm ON gm.idGrupo = al.idGrupo
         WHERE al.matricula=? AND gm.id=? AND al.activo=1 LIMIT 1"
    );
    $stmt->bind_param('si', $matricula, $idGM);
    $stmt->execute();
    $alumno = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$alumno) {
        echo json_encode(['ok'=>false,'msg'=>'Matrícula no encontrada en este grupo']); exit;
    }

    $hora  = date('H:i:s');
    $fecha = date('Y-m-d');

    // INSERT OR UPDATE (UNIQUE constraint on idGrupoMateria+idAlumno+fecha)
    $stmt = $conn->prepare(
        "INSERT INTO Asistencias (idGrupoMateria,idAlumno,estado,fecha,hora,registrado_por)
         VALUES (?,?,?,?,?,?)
         ON DUPLICATE KEY UPDATE estado=VALUES(estado), hora=VALUES(hora)"
    );
    $stmt->bind_param('iisssi', $idGM, $alumno['id'], $estado, $fecha, $hora, $idDocente);

    if ($stmt->execute()) {
        echo json_encode(['ok'=>true,'nombre'=>$alumno['nombre'],'estado'=>$estado]);
    } else {
        echo json_encode(['ok'=>false,'msg'=>'Error al guardar: '.$conn->error]);
    }
    exit;
}

echo json_encode(['ok'=>false,'msg'=>'Acción inválida']);
