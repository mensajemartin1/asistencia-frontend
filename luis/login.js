function mostrarPassword(){

let pass = document.getElementById("password");

if(pass.type === "password"){
pass.type = "text";
}else{
pass.type = "password";
}

}

function login(){

let usuario = document.getElementById("usuario").value;
let password = document.getElementById("password").value;

fetch(`loginModel.php?accion=login&usuario=${usuario}&password=${password}`)
.then(response => response.text())
.then(data => {

if(data === "ok"){
window.location = "asistencia.php";
}else{
document.getElementById("mensaje").innerHTML = "Datos incorrectos";
}

});

}

function registrar(){

let usuario = prompt("Nuevo usuario:");
let password = prompt("Contraseña:");

fetch(`loginModel.php?accion=registro&usuario=${usuario}&password=${password}`)
.then(response => response.text())
.then(data => alert(data));

}

function recuperar(){

let usuario = prompt("Ingrese su usuario:");

fetch(`loginModel.php?accion=recuperar&usuario=${usuario}`)
.then(response => response.text())
.then(data => alert(data));

}