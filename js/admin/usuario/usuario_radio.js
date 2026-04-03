document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const card = e.target.closest('.item-usuario-selecao');
        if (!card) return;

        if (e.target.closest('a, button, input, label')) {
            if (!e.target.classList.contains('radio-usuario')) {
                return;
            }
        }

        const radio = card.querySelector('.radio-usuario');
        if (!radio) return;

        radio.checked = true;
        radio.dispatchEvent(new Event('change', { bubbles: true }));

        const btnEditar = document.getElementById('btnEditarUsuario');
        const btnWhatsapp = document.getElementById('btnWhatsappUsuario');

        if (btnEditar) {
            btnEditar.disabled = false;
            btnEditar.dataset.id = radio.value;
            btnEditar.dataset.tipo = radio.dataset.tipo || '1';
        }

        if (btnWhatsapp) {
            const telefone = radio.dataset.telefone || '';
            btnWhatsapp.dataset.telefone = telefone;
            btnWhatsapp.disabled = telefone === '';
        }
    });

    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('radio-usuario')) return;

        const btnEditar = document.getElementById('btnEditarUsuario');
        const btnWhatsapp = document.getElementById('btnWhatsappUsuario');

        if (btnEditar) {
            btnEditar.disabled = false;
            btnEditar.dataset.id = e.target.value;
            btnEditar.dataset.tipo = e.target.dataset.tipo || '1';
        }

        if (btnWhatsapp) {
            const telefone = e.target.dataset.telefone || '';
            btnWhatsapp.dataset.telefone = telefone;
            btnWhatsapp.disabled = telefone === '';
        }
    });

    const btnEditar = document.getElementById('btnEditarUsuario');

    if (btnEditar) {
        btnEditar.addEventListener('click', async function () {
            const id = this.dataset.id;
            if (!id) return;

            await editarUsuario(this);
        });
    }

    const btnWhatsapp = document.getElementById('btnWhatsappUsuario');

    if (btnWhatsapp) {
        btnWhatsapp.addEventListener('click', function () {
            const telefone = this.dataset.telefone;
            if (!telefone) return;

            window.open(`https://wa.me/55${telefone}`, '_blank');
        });
    }
});