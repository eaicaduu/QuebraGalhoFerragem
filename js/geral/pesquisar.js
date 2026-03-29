document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('inputPesquisarProduto');
    const resultadoProdutos = document.getElementById('resultadoProdutos');

    if (!input || !resultadoProdutos) {
        return;
    }

    let timeout = null;
    const contexto = input.dataset.contexto || 'usuario';

    function mostrarLoading() {
        resultadoProdutos.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-dark" role="status"></div>
            </div>
        `;
    }

    function tratarTermo(valor) {
        return valor
            .replace(/[\x00-\x1F\x7F]/g, '')
            .trim()
            .slice(0, 100);
    }

    async function buscarProdutos(termo) {
        mostrarLoading();

        try {
            const response = await fetch(
                './app/models/geral/pesquisar.php?pesquisa='
                + encodeURIComponent(termo)
                + '&contexto='
                + encodeURIComponent(contexto),
                {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }
            );

            const data = await response.json();

            if (!response.ok || !data.status) {
                throw new Error(data?.mensagem || 'Erro ao pesquisar produtos.');
            }

            resultadoProdutos.innerHTML = data.html;
        } catch (error) {
            resultadoProdutos.innerHTML = `
                <div class="alert alert-danger text-center">
                    ${error.message || 'Erro ao carregar produtos.'}
                </div>
            `;
        }
    }

    input.addEventListener('input', function () {
        clearTimeout(timeout);

        timeout = setTimeout(function () {
            const termo = tratarTermo(input.value);
            buscarProdutos(termo);
        }, 300);
    });
});