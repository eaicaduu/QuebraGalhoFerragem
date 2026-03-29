document.addEventListener('DOMContentLoaded', function () {
    const btnExcluir = document.getElementById('btnExcluirCategoria');

    if (!btnExcluir) return;

    btnExcluir.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        const radio = document.querySelector('.radio-categoria:checked');

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
                const response = await fetch('./app/models/categoria/categoria_deleta.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });

                const data = await response.json();

                if (!response.ok || !data.status) {
                    Swal.fire('Erro', data.mensagem || 'Erro ao excluir.', 'error');
                    return;
                }

                await Swal.fire('Sucesso', data.mensagem, 'success');

                const radioSelecionado = document.querySelector('.radio-categoria:checked');
                const card = radioSelecionado?.closest('.item-categoria');

                if (card) card.remove();

                this.disabled = true;

                const btnEditar = document.getElementById('btnEditarCategoria');
                if (btnEditar) {
                    btnEditar.disabled = true;
                    delete btnEditar.dataset.id;
                }

                delete this.dataset.id;

            } catch (error) {
                Swal.fire('Erro', 'Erro inesperado ao excluir a categoria.', 'error');
                console.error(error);
            }
        });
    });
});