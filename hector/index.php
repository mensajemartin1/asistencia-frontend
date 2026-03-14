<?php

include "database.php";

date_default_timezone_set("America/Mexico_City");

$hora_actual = date("H:i:s");

$sql = "SELECT * FROM Materias 
WHERE '$hora_actual' BETWEEN horaInicio AND horaFin";

$result = $conn->query($sql);

$materia_actual = "Sin clase";

if($result && $result->num_rows > 0){

$row = $result->fetch_assoc();
$materia_actual = $row['nombreMateria'];

}

?>



<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<title>Sistema de Asistencia</title>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>

body{
font-family:Arial;
background:linear-gradient(135deg,#667eea,#764ba2);
padding:30px;
}

.container{
max-width:1000px;
margin:auto;
}

.card{
background:white;
padding:20px;
border-radius:10px;
margin-bottom:20px;
}

input,select{
width:100%;
padding:10px;
margin-bottom:10px;
}

button{
padding:10px;
background:#667eea;
color:white;
border:none;
cursor:pointer;
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#667eea;
color:white;
padding:10px;
}

td{
padding:8px;
border-bottom:1px solid #ddd;
text-align:center;
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<h2>Registro de Asistencia</h2>

<form id="formAsistencia">

<input type="text" name="matricula" id="matricula" placeholder="Escanear QR">

<label>Materia actual</label>

<input type="text" name="materia" value="<?php echo $materia_actual; ?>" readonly>

<select name="estado">

<option value="Presente">Presente</option>
<option value="Ausente">Ausente</option>

</select>

<button type="submit">Registrar</button>

</form>

</div>

<div class="card">

<h3>Lista de Asistencia</h3>

<table>

<thead>

<tr>

<th>ID</th>
<th>Matricula</th>
<th>Nombre</th>
<th>Materia</th>
<th>Estado</th>
<th>Fecha</th>
<th>Hora</th>

</tr>

</thead>

<tbody id="tablaAsistencia"></tbody>

</table>

</div>

</div>

<script>

function cargarAsistencia(){

$.ajax({

url:"obtener_asistencia.php",

success:function(data){

$("#tablaAsistencia").html(data);

}

});

}

cargarAsistencia();

$("#formAsistencia").submit(function(e){

e.preventDefault();

$.ajax({

url:"guardar_asistencia.php",

type:"POST",

data:$(this).serialize(),

success:function(res){

if(res=="ok"){

$("#formAsistencia")[0].reset();

cargarAsistencia();

}else{

alert("Error al registrar");

}

}

});

});

$("#matricula").keypress(function(e){

if(e.which == 13){

e.preventDefault();

$("#formAsistencia").submit();

}

});

</script>

</body>
</html>