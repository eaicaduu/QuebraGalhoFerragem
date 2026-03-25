document.addEventListener('DOMContentLoaded', function () {

    const btnCancelar = document.getElementById('btnCancelarImportacao');

    if (!btnCancelar) return;

    btnCancelar.addEventListener('click', async function () {

        const confirm = await Swal.fire({
            icon: 'warning',
            title: 'Cancelar importação?',
            text: 'Os dados carregados serão perdidos.',
            showCancelButton: true,
            confirmButtonText: 'Sim, cancelar',
            cancelButtonText: 'Voltar'
        });

        if (!confirm.isConfirmed) return;

        try {

            const response = await fetch('./app/models/importar/importar_cancel.php', {
                method: 'POST'
            });

            const data = await response.json();

            if (!response.ok || !data.status) {
                throw new Error('Erro ao cancelar importação');
            }

            window.location.href = 'admin.php?page=importar&acao=importar';

        } catch (e) {
            Swal.fire('Erro', 'Não foi possível cancelar.', 'error');
        }

    });

});