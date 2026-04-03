document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (e) {
        const card = e.target.closest('.item-produto-selecao');
        if (!card) return;

        const radio = card.querySelector('.radio-produto');
        if (!radio) return;

        radio.checked = true;
        radio.dispatchEvent(new Event('change', { bubbles: true }));

        const btnEditar = document.getElementById('btnEditarProduto');
        const btnExcluir = document.getElementById('btnExcluirProduto');

        if (!btnEditar || !btnExcluir) return;

        const id = radio.value;

        btnEditar.disabled = false;
        btnExcluir.disabled = false;

        btnEditar.dataset.id = id;
        btnExcluir.dataset.id = id;
    });

    const btnEditar = document.getElementById('btnEditarProduto');

    if (btnEditar) {
        btnEditar.addEventListener('click', function () {
            const id = this.dataset.id;
            if (!id) return;

            window.location.href = `admin.php?page=produto&acao=editar produto&id=${id}`;
        });
    }

});