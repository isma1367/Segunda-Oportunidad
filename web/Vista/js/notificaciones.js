// Creo el contenedor de avisos y le doy clase e id
var contenedorAvisos = document.createElement("div")
contenedorAvisos.classList.add("contenedor-avisos")
contenedorAvisos.id = "contenedor-avisos"
document.querySelector("body").appendChild(contenedorAvisos)

// Evento para deterctar click en la x de los avisos
contenedorAvisos.addEventListener("click", (e) => {
    var avisoId = e.target.closest("div.aviso").id
    if (e.target.closest("button.btn-cerrar")) {
        cerrarAviso(avisoId)
    }
})

// Función para agregar el aviso
const agregarAviso = ({ tipo, titulo, desc }) => {
   // console.log(tipo, titulo, desc)
    // Crear el nuevo aviso
    var nuevoAviso = document.createElement("div")

    // Agregar clases correspondientes
    nuevoAviso.classList.add("aviso")
    nuevoAviso.classList.add(tipo)

    // Agregar el id al Aviso
    const fecha = Date.now() // id único
    nuevoAviso.id = fecha+Math.floor(Math.random() * 100000000);

    // Iconos
    const iconos = {
        exito: `<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>`,
        error: `<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-exclamation-octagon-fill" viewBox="0 0 16 16">
                    <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>`,
        info: `<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                </svg>`,
        warning: `<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>`
    }

    // Plantilla del aviso
    var aviso = `<div class="contenido">
                        <div class="icono">
                            ${iconos[tipo]}
                        </div>
                        <div class="texto">
                            <p class="titulo">${titulo}</p>
                            <p class="desc">${desc}</p>
                        </div>
                </div>
                    <button class="btn-cerrar">
                        <div class="icono">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </div>
                    </button>`

    // Agregamos la plantilla a nuevoAviso
    nuevoAviso.innerHTML = aviso

    // Agregamos el nuevo aviso al contenedor
    contenedorAvisos.appendChild(nuevoAviso)

    // Función para manejar la salida del aviso
    const handleAnimacionSalida = (e) => {
        if (e.animationName == "salida") {
            nuevoAviso.removeEventListener("animationend", handleAnimacionSalida)
            nuevoAviso.remove()
        }
    }

    // Que la notificaci´pn se cierre a los 5s
    setTimeout(() => cerrarAviso(nuevoAviso.id), 10000)

    // Agregar un evento para detectar cuándo termina la animación
    nuevoAviso.addEventListener("animationend", handleAnimacionSalida)
}

// Función para sacar el Aviso
const cerrarAviso = (id) => {
    document.getElementById(id)?.classList.add("saliendo")
}
