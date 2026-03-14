<?php ?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<title>Sistema de Asistencias</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="consultas_controller.js"></script>

<style>

body{
font-family: Arial;
background:#f5f6fa;
margin:0;
text-align:center;
}

header{
background:#4b7bec;
color:white;
padding:20px;
font-size:22px;
}

.contenedor{
width:90%;
margin:auto;
margin-top:30px;
}

input,select{
padding:10px;
margin:5px;
border:1px solid #ccc;
border-radius:5px;
}

button{
padding:10px 15px;
border:none;
border-radius:5px;
background:#4b7bec;
color:white;
cursor:pointer;
margin:5px;
}

button:hover{
background:#3867d6;
}

table{
width:100%;
background:white;
border-collapse:collapse;
margin-top:20px;
}

th{
background:#4b7bec;
color:white;
padding:10px;
}

td{
padding:10px;
border-bottom:1px solid #eee;
}

img{
border-radius:50%;
}

.grafica{
width:400px;
margin:auto;
margin-top:40px;
}

</style>

</head>

<body>

<header>
Sistema de Control de Asistencias
</header>

<div class="contenedor">

<input type="text" id="alumno_id" placeholder="ID Alumno">
<button onclick="consultarAlumno()">Consultar Alumno</button>

<input type="text" id="grupo" placeholder="Grupo">
<button onclick="consultarGrupo()">Consultar Grupo</button>

<select id="materia_id">

<option value="">Seleccionar Materia</option>

<?php include "materias_select.php"; ?>

</select>

<button onclick="consultarMateria()">Consultar Materia</button>

<input type="date" id="fecha">
<button onclick="consultarFecha()">Consultar Fecha</button>

<button onclick="historial()">Ver Historial</button>

<table>

<thead>

<tr>
<th>Foto</th>
<th>No Control</th>
<th>Nombre</th>
<th>Grupo</th>
<th>Materia</th>
<th>Fecha</th>
</tr>

</thead>

<tbody id="tabla"></tbody>

</table>

<div class="grafica">

<canvas id="graficaGrupo"></canvas>

</div>

</div>

</body>

</html>