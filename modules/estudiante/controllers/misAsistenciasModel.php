<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['estudiante','admin']);
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$conn      = getConnection();
$idUsuario = (int)$_SESSION['user_id'];
$accion    = $_GET['accion'] ?? 'resumen';

// Obtener alumno vinculado al usuario
$stmt = $conn->prepare("SELECT id, nombre, matricula, idGrupo FROM Alumnos WHERE idUsuario=? LIMIT 1");
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$alumno = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$alumno) {
    // Primera vez sin login previo (edge case): devolver respuesta manejable
    echo json_encode(['error' => 'sin_registro']);
    exit;
}

$idAlumno = $alumno['id'];
$idGrupo  = $alumno['idGrupo'] ? (int)$alumno['idGrupo'] : null;

// ── RESUMEN POR MATERIA ──────────────────────────────────────────────────────
if ($accion === 'resumen') {
    // Sin grupo asignado aún
    if (!$idGrupo) {
        echo json_encode([
            'alumno'  => $alumno,
            'materias'=> [],
            'sin_grupo' => true,
        ]);
        exit;
    }

    $r      = $conn->query("SELECT valor FROM Configuracion WHERE clave='porcentaje_minimo' LIMIT 1");
    $pctMin = $r ? (int)$r->fetch_row()[0] : 80;

    // Todas las materias del grupo + stats de asistencia (LEFT JOIN para mostrar aunque haya 0 clases)
    $stmt = $conn->prepare(
        "SELECT m.nombre AS materia, gm.id AS idGM, gm.ciclo,
                COUNT(a.id)                       AS total,
                COALESCE(SUM(a.estado='presente'),0) AS presentes,
                COALESCE(SUM(a.estado='falta'),0)    AS faltas,
                COALESCE(SUM(a.estado='retardo'),0)  AS retardos
         FROM GruposMaterias gm
         JOIN Materias m ON m.id = gm.idMateria
         LEFT JOIN Asistencias a ON a.idGrupoMateria = gm.id AND a.idAlumno = ?
         WHERE gm.idGrupo = ? AND gm.activo = 1
         GROUP BY gm.id
         ORDER BY m.nombre"
    );
    $stmt->bind_param('ii', $idAlumno, $idGrupo);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    foreach ($rows as &$row) {
        $row['pct']     = $row['total'] > 0
            ? round(100 * $row['presentes'] / $row['total'], 1) : 0;
        $row['alerta']  = $row['pct'] < $pctMin;
        $row['pct_min'] = $pctMin;
    }

    echo json_encode([
        'alumno'  => $alumno,
        'materias'=> $rows,
    ]);
    exit;
}

// ── HISTORIAL COMPLETO ───────────────────────────────────────────────────────
if ($accion === 'historial') {
    $idGM     = (int)($_GET['idGM'] ?? 0);
    $fechaIni = $_GET['fechaIni'] ?? date('Y-m-01');
    $fechaFin = $_GET['fechaFin'] ?? date('Y-m-d');

    $sql = "SELECT m.nombre AS materia, g.nombre AS grupo,
                   a.estado, a.fecha, TIME_FORMAT(a.hora,'%H:%i') AS hora
            FROM Asistencias a
            JOIN GruposMaterias gm ON gm.id = a.idGrupoMateria
            JOIN Materias m ON m.id = gm.idMateria
            JOIN Grupos   g ON g.id = gm.idGrupo
            WHERE a.idAlumno = ? AND a.fecha BETWEEN ? AND ?";

    $types  = 'iss';
    $params = [$idAlumno, $fechaIni, $fechaFin];

    if ($idGM) {
        $sql .= " AND a.idGrupoMateria=?";
        $types .= 'i'; $params[] = $idGM;
    }
    $sql .= " ORDER BY a.fecha DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    exit;
}

// ── MIS MATERIAS (selector) ──────────────────────────────────────────────────
if ($accion === 'mis_materias') {
    $stmt = $conn->prepare(
        "SELECT DISTINCT gm.id AS idGM, m.nombre AS materia
         FROM Asistencias a
         JOIN GruposMaterias gm ON gm.id = a.idGrupoMateria
         JOIN Materias m ON m.id = gm.idMateria
         WHERE a.idAlumno=?
         ORDER BY m.nombre"
    );
    $stmt->bind_param('i', $idAlumno);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    exit;
}

echo json_encode([]);
