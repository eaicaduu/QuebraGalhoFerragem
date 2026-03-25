document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-excluir-produto').forEach(function (btn) {

        btn.addEventListener('click', function () {

            const id = this.getAttribute('data-id');

            if (!id) return;

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

                    const card = btn.closest('.col-6, .col-md-4, .col-lg-3');
                    if (card) {
                        card.remove();
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

});