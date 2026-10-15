/**
 * ui.js — Confirmaciones (SweetAlert2) + Cargando bonito + barra (NProgress)
 * ============================================================
 *
 * CÓMO USARLO:
 *
 * 1) CONFIRMACIÓN en un botón de formulario:
 *    <button type="submit" data-confirm="¿Seguro que quieres eliminar esto?">Eliminar</button>
 *    Opcionales: data-title, data-icon, data-btn-ok
 *
 * 2) CONFIRMACIÓN en un link:
 *    <a href="/ruta" data-confirm="¿Quieres continuar?">Ir a la ruta</a>
 *
 * 3) CARGANDO: es automático en todo formulario que no sea GET.
 *    Si no lo quieres en un formulario: <form data-no-spinner>
 *
 * El cargando dura MÍNIMO 1 segundo (TIEMPO_MINIMO) para que se alcance a ver.
 */

/* ─── Configuración ───────────────────────────────────────────────────────── */
const TIEMPO_MINIMO = 1000; // milisegundos que se ve el cargando como mínimo

const esperar = (ms) => new Promise((resolver) => setTimeout(resolver, ms));

/* ─── Toast global (por si lo quieres usar en otras vistas) ───────────────── */
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
});

/* ─── NProgress: solo la barra de arriba ──────────────────────────────────── */
NProgress.configure({
    showSpinner: false,
    speed: 400,
    minimum: 0.15,
    trickleSpeed: 150,
});

/* ─── Estilos del cargando (se inyectan solos, no tocas ningún CSS) ───────── */
(function inyectarEstilos() {
    const css = `
        .cargando-popup {
            border-radius: 20px !important;
            padding: 2.2rem 2.8rem !important;
            width: auto !important;
            min-width: 280px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .25) !important;
            animation: cargando-entrada .25s ease-out;
        }
        .cargando-contenido { text-align: center; }

        /* Anillo giratorio con degradado */
        .cargando-anillo {
            width: 68px;
            height: 68px;
            margin: 0 auto 1.2rem;
            border-radius: 50%;
            background: conic-gradient(from 0deg, rgba(96, 96, 95, 0), #60605f);
            -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 7px), #000 calc(100% - 6px));
                    mask: radial-gradient(farthest-side, transparent calc(100% - 7px), #000 calc(100% - 6px));
            animation: cargando-giro .9s linear infinite;
        }
        .cargando-titulo {
            margin: 0 0 .3rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #2b2b2a;
        }
        .cargando-texto {
            margin: 0;
            font-size: .95rem;
            color: #8a8a87;
        }
        /* Puntitos animados después del texto */
        .cargando-puntos span {
            display: inline-block;
            animation: cargando-punto 1.2s infinite;
        }
        .cargando-puntos span:nth-child(2) { animation-delay: .2s; }
        .cargando-puntos span:nth-child(3) { animation-delay: .4s; }

        @keyframes cargando-giro { to { transform: rotate(360deg); } }
        @keyframes cargando-punto { 0%, 80%, 100% { opacity: .2; } 40% { opacity: 1; } }
        @keyframes cargando-entrada {
            from { opacity: 0; transform: scale(.9); }
            to   { opacity: 1; transform: scale(1); }
        }
    `;
    const estilo = document.createElement('style');
    estilo.textContent = css;
    document.head.appendChild(estilo);
})();

/* ─── Cuadro de cargando ──────────────────────────────────────────────────── */
function mostrarCargando(titulo = 'Procesando', texto = 'Por favor espera un momento') {
    Swal.fire({
        html: `
            <div class="cargando-contenido">
                <div class="cargando-anillo"></div>
                <h3 class="cargando-titulo">${titulo}</h3>
                <p class="cargando-texto">${texto}<span class="cargando-puntos"><span>.</span><span>.</span><span>.</span></span></p>
            </div>
        `,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        backdrop: 'rgba(30, 30, 30, .55)',
        customClass: { popup: 'cargando-popup' },
    });
}

/* ─── Textos del cargando según la acción del formulario ──────────────────── */
function textosPara(form) {
    const metodo = (form.querySelector('input[name="_method"]')?.value || form.method || 'POST').toUpperCase();
    const cambiaEstado = form.querySelector('input[name="estado"]');

    if (metodo === 'DELETE') return { titulo: 'Eliminando', texto: 'Quitando el registro' };
    if (metodo === 'PUT' && cambiaEstado) return { titulo: 'Actualizando estado', texto: 'Aplicando el cambio' };
    if (metodo === 'PUT' || metodo === 'PATCH') return { titulo: 'Guardando cambios', texto: 'Actualizando la información' };
    return { titulo: 'Guardando', texto: 'Registrando la información' };
}

/* ─── Mostrar cargando, esperar el mínimo y recién enviar ─────────────────── */
async function enviarConCarga(form) {
    if (form.dataset.enviando) return; // evita doble envío
    form.dataset.enviando = '1';

    // Un <dialog> abierto con showModal() tapa al SweetAlert, así que lo cerramos
    const dialogo = form.closest('dialog');
    if (dialogo) dialogo.close();

    const { titulo, texto } = textosPara(form);
    mostrarCargando(titulo, texto);
    NProgress.start();

    await esperar(TIEMPO_MINIMO);
    form.submit(); // form.submit() no vuelve a disparar el evento "submit"
}

/* ─── Si el usuario vuelve atrás con el botón del navegador ───────────────── */
window.addEventListener('pageshow', (e) => {
    if (e.persisted) {
        Swal.close();
        NProgress.done();
        document.querySelectorAll('form[data-enviando]').forEach((f) => delete f.dataset.enviando);
    }
});

/* ─── Spinner de barra en cualquier navegación ────────────────────────────── */
window.addEventListener('beforeunload', () => NProgress.start());

/* ─── Confirmaciones: intercepta clics en elementos con data-confirm ──────── */
document.addEventListener('click', async function (e) {
    const trigger = e.target.closest('[data-confirm]');
    if (!trigger) return;

    e.preventDefault();
    e.stopPropagation();

    const mensaje  = trigger.dataset.confirm || '¿Estás seguro?';
    const titulo   = trigger.dataset.title   || 'Confirmar acción';
    const textoBtn = trigger.dataset.btnOk   || 'Sí, continuar';
    const icono    = trigger.dataset.icon    || 'question';

    const resultado = await Swal.fire({
        title:              titulo,
        text:               mensaje,
        icon:               icono,
        showCancelButton:   true,
        confirmButtonText:  textoBtn,
        cancelButtonText:   'Cancelar',
        confirmButtonColor: '#60605f',
        cancelButtonColor:  '#e3e3df',
        reverseButtons:     true,
        focusCancel:        true,
    });

    if (!resultado.isConfirmed) return;

    // Confirmó dentro de un formulario → cargando + submit
    const form = trigger.closest('form');
    if (form) {
        await enviarConCarga(form);
        return;
    }

    // Confirmó en un link → cargando + navegar
    if (trigger.tagName === 'A' && trigger.href) {
        mostrarCargando('Cargando', 'Llevándote a la página');
        NProgress.start();
        await esperar(TIEMPO_MINIMO);
        window.location.href = trigger.href;
    }
});

/* ─── Cargando en formularios sin confirmación (crear, editar, guardar) ───── */
document.addEventListener('submit', function (e) {
    const form = e.target;

    if (e.defaultPrevented) return;
    if ((form.method || 'get').toLowerCase() === 'get') return; // filtros: sin cargando
    if (form.closest('[data-no-spinner]')) return;

    e.preventDefault();
    enviarConCarga(form);
});