document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btnSalvarImportado').forEach(btn => {

        btn.addEventListener('click', async function () {

            const dados = {
                codigo: this.dataset.codigo,
                nome: this.dataset.descricao,
                grupo: this.dataset.grupo,
                preco: this.dataset.preco
            };

            const botao = this;

            try {

                botao.disabled = true;
                botao.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                const response = await fetch('./app/models/importar/importar_salvar.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dados)
                });

                const data = await response.json();

                if (!response.ok || !data.status) {
                    throw new Error(data.mensagem || 'Erro ao salvar');
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Salvo!',
                    text: 'Produto salvo com sucesso'
                });

                botao.classList.remove('btn-success');
                botao.classList.add('btn-secondary');
                botao.innerHTML = '<i class="fa fa-check me-1"></i>Salvo';
                botao.disabled = true;

            } catch (error) {

                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: error.message
                });

                botao.disabled = false;
                botao.innerHTML = '<i class="fa fa-save me-1"></i>Salvar';
            }

        });

    });

});