document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('inputPesquisarProduto');
    const resultadoProdutos = document.getElementById('resultadoProdutos');

    if (!input || !resultadoProdutos) {
        return;
    }

    let timeout = null;

    function mostrarLoading() {
        resultadoProdutos.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-dark" role="status"></div>
            </div>
        `;
    }

    async function buscarProdutos(termo) {
        mostrarLoading();

        try {
            const response = await fetch('./app/models/geral/produto_pesquisar.php?pesquisa=' + encodeURIComponent(termo));
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
            buscarProdutos(input.value.trim());
        }, 300);
    });
});