<?php

require_once 'lib/fpdf.php';
require_once __DIR__ . '/../../config/database.php';

$conexion = getConnection();

// ── Consulta: asistencias por alumno (20 días = 100%) ────────────────────
$sql = "SELECT
            a.nombre,
            SUM(CASE WHEN asi.estado = 'asistencia' THEN 1 ELSE 0 END) AS asistencias,
            IFNULL(ROUND((SUM(CASE WHEN asi.estado = 'asistencia' THEN 1 ELSE 0 END) * 100) / 20, 2), 0) AS porcentaje
        FROM Alumnos a
        LEFT JOIN Asistencias asi ON a.id = asi.idAlumno
        GROUP BY a.id, a.nombre";

$resultado = $conexion->query($sql);

// ── Configuración del PDF ─────────────────────────────────────────────────
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 20);

// Encabezado
$pdf->SetFillColor(37, 99, 235);
$pdf->Rect(0, 0, 210, 40, 'F');

$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 18);
$pdf->Ln(5);
$pdf->Cell(0, 10, utf8_decode('SISTEMA DE GESTIÓN DE ASISTENCIAS'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, utf8_decode('Reporte Detallado de Rendimiento - Meta 20 Días'), 0, 1, 'C');
$pdf->Ln(15);

// Fecha de emisión
$pdf->SetTextColor(50, 50, 50);
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 10, 'Fecha de reporte: ' . date('d/m/Y H:i'), 0, 1, 'R');
$pdf->Ln(5);

// Encabezado de tabla
$pdf->SetFillColor(37, 99, 235);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetLineWidth(0.3);
$pdf->SetFont('Arial', 'B', 11);

$w = [100, 40, 40];

$pdf->Cell($w[0], 12, 'Nombre Completo',  1, 0, 'C', true);
$pdf->Cell($w[1], 12, 'Asistencias',      1, 0, 'C', true);
$pdf->Cell($w[2], 12, 'Porcentaje (%)',   1, 1, 'C', true);

// Cuerpo de la tabla
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(30, 30, 30);
$pdf->SetDrawColor(230, 230, 230);
$fill = false;

while ($fila = $resultado->fetch_assoc()) {

    $pdf->SetFillColor(245, 247, 251);
    $pdf->Cell($w[0], 10, '  ' . utf8_decode($fila['nombre']), 'BR', 0, 'L', $fill);
    $pdf->Cell($w[1], 10, $fila['asistencias'] . ' / 20',      'BR', 0, 'C', $fill);

    if ($fila['porcentaje'] < 80) {
        $pdf->SetTextColor(220, 38, 38);
    } else {
        $pdf->SetTextColor(22, 163, 74);
    }

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell($w[2], 10, $fila['porcentaje'] . '%', 'B', 1, 'C', $fill);

    $pdf->SetTextColor(30, 30, 30);
    $pdf->SetFont('Arial', '', 10);
    $fill = !$fill;
}

// Pie de página
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 8);
$pdf->SetTextColor(150, 150, 150);
$pdf->Cell(0, 10, utf8_decode('Este documento es un reporte oficial generado automáticamente.'), 0, 0, 'C');

$pdf->Output();
