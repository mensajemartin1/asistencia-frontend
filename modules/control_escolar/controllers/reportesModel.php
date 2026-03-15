<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin','control_escolar']);
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../reports/lib/fpdf.php';

$conn      = getConnection();

$rol         = $_SESSION['rol']    ?? '';
$campusScope = ($rol === 'control_escolar') ? ($_SESSION['campus'] ?? '') : '';

$tipo      = $_GET['tipo']     ?? 'grupo';
$formato   = $_GET['formato']  ?? 'pdf';
$idGrupo   = (int)($_GET['idGrupo']   ?? 0);
$idMateria = (int)($_GET['idMateria'] ?? 0);
$idAlumno  = (int)($_GET['idAlumno']  ?? 0);
$fechaIni  = $_GET['fechaIni'] ?? date('Y-m-01');
$fechaFin  = $_GET['fechaFin'] ?? date('Y-m-d');

// Seguridad: si CE intenta reportar un grupo de otro campus, rechazar
if ($campusScope && $idGrupo) {
    $chk = $conn->prepare("SELECT id FROM Grupos WHERE id=? AND campus=? LIMIT 1");
    $chk->bind_param('is', $idGrupo, $campusScope);
    $chk->execute();
    if (!$chk->get_result()->num_rows) {
        http_response_code(403); exit;
    }
    $chk->close();
}

// Leer config
$cfgQuery = $conn->query("SELECT clave,valor FROM Configuracion");
$cfg = [];
while ($row = $cfgQuery->fetch_assoc()) $cfg[$row['clave']] = $row['valor'];
$pctMin = (int)($cfg['porcentaje_minimo'] ?? 80);

// ── DATOS REPORTE ────────────────────────────────────────────────────────────
$rows = [];

if ($tipo === 'grupo' && $idGrupo) {
    $stmt = $conn->prepare(
        "SELECT al.nombre AS alumno, al.matricula,
                m.nombre AS materia,
                COUNT(a.id) AS total,
                SUM(a.estado='presente') AS presentes,
                ROUND(100*SUM(a.estado='presente')/COUNT(a.id),1) AS pct
         FROM Alumnos al
         JOIN GruposMaterias gm ON gm.idGrupo=al.idGrupo
         JOIN Materias m ON m.id=gm.idMateria
         LEFT JOIN Asistencias a ON a.idAlumno=al.id AND a.idGrupoMateria=gm.id
             AND a.fecha BETWEEN ? AND ?
         WHERE al.idGrupo=? AND al.activo=1
         GROUP BY al.id, gm.id ORDER BY al.nombre, m.nombre"
    );
    $stmt->bind_param('ssi', $fechaIni, $fechaFin, $idGrupo);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

if ($tipo === 'alumno' && $idAlumno) {
    $stmt = $conn->prepare(
        "SELECT al.nombre AS alumno, al.matricula, m.nombre AS materia,
                COUNT(a.id) AS total,
                SUM(a.estado='presente') AS presentes,
                ROUND(100*SUM(a.estado='presente')/NULLIF(COUNT(a.id),0),1) AS pct
         FROM Alumnos al
         JOIN GruposMaterias gm ON gm.idGrupo=al.idGrupo
         JOIN Materias m ON m.id=gm.idMateria
         LEFT JOIN Asistencias a ON a.idAlumno=al.id AND a.idGrupoMateria=gm.id
             AND a.fecha BETWEEN ? AND ?
         WHERE al.id=?
         GROUP BY gm.id ORDER BY m.nombre"
    );
    $stmt->bind_param('ssi', $fechaIni, $fechaFin, $idAlumno);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// ── CSV ──────────────────────────────────────────────────────────────────────
if ($formato === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="reporte_' . date('Ymd') . '.csv"');
    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($out, ['Alumno','Matrícula','Materia','Total clases','Presentes','% Asistencia']);
    foreach ($rows as $r) {
        fputcsv($out, [$r['alumno'],$r['matricula'],$r['materia'],$r['total'],$r['presentes'],$r['pct'].'%']);
    }
    fclose($out);
    exit;
}

// ── PDF ──────────────────────────────────────────────────────────────────────
$pdf = new FPDF('L','mm','Letter');
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

// Header
$pdf->SetFillColor(30,58,138);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(0,10,'ITSZ - Reporte de Asistencia',0,1,'C',true);
$pdf->SetFont('Arial','',9);
$pdf->SetTextColor(100,100,100);
$pdf->Cell(0,6,'Período: '.$fechaIni.' al '.$fechaFin,0,1,'C');
$pdf->Ln(3);

// Table header
$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(30,58,138);
$pdf->SetTextColor(255,255,255);
$cols = ['Alumno'=>70,'Matrícula'=>25,'Materia'=>60,'Total'=>20,'Presentes'=>22,'% Asistencia'=>28];
foreach ($cols as $label => $w) $pdf->Cell($w,7,$label,1,0,'C',true);
$pdf->Ln();

// Rows
$pdf->SetFont('Arial','',8);
$alt = false;
foreach ($rows as $r) {
    $pct = (float)($r['pct'] ?? 0);
    $pdf->SetTextColor(0,0,0);
    if ($pct < $pctMin) {
        $pdf->SetFillColor(254,226,226);
        $pdf->SetTextColor(185,28,28);
    } else {
        $pdf->SetFillColor($alt ? 240:255,  $alt ? 248:255,  $alt ? 255:255);
        $pdf->SetTextColor(0,0,0);
    }
    $pdf->Cell(70,6,$r['alumno'],1,0,'L',$pct<$pctMin);
    $pdf->Cell(25,6,$r['matricula'],1,0,'C',$pct<$pctMin);
    $pdf->Cell(60,6,$r['materia'],1,0,'L',$pct<$pctMin);
    $pdf->Cell(20,6,$r['total'],1,0,'C',$pct<$pctMin);
    $pdf->Cell(22,6,$r['presentes'],1,0,'C',$pct<$pctMin);
    $pdf->Cell(28,6,$pct.'%',1,0,'C',$pct<$pctMin);
    $pdf->Ln();
    $alt = !$alt;
}

$pdf->Ln(4);
$pdf->SetFont('Arial','I',7);
$pdf->SetTextColor(150,150,150);
$pdf->Cell(0,5,'Generado el '.date('d/m/Y H:i').' | Porcentaje mínimo requerido: '.$pctMin.'%',0,1,'C');

$pdf->Output('I','reporte_asistencia_'.date('Ymd').'.pdf');
