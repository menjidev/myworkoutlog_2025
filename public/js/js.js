document.addEventListener('DOMContentLoaded', function () {
  const body = document.body;

  function abrirModal(selector) {
    const modal = document.querySelector(selector);
    if (!modal) return;

    modal.classList.add('modal-ejercicio--visible');
    modal.setAttribute('aria-hidden', 'false');
    body.classList.add('no-scroll');
  }

  function cerrarModal(selector) {
    const modal = document.querySelector(selector);
    if (!modal) return;

    modal.classList.remove('modal-ejercicio--visible');
    modal.setAttribute('aria-hidden', 'true');
    body.classList.remove('no-scroll');
  }

  document.querySelectorAll('[data-modal-target]').forEach(function (trigger) {
    trigger.addEventListener('click', function (event) {
      event.preventDefault();
      const selector = this.getAttribute('data-modal-target');
      if (!selector) return;

      if (selector === '#modal-ver-notas-serie') {
        const texto = this.getAttribute('data-nota-serie') || '';
        const destino = document.getElementById('modal-ver-notas-serie-texto');
        if (destino) {
          destino.textContent = texto;
        }
      }

      abrirModal(selector);
    });
  });

  document.querySelectorAll('.modal-ejercicio [data-modal-close]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const modal = this.closest('.modal-ejercicio');
      if (!modal || !modal.id) return;
      cerrarModal('#' + modal.id);
    });
  });


  let inputNotasActual = null;

  document.querySelectorAll('.boton-añadir-serie').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const form = this.closest('form');
      if (!form) return;

      inputNotasActual = form.querySelector('input[name="notas"]');
      if (!inputNotasActual) return;

      const textarea = document.getElementById('modal-notas-serie-textarea');
      if (textarea) {
        textarea.value = inputNotasActual.value || '';
      }

      abrirModal('#modal-notas-serie');
    });
  });

  const btnGuardarNotasModal = document.getElementById('modal-notas-serie-guardar');
  if (btnGuardarNotasModal) {
    btnGuardarNotasModal.addEventListener('click', function () {
      if (!inputNotasActual) {
        cerrarModal('#modal-notas-serie');
        return;
      }

      const textarea = document.getElementById('modal-notas-serie-textarea');
      const valor = textarea ? textarea.value.trim() : '';

      inputNotasActual.value = valor;

      cerrarModal('#modal-notas-serie');
    });
  }
});
