document.addEventListener('DOMContentLoaded', function () {
    const btnExcluir = document.getElementById('btnExcluirProduto');

    if (!btnExcluir) return;

    btnExcluir.addEventListener('click', function () {

        const id = this.getAttribute('data-id');
        const radio = document.querySelector('.radio-produto:checked');

        if (!id || !radio) return;

        Swal.fire({
            title: 'Tem certeza?',
            text: 'Essa ação não pode ser desfeita.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33'
        }).then(async (result) => {

            if (!result.isConfirmed) return;

            try {

                const response = await fetch('./app/models/produto/produto_deleta.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });

                const data = await response.json();

                if (!response.ok || !data.status) {
                    throw new Error(data?.mensagem || 'Erro ao excluir.');
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Excluído',
                    text: data.mensagem || 'Produto removido com sucesso'
                });

                const card = radio.closest('.item-produto-selecao');
                if (card) {
                    card.remove();
                }

                btnExcluir.disabled = true;

                const btnEditar = document.getElementById('btnEditarProduto');
                if (btnEditar) {
                    btnEditar.disabled = true;
                }

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: error.message
                });
            }

        });

    });
});