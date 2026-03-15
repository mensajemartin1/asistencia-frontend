let grafica

function historial(){
    fetch("../controllers/consultasModel.php?accion=historial")
    .then(res => res.text())
    .then(data => {
        document.getElementById("tabla").innerHTML = data
    })
}

function consultarAlumno(){
    let id = document.getElementById("alumno_id").value
    fetch("../controllers/consultasModel.php?accion=porAlumno&alumno_id=" + id)
    .then(res => res.text())
    .then(data => {
        document.getElementById("tabla").innerHTML = data
    })
}

function consultarGrupo(){
    let grupo = document.getElementById("grupo").value
    fetch("../controllers/consultasModel.php?accion=porGrupo&grupo=" + grupo)
    .then(res => res.text())
    .then(data => {
        document.getElementById("tabla").innerHTML = data
    })
    cargarGrafica(grupo)
}

function consultarMateria(){
    let materia = document.getElementById("materia_id").value
    fetch("../controllers/consultasModel.php?accion=porMateria&materia_id=" + materia)
    .then(res => res.text())
    .then(data => {
        document.getElementById("tabla").innerHTML = data
    })
}

function consultarFecha(){
    let fecha = document.getElementById("fecha").value
    fetch("../controllers/consultasModel.php?accion=porFecha&fecha=" + fecha)
    .then(res => res.text())
    .then(data => {
        document.getElementById("tabla").innerHTML = data
    })
}

function cargarGrafica(grupo){
    fetch("../controllers/grafica_grupo.php?grupo=" + grupo)
    .then(res => res.json())
    .then(datos => {

        let nombres     = []
        let asistencias = []

        datos.forEach(d => {
            nombres.push(d.nombre)
            asistencias.push(d.total)
        })

        if(grafica){ grafica.destroy() }

        grafica = new Chart(document.getElementById("graficaGrupo"), {
            type: 'pie',
            data: {
                labels: nombres,
                datasets: [{
                    data: asistencias,
                    backgroundColor: [
                        '#4b7bec','#20bf6b','#f7b731',
                        '#eb3b5a','#8854d0','#0fb9b1'
                    ]
                }]
            }
        })

    })
}
