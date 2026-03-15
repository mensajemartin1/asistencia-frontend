<?php

include "database.php";

$matricula = $_POST['matricula'];
$estado = $_POST['estado'];

date_default_timezone_set("America/Mexico_City");

$fecha = date("Y-m-d");
$hora = date("H:i:s");

# buscar alumno

$sqlAlumno = "SELECT id FROM Alumnos WHERE matricula='$matricula'";
$resultAlumno = $conn->query($sqlAlumno);

if(!$resultAlumno || $resultAlumno->num_rows == 0){
echo "Alumno no encontrado";
exit;
}

$rowAlumno = $resultAlumno->fetch_assoc();
$idAlumno = $rowAlumno['id'];

# detectar materia por horario

$sqlMateria = "SELECT id FROM Materias 
WHERE '$hora' BETWEEN horaInicio AND horaFin";

$resultMateria = $conn->query($sqlMateria);

if(!$resultMateria || $resultMateria->num_rows == 0){
echo "No hay clase en este horario";
exit;
}

$rowMateria = $resultMateria->fetch_assoc();
$idMateria = $rowMateria['id'];

# guardar asistencia

$sql = "INSERT INTO Asistencias
(idAlumno,idMateria,estado,fecha,hora)
VALUES
('$idAlumno','$idMateria','$estado','$fecha','$hora')";

if($conn->query($sql)){
echo "ok";
}else{
echo $conn->error;
}

?>
