document.addEventListener('DOMContentLoaded', function () {
    const btnEditar = document.getElementById('btnSelecionarEditarCarousel');
    const btnExcluir = document.getElementById('btnSelecionarExcluirCarousel');
    const itens = document.querySelectorAll('.carousel-item-select');

    if (!itens.length) return;

    let modoSelecao = null;

    function limparSelecao() {
        modoSelecao = null;

        itens.forEach(function (item) {
            item.classList.remove('modo-selecao');

            const overlay = item.querySelector('.carousel-select-overlay');
            if (overlay) {
                overlay.classList.add('d-none');
                overlay.textContent = 'Selecionar';
            }
        });

        if (btnEditar) btnEditar.classList.remove('btn-warning');
        if (btnExcluir) btnExcluir.classList.remove('btn-warning');
    }

    function ativarSelecao(tipo) {
        modoSelecao = tipo;

        itens.forEach(function (item) {
            item.classList.add('modo-selecao');

            const overlay = item.querySelector('.carousel-select-overlay');
            if (overlay) {
                overlay.classList.remove('d-none');
                overlay.textContent = tipo === 'editar' ? 'Selecionar para editar' : 'Selecionar para excluir';
            }
        });

        if (btnEditar) btnEditar.classList.remove('btn-warning');
        if (btnExcluir) btnExcluir.classList.remove('btn-warning');

        if (tipo === 'editar' && btnEditar) btnEditar.classList.add('btn-warning');
        if (tipo === 'excluir' && btnExcluir) btnExcluir.classList.add('btn-warning');
    }

    if (btnEditar) {
        btnEditar.addEventListener('click', function () {
            if (modoSelecao === 'editar') {
                limparSelecao();
                return;
            }
            ativarSelecao('editar');
        });
    }

    if (btnExcluir) {
        btnExcluir.addEventListener('click', function () {
            if (modoSelecao === 'excluir') {
                limparSelecao();
                return;
            }
            ativarSelecao('excluir');
        });
    }

    itens.forEach(function (item) {
        item.addEventListener('click', async function () {
            if (!modoSelecao) return;

            const id = this.getAttribute('data-id');
            if (!id) return;

            if (modoSelecao === 'editar') {
                window.location.href = `admin.php?page=configuracoes&acao=editar&id=${id}`;
                return;
            }

            if (modoSelecao === 'excluir') {
                excluirImagemCarousel(item, id, limparSelecao);
            }
        });
    });
});