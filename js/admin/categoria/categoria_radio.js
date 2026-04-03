document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (e) {

        const card = e.target.closest('.item-categoria');
        if (card) {
            const radio = card.querySelector('.radio-categoria');
            if (!radio) return;

            radio.checked = true;
            radio.dispatchEvent(new Event('change', { bubbles: true }));

            const btnEditar = document.getElementById('btnEditarCategoria');
            const btnExcluir = document.getElementById('btnExcluirCategoria');

            if (btnEditar && btnExcluir) {
                const id = radio.value;

                btnEditar.disabled = false;
                btnExcluir.disabled = false;

                btnEditar.dataset.id = id;
                btnExcluir.dataset.id = id;
            }

            return;
        }

        if (e.target.closest('#btnEditarCategoria')) {
            const btn = document.getElementById('btnEditarCategoria');
            if (!btn) return;

            const id = btn.dataset.id;
            if (!id) return;

            window.location.href = `admin.php?page=categoria&acao=editar categoria&id=${id}`;
        }

    });

});