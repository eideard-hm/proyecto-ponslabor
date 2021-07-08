const imgPersona = document.querySelector('.imagen-persona');
const opcionesInfo = document.querySelector('.info-persona');
const contenedorBarraBusqueda = document.querySelector('.content-search #icon-search');
const inputBusqueda = document.querySelector('#txtSearchAspirante');
const barraBusqueda = document.querySelector('.content-bar-search');
const coverContenedorBusqueda = document.querySelector('#menu .cover-ctn-search');
const limpiarInputBuscador = document.querySelector('#borrar-contenido');

imgPersona.addEventListener('click', () => {
    opcionesInfo.classList.toggle('active');
});

/*=========================== BUSCADOR =========================*/
contenedorBarraBusqueda.addEventListener('click', () => {
    mostrarBarraBusqueda();
});

coverContenedorBusqueda.addEventListener('click', () => {
    ocultarBarraBusqueda();
});

limpiarInputBuscador.addEventListener('click', () => {
    inputBusqueda.value = '';
    inputBusqueda.focus();
});

barraBusqueda.addEventListener('submit', e => {
    e.preventDefault();
})

const mostrarBarraBusqueda = () => {
    barraBusqueda.style.top = '65px';
    barraBusqueda.style.opacity = '1';
    coverContenedorBusqueda.style.display = 'block';
    inputBusqueda.focus();
}

const ocultarBarraBusqueda = () => {
    barraBusqueda.style.top = '-10px';
    barraBusqueda.style.opacity = '0';
    coverContenedorBusqueda.style.display = 'none';
}

/* ======================== RESPONSIVE DESIGN ================== */

document.querySelector('#icono-reponsive').addEventListener('click', () => {
    mostrarBarraResponsive();

});

const mostrarBarraResponsive = () => {
    document.querySelector('.contenedor-responsive').classList.toggle('active');
    document.querySelector('#icono-reponsive').classList.toggle('fa-bars');
    document.querySelector('#icono-reponsive').classList.toggle('fa-times');
}