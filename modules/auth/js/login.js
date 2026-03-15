function mostrarPassword() {
    let pass = document.getElementById("password");
    pass.type = (pass.type === "password") ? "text" : "password";
}

function login() {
    let usuario  = document.getElementById("usuario").value;
    let password = document.getElementById("password").value;

    let form = new FormData();
    form.append("accion",   "login");
    form.append("usuario",  usuario);
    form.append("password", password);

    fetch("../controllers/loginModel.php", { method: "POST", body: form })
        .then(response => response.text())
        .then(data => {
            if (data === "ok") {
                window.location = "../views/dashboard.php";
            } else {
                document.getElementById("mensaje").innerHTML = "Datos incorrectos";
            }
        });
}

function registrar() {
    let usuario  = prompt("Nuevo usuario:");
    let password = prompt("Contraseña:");

    let form = new FormData();
    form.append("accion",   "registro");
    form.append("usuario",  usuario);
    form.append("password", password);

    fetch("../controllers/loginModel.php", { method: "POST", body: form })
        .then(response => response.text())
        .then(data => alert(data));
}

function recuperar() {
    let usuario = prompt("Ingrese su usuario:");

    let form = new FormData();
    form.append("accion",  "recuperar");
    form.append("usuario", usuario);

    fetch("../controllers/loginModel.php", { method: "POST", body: form })
        .then(response => response.text())
        .then(data => alert(data));
}
