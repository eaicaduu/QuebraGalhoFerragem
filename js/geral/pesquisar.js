document.addEventListener('DOMContentLoaded', function () {
    const inputDesktop = document.getElementById('inputPesquisarProduto');
    const inputMobile = document.getElementById('inputPesquisarProdutoMobile');
    const resultadoProdutos = document.getElementById('resultadoProdutos');

    if ((!inputDesktop && !inputMobile) || !resultadoProdutos) {
        return;
    }

    let timeout = null;
    const contexto = (inputDesktop || inputMobile).dataset.contexto || 'usuario';

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

    function handleInput() {
        clearTimeout(timeout);

        const currentInput = this; // 'this' refers to the input that triggered the event
        timeout = setTimeout(function () {
            const termo = tratarTermo(currentInput.value);
            if (termo.length === 0) {
                // Recarregar produtos originais
                buscarProdutos('');
                return;
            }
            if (termo.length < 2) {
                return;
            }
            buscarProdutos(termo);
        }, 300);
    }

    // Attach event listeners to both inputs if they exist
    if (inputDesktop) {
        inputDesktop.addEventListener('input', handleInput);
    }
    if (inputMobile) {
        inputMobile.addEventListener('input', handleInput);
    }
});